<?php
/**
 * 描述：
 * Created at 2021/5/17 15:35 by Temple Chan
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/17
 * Time: 17:35
 */
namespace wenshizhengxin\feedback\libs;


class Feedback
{
    public static function getTypeOptions($unshiftArray = null)
    {
        $result = [
            ['id' => Constant::TYPE_REQUIREMENT, 'name' => '项目需求'],
            ['id' => Constant::TYPE_BUG, 'name' => '项目Bug'],
            ['id' => Constant::TYPE_SUGGESTION, 'name' => '优化建议'],
        ];
        if ($unshiftArray !== null) {
            array_unshift($result, $unshiftArray);
        }

        return $result;
    }

    public static function getTypeDesc($key)
    {
        $map = [];
        foreach (self::getTypeOptions() as $item) {
            $map[$item['id']] = $item['name'];
        }

        return $map[$key] ?? '未知类型';
    }

    public static function getLevelOptions($unshiftArray = null)
    {
        $result = [
            ['id' => Constant::LEVEL_NONE, 'name' => '放弃'],
            ['id' => Constant::LEVEL_NORMAL, 'name' => '一般'],
            ['id' => Constant::LEVEL_HIGH, 'name' => '紧急'],
            ['id' => Constant::LEVEL_EMERGENCY, 'name' => '非常紧急'],
        ];

        if ($unshiftArray !== null) {
            array_unshift($result, $unshiftArray);
        }

        return $result;
    }

    public static function getLevelDesc($key)
    {
        $map = [];
        foreach (self::getLevelOptions() as $item) {
            $map[$item['id']] = $item['name'];
        }

        return $map[$key] ?? '未知级别';
    }

    public static function getStatusOptions($unshiftArray = null)
    {
        $result = [
            ['id' => Constant::STATUS_PENDING, 'name' => '已提交'],
            ['id' => Constant::STATUS_RECEIVED, 'name' => '已阅览'],
            ['id' => Constant::STATUS_DEALING, 'name' => '处理中'],
            ['id' => Constant::STATUS_RETURNED, 'name' => '已退回'],
            ['id' => Constant::STATUS_FINISHED, 'name' => '已解决'],
        ];

        if ($unshiftArray !== null) {
            array_unshift($result, $unshiftArray);
        }

        return $result;
    }

    public static function getStatusDesc($key)
    {
        $map = [];
        foreach (self::getStatusOptions() as $item) {
            $map[$item['id']] = $item['name'];
        }

        return $map[$key] ?? '未知状态';
    }

    public static function getImgSrcs($img, $assoc = false)
    {
        $result = [];
        foreach (array_filter(explode(',', $img)) as $src) {
            if (strpos($src, 'http') === 0) {
                $result[] = $src;
            } else {
                $result[] = 'upload/' . str_replace('\\', '/', $src);
            }
        }

        return $assoc === true ? $result : implode(',', $result);
    }
}
