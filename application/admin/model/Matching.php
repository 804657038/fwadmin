<?php

namespace app\admin\model;
use think\Exception;
use think\Model;

class Matching extends Model
{
    /*添加或修改信息*/
    public function add($data){
        try{
            $validate = new \app\admin\validate\Matching;
            $data['update_time']=date('Y-m-d H:i:s');
            if (!$validate->check($data)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }
            if(isset($data['id']) && !empty($data['id'])){
                $this->allowField(true)->save($data,['id'=>$data['id']]);
            }else{
                $data['add_time']=date('Y-m-d H:i:s');
                $this->allowField(true)->save($data);
            }
            return [
                'code'=>200,
                'msg'=>'保存成功'
            ];
        }catch (Exception $e){
            return [
                'code'=>200,
                'msg'=>$e->getMessage()
            ];
        }
    }
}
