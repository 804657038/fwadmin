<?php
namespace app\admin\controller;
use think\Controller;
use think\Exception;
use think\Request;
use app\admin\model\Admin;
use app\admin\model\Log;
class Login extends Controller{
    public function initialize()
    {
        parent::initialize();
    }
    /*执行登录*/
    public function login(Request $request,Admin $model,Log $log){
        try{
            $param=$request->param();

            $password=decrypt2($param['password']);

            $admin=$model->where('username',$param['username'])->find();
            if($admin['status']==0 || !$admin){
                $this->result('',0,'账号禁止登录','json');
            }
            $paswd=md5($param['username'].md5($password.$admin['key_code']));
            if($paswd!=$admin['password'] || !$admin){

                $this->result('',0,'账号/密码错误','json');
            }
            /*生成token*/

            $cacheId=md5($admin['username'].'_'.$admin['id']);
            $cacheContent=[
                'id'=>$admin['id'],
                'username'=>$admin['username'],
                'roles'=>explode(',',$admin['auth']),
                'avatar'=>$admin['avatar'],
                'group_id'=>$admin['group_id'],
                'name'=>$admin['name'],
            ];
            cache($cacheId,$cacheContent,86400);
            $token=encrypt($admin['username']);
            $logData=[
                'user_id'=>$admin['id'],
                'code'=>200,
                'msg'=>'登录成功',
                'data'=>json_encode($param),
                'add_time'=>date('Y-m-d H:i:s'),
                'module'=>$request->module(),
                'controller'=>$request->controller(),
                'action'=>$request->action(),
                'method'=>$request->method(),
            ];
            $log->setlog($logData);
            return json([
                'code'=>200,
                'token'=>$token
            ]);
        }catch (Exception $e){
            $this->result('',0,$e->getMessage(),'json');

        }
    }


}