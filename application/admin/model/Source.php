<?php

namespace app\admin\model;

use think\Exception;
use think\Model;

class Source extends Model
{
    //获取添加/编辑页面需要的数据
    public function addpage($id){
        $data=$this::get($id);
        $data['regions']=[
            $data['province'],$data['city'],$data['region']
        ];
        $data['taglist']=(new SourceTag)->where('source_id',$id)->column('tag_id');
        return [
            'code'=>200,
            'data'=>$data,
        ];
    }
    //添加 编辑
    public function setSave($data){
        try{
            $data['province']=$data['regions'][0];
            $data['city']=$data['regions'][1];
            $data['region']=$data['regions'][2];
            if(isset($data['id']) && !empty($data['id'])){
                $data['update_time']=date('Y-m-d H:i:s',time());
                $data['action_user_id']=USER['id'];
                $this->allowField(true)
                    ->save($data,['id'=>$data['id']]);
            }else{
                $data['add_time']=date('Y-m-d H:i:s',time());
                $data['display']=0;
                $data['user_id']=USER['id'];
                $data['hasadmin']=1;
                $this->allowField(true)
                    ->save($data);
            }
            if(isset($data['taglist']) && !empty($data['taglist'])){
                /*先清空数据，在写入*/
                (new SourceTag)->where('source_id',$this->id)->delete();
                $tarArr=[];
                foreach ($data['taglist'] as $key=>$value){
                    $tarArr[]=[
                        'source_id'=>$this->id,
                        'tag_id'=>$value
                    ];
                }
                (new SourceTag)->saveAll($tarArr);
            }
            return [
                'code'=>200,
                'msg'=>'发布成功',
                'data'=>$this->id
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
    //获取配套
    public function getMatchingAttr($val){
        if($val){
            $attr=explode(',',$val);
            $data=[];
            foreach ($attr as $key=>$val){
                $data[]=(int)$val;
            }
            return $data;
        }else{
            return [];
        }

    }
    //获取分类名称
    public function getCatNameAttr($val){
        $catname=db('cate')->where('id',$this->cat_id)->value('name');
        return $catname;
    }
    //获取地区名称
    public function getRegionNameAttr($val){
        $province=db('region')->where('id',$this->province)->value('region_name');
        $city=db('region')->where('id',$this->city)->value('region_name');
        $region=db('region')->where('id',$this->region)->value('region_name');
        return $province.'-'.$city.'-'.$region;
    }
    //获取用户
    public function getUserIdAttr($val){
        //是否是后台管理员
        $hasadmin=$this->hasadmin;

        if($hasadmin==1){ //是后台管理员
            $admin=(new Admin)->where('id',$val)->field('group_id,username')->find();
            return [
                'id'=>$val,
                'hasadmin'=>1,
                'username'=>$admin['group_id']['name'].':'.$admin['username']
            ];
        }
    }
}
