<?php
/**
 * 描述：
 * Created at 2021/5/17 15:27 by Temple Chan
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/17
 * Time: 15:28
 */

namespace wenshizhengxin\feedback\app;


use epii\server\Args;
use think\Db;
use wangshouwei\session\Session;
use wenshizhengxin\feedback\libs\Constant;
use wenshizhengxin\feedback\libs\Feedback as LibFeedback;
use wenshizhengxin\feedback\libs\Mail;

class feedback extends base
{
    public function index()
    {
        try {
            $this->adminUiDisplay();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function add()
    {
        try {
            $id = Args::params('id/d', 0);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $insertData = [
                    'feedback_title' => Args::params('feedback_title/反馈消息标题/ss/ll'),
                    'feedback_type' => Args::params('feedback_type/反馈消息类型/ss/ll'),
                    'level' => Args::params('level/反馈级别/ss/ll'),
                    'description' => Args::params('description/反馈描述/ss/ll'),
                    'img' => Args::params('img/图片/ss/ll'),
                    'access_time' => Args::params('access_time/访问时间/ss/ll'),
                    'create_time' => Args::params('create_time/添加时间/ss/ll'),
                ];

                $timestamp = time();

                /************事务开始************/
                Db::startTrans();
                if ($id === 0) { // 新增
                    $insertData['create_time'] = $timestamp;
                    $insertData['from_uid'] = Session::get('user_id');
                    $res = Db::name('feedback')->insert($insertData, false, true);
                    if (!$res) {
                        throw new \Exception('添加失败');
                    }

                    Mail::send('*', '【文始反馈】' . $insertData['feedback_title'], nl2br($insertData['description']));
                } else { // 修改
                    $insertData['update_time'] = $timestamp;
                    $res = Db::name('feedback')->where('id', $id)->update($insertData);
                    if (!$res) {
                        throw new \Exception('修改失败');
                    }
                }

                Db::commit();
                /************事务结束************/

                $this->success();
            } else {
                if ($id > 0) {
                    $feedback = Db::name('feedback')->where('id', $id)->find();
                    $this->assign('feedback', $feedback);
                }

                $typeOptions = LibFeedback::getTypeOptions();
                $this->assign('typeOptions', $typeOptions);

                $levelOptions = LibFeedback::getLevelOptions();
                $this->assign('levelOptions', $levelOptions);

                $statusOptions = LibFeedback::getStatusOptions();
                $this->assign('statusOptions', $statusOptions);
                $this->adminUiDisplay();
            }
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
    }

    public function ajax_data()
    {
        try {
            $where = [];

            if ($feedback_title = Args::params('feedback_title')) {
                $where[] = [
                    'f.feedback_title', 'like', '%' . $feedback_title . '%'
                ];
            }
            if ($feedback_type = Args::params('feedback_type')) {
                $where[] = [
                    'f.feedback_type', 'like', '%' . $feedback_type . '%'
                ];
            }
            if ($level = Args::params('level')) {
                $where[] = [
                    'f.level', 'like', '%' . $level . '%'
                ];
            }

            $query = Db::name(Constant::TABLE_FEEDBACK)
                ->alias('f')
                ->leftJoin(Constant::TABLE_ADMIN . ' a', 'f.from_uid=a.id')
                ->field('f.*,a.group_name as from_uname')
                ->order('f.id desc');

            return $this->tableJsonData($query, $where, function ($row) {
                $row['type_desc'] = LibFeedback::getTypeDesc($row['feedback_type']);
                $row['level_desc'] = LibFeedback::getLevelDesc($row['level']);
                $row['status_desc'] = LibFeedback::getStatusDesc($row['status']);
                $row['access_time'] = $row['access_time'] ? date('Y-m-d H:i:s', $row['access_time']) : '-';
                $row['create_time'] = $row['create_time'] ? date('Y-m-d H:i:s', $row['create_time']) : '-';
                $row['update_time'] = $row['update_time'] ? date('Y-m-d H:i:s', $row['update_time']) : '-';
                return $row;
            });
        } catch (\Exception $e) {
        }
    }

    public function del()
    {
        try {
            $id = Args::params('id');
            $res = Db::name(Constant::TABLE_FEEDBACK)->where('id', $id)->delete();
            if (!$res) {
                throw new \Exception('删除失败');
            }

            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function detail()
    {
        try {
            $id = Args::params('id/d/1');

            $feedback = Db::name(Constant::TABLE_FEEDBACK)->where('id', $id)->find();

            $feedback['type_desc'] = LibFeedback::getTypeDesc($feedback['feedback_type']);
            $feedback['level_desc'] = LibFeedback::getLevelDesc($feedback['level']);
            $feedback['status_desc'] = LibFeedback::getStatusDesc($feedback['status']);
            $feedback['access_time'] = $feedback['access_time'] ? date('Y-m-d H:i:s', $feedback['access_time']) : '-';
            $feedback['create_time'] = $feedback['create_time'] ? date('Y-m-d H:i:s', $feedback['create_time']) : '-';
            $feedback['update_time'] = $feedback['update_time'] ? date('Y-m-d H:i:s', $feedback['update_time']) : '-';
            $feedback['show_url'] = LibFeedback::getImgSrcs($feedback['img']);

            if ($feedback['status'] === Constant::STATUS_PENDING) { // 第一次看，直接标记已读
                $timestamp = time();
                Db::name(Constant::TABLE_FEEDBACK)->where('id', $id)
                    ->update(['status' => Constant::STATUS_RECEIVED, 'access_time' => $timestamp, 'update_time' => $timestamp]);
            }

            $this->assign('feedback', $feedback);

            $this->adminUiDisplay();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
    }

    public function change_status()
    {
        try {
            $id = Args::params('id/d/1');
            $status = Args::params('status/d/1');
            $res = Db::name(Constant::TABLE_FEEDBACK)->where('id', $id)->update(['status' => $status, 'update_time' => time()]);
            if (!$res) {
                throw new \Exception('修改失败');
            }

            exit(json_encode(['code' => 1, 'msg' => '成功'], JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            exit(json_encode(['code' => 0, 'msg' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
        }
    }
}