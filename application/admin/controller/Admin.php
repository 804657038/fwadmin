<?php
namespace app\admin\controller;
/**
 * @title 后台管理员控制器
 * @description ...
 */
use think\Exception;
use think\Request;
use app\admin\model\Admin as model;
class Admin extends Paren{
    /**
     * @title 获取管理员列表
     * @description
     * @author
     * @url /admin/admin/lst
     * @method GET
     * @param name:limit type:int require:1 default: other: desc:每页默认条数
     * @param name:page type:int require:1 default: other: desc:分页参数
     * @param name:search type:string require:0 default: other: desc:搜索关键字，按姓名电话搜索
     * @return data:返回信息
     */
     public function lst(Request $request,model $admin){
         self::adminAutho('admin');
         $param=$request->param();
         $row=$param['limit']??10;
         $where=[];
         if(isset($param['search'])){
             $where[]=['name','like','%'.$param['search'].'%','or'];
             $where[]=['mobile','like','%'.$param['search'].'%','or'];
         }
         $list=$admin->where($where)->order('id asc')->paginate($row);
         $this->result($list,200,'','json');
     }
    /**
     * @title 管理员编辑
     * @description
     * @author
     * @url /admin/admin/add
     * @method POST
     * @param name:data type:int require:1 default: other: desc:编辑数据包
     * @return data:返回信息
     */
    public function add(Request $request,model $admin){
        $param=$request->param();
        $unique=['require','alphaNum'];
        $unique['unique']='admin,username';
        if(isset($param['id'])){
            $unique['unique']="admin,username,".$param['id'];
        }
        $validate=$this->validate($param,[
            'username|账号' => $unique,
            'key_code|密钥'=>'require|alphaNum',
            'group_id|所属角色'=>'require|number',
            'status|账号状态'=>'require|in:0,1',

        ]);
        if(true !== $validate){
            $this->result('',200,$validate,'json');
        }
        $result=$admin->setSave($param);
        $this->result('',$result['code'],$result['msg'],'json');
    }
    /**
     * @title 获取登录的管理员信息
     * @description
     * @author
     * @url /admin/admin/info
     * @method GET
     * @return data:返回信息
     */
    public function info(){
        try{

            $this->result(USER,200,'','json');
        }catch (Exception $e){

            $this->result('',0,$e->getMessage(),'json');
        }
    }
    /**
     * @title 退出登录
     * @description
     * @author
     * @url /admin/admin/logout
     * @method post
     * @return data:返回信息
     */
    public function logout(){
        try{
            $header=request()->header('Authorization');
            $data=explode(' ', $header);
            $data_1=decrypt(decrypt2(($data[1])));
            if(!$data_1){
                $this->result('',0,'登录状态错误','json');
            }
            $userData=(new model)->where('username',$data_1)->field('id')->find();
            $cacheId=md5($data_1.'_'.$userData['id']);
            cache($cacheId,null);
            $this->result('',200,'退出成功','json');
        }catch (Exception $e){
            $this->result('',0,$e->getMessage(),'json');
        }
    }
}
