<?php

namespace app\admin\model;

use think\Exception;
use think\Model;

class Tag extends Model
{

    //添加 编辑
    public function setSave($data){
        try{
            if(isset($data['id']) && !empty($data['id'])){
                $this->allowField(true)
                    ->save($data,['id'=>$data['id']]);
                /*是否有添加属性*/
                $tagAttr=new TagAttr;
                if(isset($data['delArray'])){  //先去删除点击了删除按钮的属性
                    foreach ($data['delArray'] as $key=>$value){
                        if($value['id']){ //先判断是否有id，如果有再去删除
                            $tagAttr::destroy($value['id']);
                        }
                    }
                }
                if(isset($data['tagArray'])){
                    foreach ($data['tagArray'] as $key=>$value){
                        if(!$value['id']){ //如果id为空是添加
                            $tagAttr->insert([
                                'tag_id'=>$data['id'],
                                'name'=>$value['name'],
                                'add_time'=>date('Y-m-d H:i:s')
                            ]);
                        }else{
                            $tagAttr->where('id',$value['id'])->update([ //否则是修改
                                'tag_id'=>$data['id'],
                                'name'=>$value['name'],
                                'add_time'=>date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }

            }else{
                $data['add_time']=date('Y-m-d H:i:s',time());
                $this->allowField(true)
                    ->save($data);
            }
            return [
                'code'=>200,
                'msg'=>'保存成功'
            ];
        }catch (Exception $e){
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }
    //删除
    public function dele($id){
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
