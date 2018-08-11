<?php

namespace app\admin\model;

use think\Exception;
use think\Model;

class CateAttr extends Model
{
    //列表
    public function lst($where=[]){
        try{
            $pagelist=$data=$this->where($where)->select();
            foreach ($pagelist as $key=>$vale){
                $pagelist[$key]['attr']=explode(',',$vale['attr']);
            }
            $data=[];
            $data['total']=1;
            $data['data']=$pagelist;
            return $data;
        }catch (Exception $e){
            return $e->getMessage();
        }

    }

    //获取添加/编辑页面需要的数据
    public function addpage(){
        $where=[];
        $where['display']=1;
        $data=db('cate')->where($where)->select();
        return [
            'code'=>200,
            'data'=>$data
        ];
    }

    //添加 编辑
    public function setSave($data){
        try{
            if(isset($data['attr'])){
                $data['attr']=implode(',',$data['attr']);
            }
            if(isset($data['id']) && !empty($data['id'])){
                $this->allowField(true)
                    ->save($data,['id'=>$data['id']]);
            }else{
                $data['add_time']=date('Y-m-d H:i:s',time());
                $this->allowField(true)
                    ->save($data);
            }
            return [
                'code'=>200,
                'msg'=>'保存成功',
            ];
        }catch (Exception $e){
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }
    //删除
    public function del($id){
        try{
            $this->destroy($id);
            return [
                'code'=>200,
                'msg'=>'删除成功'
            ];
        }catch(Exception $e){
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }
}
