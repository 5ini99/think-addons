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
            $class = get_addon_class($this->addon, 'controller') . "\\{$this->controller}";
            $model = new $class();
            if ($model === false) {
                return $this->error(lang('addon init fail'));
            }
            // 调用操作
            return call_user_func([$model, $this->action]);
        }

        return $this->error(lang('addon cannot name or action'));
    }
}
