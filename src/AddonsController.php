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
                $model = new $class();
                if ($model === false) {
                    $this->error(lang('addon init fail'));
                }
                // 调用操作
                if (!method_exists($model, $this->action)) {
                    $this->error(lang('Controller Class Method Not Exists'));
                }
                return call_user_func([$model, $this->action]);
            } else {
                $this->error(lang('Controller Class Not Exists'));
            }
        }
        $this->error(lang('addon cannot name or action'));
    }
}
