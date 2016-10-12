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

use think\Controller;

/**
 * 插件执行默认控制器
 * Class AddonsController
 * @package think\addons
 */
class AddonsController extends Controller
{
    // 当前插件操作
    protected $addon = null;
    protected $controller = null;
    protected $action = null;

    /**
     * 插件初始化
     */
    public function _initialize()
    {
        $this->addon = ucfirst($this->request->param('_addon/s', ''));
        $this->controller = ucfirst($this->request->param('_controller/s', ''));
        $this->action = $this->request->param('_action/s', '');
    }

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
