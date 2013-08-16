<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

/**
 * 扩展后台管理页面
 * @author yangweijie <yangweijiester@gmail.com>
 */

class AddonsController extends AdminController {
    static protected $nodes = array(
        array( 'title'=>'模型管理', 'url'=>'Addons/index', 'group'=>'扩展'),
        array( 'title'=>'插件管理', 'url'=>'Addons/index', 'group'=>'扩展'),
        array( 'title'=>'钩子管理', 'url'=>'Addons/hooks', 'group'=>'扩展'),
    );

    public function index(){
        $this->assign('list',D('Addons')->getList());
        $this->display();
    }

    /**
     * 启用插件
     */
    public function enable(){
        $id = I('id');
        $flag = D('Addons')->where("id={$id}")->setField('status', 1);
        if($flag !== false)
            $this->success('启用成功');
        else
            $this->error('启用失败');
    }

    /**
     * 禁用插件
     */
    public function disable(){
        $id = I('id');
        $flag = D('Addons')->where("id={$id}")->setField('status', 0);
        if($flag !== false)
            $this->success('禁用成功');
        else
            $this->error('禁用失败');
    }

    /**
     * 设置插件页面
     */
    public function config(){
        $id = (int)I('id');
        $addon = D('Addons')->find($id);
        if(!$addon)
            $this->error('插件未安装');
        $this->assign('data',$addon);
        $config_tpl = C('EXTEND_MODULE.Addons')."{$addon['name']}/View/Config/config.html";
        $this->display($config_tpl);
    }

    /**
     * 钩子列表
     */
    public function hooks(){
        $order = $field = array();
        $this->assign('list', D('Hooks')->field($field)->order($order)->select());
        $this->display();
    }

    public function updateSort(){
        $addons = trim(I('addons'));
        $id = I('id');
        D('Hooks')->where("id={$id}")->setField('addons', $addons);
        $this->success('更新排序成功');
    }

    public function execute($_addons = null, $_controller = null, $_action = null){
        if(C('URL_CASE_INSENSITIVE')){
            $_addons = ucfirst(strtolower($_addons));
            $_controller = parse_name($_controller,1);
        }

        if(!empty($_addons) && !empty($_controller) && !empty($_action)){
            $Addons = A("Addons://{$_addons}/{$_controller}")->setName($_addons)->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    /**
     * 设置当前插件名称
     * @param string $name 插件名称
     */
    protected function setName($name){
        $this->addons = $name;
        return $this;
    }
}
