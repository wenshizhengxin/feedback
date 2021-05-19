<?php
/**
 * 描述：
 * Created at 2021/5/17 15:20 by Temple Chan
 */

namespace wenshizhengxin\feedback\libs;


class Constant
{
    const ADDONS = 'wenshizhengxin/feedback';

    const TABLE_FEEDBACK = 'feedback';
    const TABLE_ADMIN = 'admin';


    const TYPE_REQUIREMENT = 1;
    const TYPE_BUG = 2;
    const TYPE_SUGGESTION = 3;

    const LEVEL_NONE = 1;
    const LEVEL_NORMAL = 10;
    const LEVEL_HIGH = 20;
    const LEVEL_EMERGENCY = 99;

    const STATUS_PENDING = 0;
    const STATUS_RECEIVED = 1;
    const STATUS_DEALING = 5;
    const STATUS_RETURNED = 9;
    const STATUS_FINISHED = 10;
}