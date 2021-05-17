<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/11
 * Time: 10:32
 */

namespace wenshizhengxin\feedback\app;


use epii\admin\center\admin_center_addons_controller;
use epii\admin\center\admin_center_controller;
use epii\admin\ui\lib\epiiadmin\jscmd\Alert;
use epii\admin\ui\lib\epiiadmin\jscmd\Close;
use epii\admin\ui\lib\epiiadmin\jscmd\CloseAndRefresh;
use epii\admin\ui\lib\epiiadmin\jscmd\JsCmd;
use epii\admin\ui\lib\epiiadmin\jscmd\Refresh;
use epii\server\Tools;
use epii\template\engine\EpiiViewEngine;
use wenshizhengxin\feedback\libs\Constant;

class base extends admin_center_addons_controller
{
    public function __construct()
    {
        // 因为项目目录结构发生了改变，所以得特别设置view引擎
        $engine = new EpiiViewEngine();
        $engine->init(["tpl_dir" => __DIR__ . "/../view/", "cache_dir" => Tools::getRuntimeDirectory() . "/cache/view/"]);
        $this->setViewEngine($engine);

        $this->assign('__addons', Constant::ADDONS);
    }

    /**
     * 功能：成功响应
     * Created at 2021/2/23 16:35 by Temple Chan
     * @param string $msg
     * @param string $sufAction
     * @return array|false|string
     */
    public function success($msg = '成功', $sufAction = 'close_and_refresh')
    {
        if ($sufAction === 'close') {
            $action = Close::make();
        } else if ($sufAction === 'refresh') {
            $action = Refresh::make();
        } else {
            $action = CloseAndRefresh::make();
        }
        $cmd = Alert::make()->icon('6')->msg($msg)->onOk($action);

        exit(JsCmd::make()->addCmd($cmd)->run());
    }

    /**
     * 功能：失败响应
     * Created at 2021/2/23 16:35 by Temple Chan
     * @param string $msg
     * @return array|false|string
     */
    public function error($msg = '失败')
    {
        $cmd = Alert::make()->icon('5')->msg($msg)->onOk(null);
        exit(JsCmd::make()->addCmd($cmd)->run());
    }
}