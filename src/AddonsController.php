<?php
// +----------------------------------------------------------------------
// | thinkphp5 Addons [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.zzstudio.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Byron Sampson <xiaobo.sun@qq.com>
// +----------------------------------------------------------------------
namespace think\addons;

use think\Config;
use think\exception\HttpException;

/**
 * 插件执行默认控制器
 * Class AddonsController
 * @package think\addons
 */
class AddonsController extends Controller
{
    /**
     * 插件执行
     */
    public function execute()
    {
        if (!empty($this->addon) && !empty($this->controller) && !empty($this->action)) {
            // 获取类的命名空间
            $class = get_addon_class($this->addon, 'controller', $this->controller);
            if (class_exists($class)) {
                $instance = new $class();
            } else {
                // 查看是否有空控制器
                $class = get_addon_class($this->addon, 'controller', Config::get('empty_controller'));
                if (class_exists($class)) {
                    $instance = new $class();
                }
            }
            // 如果不存在实例则直接抛出错误
            if(!isset($instance)){
                $this->error(lang('Controller Class Not Exists'));
            }
            // 检测要调用的方法是否存在
            if (is_callable([$instance, $this->action])) {
                // 执行操作方法
                $call = [$instance, $this->action];
            } elseif (is_callable([$instance, '_empty'])) {
                // 空操作
                $call = [$instance, '_empty'];
            } else {
                // 操作不存在
                throw new HttpException(404, 'method not exists:' . get_class($instance) . '->' . $this->action . '()');
            }
            // 调用操作
            return call_user_func($call);
        }
        $this->error(lang('addon cannot name or action'));
    }
}
