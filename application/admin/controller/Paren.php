<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Admin;
class Paren extends Controller{
    protected $middleware = ['Admin','Log'];
    public function initialize()
    {
        parent::initialize();
//        $this->login();
    }
    public function adminAutho($autho){

        $arr=explode(',',USER['auth']);
        if(!in_array($autho,$arr)){
            $this->result('',0,'您没有权限','json');
        }
    }

}
?>