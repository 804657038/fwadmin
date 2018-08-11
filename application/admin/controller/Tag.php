<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Tag as model;
use app\admin\model\TagAttr;
/**
 * @title 房源属性分类
 * @description ...
 */
class Tag extends Paren
{   
    /**
     * @title 房源属性分类列表
     * @description
     * @author 
     * @url /admin/tag/lst
     * @method GET
     * @return data:返回信息
     */
    public function lst(Request $request,model $model,TagAttr $attr){

    //  self::adminAutho('test_add'); //权限验证，先注释
        $param=$request->param();
        $row=$param['limit']??10;
        $where=[];
        $pagelist=$model->where($where)->paginate($row);
        $data=[];
        $data['total']=$pagelist->total();
        $list=$pagelist->all();
        foreach ($list as $key=>$value){
            //获取分类下的可选属性
            $list[$key]['attr']=$attr->where('tag_id',$value['id'])->select();
        }
        $data['data']=$list;
        $this->result($data,200,'','json');
    }
    /**
     * @title 房源属性分类列表,不分页
     * @description
     * @author
     * @url /admin/tag/lst2
     * @method GET
     * @return data:返回信息
     */
    public function lst2(model $model,TagAttr $attr){
        $list=$model->where(true)->select();
        foreach ($list as $key=>$value){
            //获取分类下的可选属性
            $list[$key]['attr']=$attr->where('tag_id',$value['id'])->select();
        }
        $this->result($list,200,'','json');
    }
    /**
     * @title 添加/编辑房源属性分类
     * @description
     * @author 
     * @url /admin/tag/add
     * @method POST
     * @param name:name type:string require:1 default: other: desc:分类名称
     * name:display type:int require: default:1 other: desc:分类显示状态(1显示 0隐藏)
     * name:add_time type:datetime require: default: other: desc:添加时间
     * @return data:返回信息
     */
    public function add(Request $request,model $model){

    //  self::adminAutho('test_add'); //权限验证，先注释
        $param=$request->param();
        $unique['require']='require';
        $unique['unique']='tag,name';
        if(isset($param['id'])){
            $unique['unique']='tag,name,'.$param['id'];
        }
        $validate=$this->validate($param,[
            'name|分类名称'=>$unique,
        ]);
        if(true !== $validate){
            $this->result('',0,$validate,'json');
        }
        $result=$model->setSave($param);
        $this->result('',$result['code'],$result['msg'],'json');
    }


    /**
     * @title 删除房源标签分类
     * @description
     * @author 
     * @url /admin/tag/del
     * @method POST
     * @return data:返回信息
     */
    public function dele(Request $request,model $model){

    //  self::adminAutho('test_add'); //权限验证，先注释
        $id=$request->post('id',null);
        $result=$model->dele($id);

        $this->result('',$result['code'],$result['msg'],'json');
    }

    /**
     * @title 获取房源标签详情数据
     * @description
     * @author
     * @url /admin/tag/desc
     * @method get
     * @param name:id type:string require:1 default: other: desc:标签ID
     * @return data:返回信息
     */
    public function desc(Request $request,model $model,TagAttr $attr){
        $id=$request->get('id',null);
        $data=$model::get($id);
        $data['attr']=$attr->where('tag_id',$id)->select();
        $this->result($data,200,'','json');
    }
}
