<?php

namespace app\admin\model;

use think\Exception;
use think\Model;

class Admin extends Model
{
    //
    public function getGroupIdAttr($value){
        $name=(new AdminRole)->where('id',$value)->value('name');
        return [
            'id'=>$value,
            'name'=>$name
        ];
    }
    /*
     * 账号编辑
     * */
    public function setSave($data){
        try{
            $saveData=[
                'username'=>$data['username'],
                'group_id'=>$data['group_id'],
                'email'=>$data['email'],
                'mobile'=>$data['mobile'],
                'name'=>$data['name'],
                'avatar'=>$data['avatar'],
                'update_time'=>date('Y-m-d H:i:s'),
                'status'=>$data['status'],
            ];
            $auth=(new AdminRole)->where('id',$data['group_id'])->value('auth');
            $saveData['auth']=$auth;
            if(!empty($data['password'])){
                $rel='/^[A-Za-z0-9]{6,12}$/';
                if(!preg_match($rel,$data['password'])){
                    return [
                        'code'=>0,
                        'msg'=>'密码必须是6-12位的数字或字母'
                    ];
                }
                if($data['confirm_password'] != $data['password']){
                    return [
                        'code'=>0,
                        'msg'=>'两次密码不输入不一致'
                    ];
                }
                $rand=randChar(6);
                $paswd=md5($data['username'].md5($data['password'].$rand));
                $saveData['password']=$paswd;
                $saveData['key_code']=$rand;
            }

            if(isset($data['id']) && !empty($data['id'])){
                $this->save($saveData,['id'=>$data['id']]);
            }else{
                $this->save($saveData);
            }
            return [
                'code'=>200,
                'msg'=>'发布成功'
            ];
        }catch (Exception $e){
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }
}
