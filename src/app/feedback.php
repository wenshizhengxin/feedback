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


use epii\admin\center\config\Settings;
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
            $typeOptions = LibFeedback::getTypeOptions(['id' => '', 'name' => '————请选择————']);
            $this->assign('typeOptions', $typeOptions);
            $levelOptions = LibFeedback::getLevelOptions(['id' => '', 'name' => '————请选择————']);
            $this->assign('levelOptions', $levelOptions);
            $statusOptions = LibFeedback::getStatusOptions(['id' => '', 'name' => '————请选择————']);
            $this->assign('statusOptions', $statusOptions);

            $this->adminUiDisplay();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function ajax_data()
    {
        try {
            $where = [];

            $role = intval(Session::get('admin_gid'));
            $seniorAuthRoles = array_filter(explode(',', Settings::get(Constant::ADDONS . '.senior_auth_roles')));
            if ($role !== 1 && in_array($role, $seniorAuthRoles) === false) { // 你没有查看所有反馈的权限。。。
                $where[] = [
                    'f.from_uid', '=', Session::get('user_id'),
                ];
            }

            if ($feedback_title = Args::params('feedback_title')) {
                $where[] = [
                    'f.feedback_title', 'like', '%' . $feedback_title . '%'
                ];
            }
            if ($feedback_type = Args::params('feedback_type', '')) {
                $where[] = [
                    'f.feedback_type', '=', $feedback_type
                ];
            }
            if ($level = Args::params('level', '')) {
                $where[] = [
                    'f.level', '=', $level
                ];
            }
            $status = Args::params('status', '');
            if ($status !== '') {
                $where[] = [
                    'f.status', '=', $status
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

    public function add()
    {
        try {
            $id = Args::params('id/d', 0);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $insertData = [
                    'feedback_title' => Args::params('feedback_title/s/1'),
                    'feedback_type' => Args::params('feedback_type/1'),
                    'level' => Args::params('level/1'),
                    'description' => Args::params('description/s'),
                    'img' => Args::params('img/s'),
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
                $this->success('操作成功', 'refresh');
            } else {
                if ($id > 0) {
                    $feedback = Db::name('feedback')->where('id', $id)->find();
                    $this->assign('feedback', $feedback);
                }

                // 绑定下拉框选项数据
                $typeOptions = LibFeedback::getTypeOptions(['id' => '', 'name' => '————请选择————']);
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
            $timestamp = time();
            $updateData = ['status' => $status, 'finish_time' => $timestamp, 'update_time' => $timestamp];
            if ($status !== Constant::STATUS_FINISHED) { // 不是完成，finish_time不能记录
                unset($updateData['finish_time']);
            }
            $res = Db::name(Constant::TABLE_FEEDBACK)->where('id', $id)->update($updateData);
            if (!$res) {
                throw new \Exception('修改失败');
            }

            exit(json_encode(['code' => 1, 'msg' => '成功'], JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            exit(json_encode(['code' => 0, 'msg' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
        }
    }
}
