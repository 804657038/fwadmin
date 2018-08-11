<?php
namespace app\admin\controller;

use think\Exception;
use think\Request;
use app\admin\model\Cate as model;
/**
 * @title 分类
 * @description ...
 */
class Cate extends Paren
{   
    /**
     * @title 房产分类列表
     * @description
     * @author 
     * @url /admin/cate/lst
     * @method GET
     * @return data:返回信息
     */
    public function lst(Request $request,model $model){

    //  self::adminAutho('test_add'); //权限验证，先注释
        $param=$request->param();
        $row=$param['limit']??10;
        $where=[];
        try{
            $pagelist=$model->where($where)->order('listorder asc')->paginate($row);
            $this->result($pagelist,200,'','json');
        }catch (Exception $e){
            $this->result('',0,'','json');
        }
    }
    /**
     * @title 通过id获取分类详情
     * @description
     * @author
     * @url /admin/cate/lst
     * @method get
     * @param id
     * @return data:返回信息
     */
    public function desc(Request $request,model $model){
        $id=$request->get('id',null);
        $data=$model::get($id);
        $this->result($data,200,'','json');
    }

    /**
     * @title 添加/编辑分类
     * @description
     * @author 
     * @url /admin/cate/add
     * @method POST
     * @param 单条分类数据包
     * @return data:返回信息
     */
    public function add(Request $request,model $model){

    //  self::adminAutho('test_add'); //权限验证，先注释
        $param=$request->param();
        $unique=['require'];
        $unique['unique']='cate,name';
        if(isset($param['id'])){
            $unique['unique']="cate,name,".$param['id'];
        }
        $validate=$this->validate($param,[
            'name|分类名称' => $unique,
        ]);
        if(true !== $validate){
            $this->result('',200,$validate,'json');
        }
        $result=$model->setSave($param);
        $this->result('',$result['code'],$result['msg'],'json');
    }


    /**
     * @title 删除分类
     * @description
     * @author 
     * @url /admin/cate/del
     * @method GET
     * @return data:返回状态
     */
    public function del(Request $request,model $model){

    //  self::adminAutho('test_add'); //权限验证，先注释
        $id=$request->post('id');
        $result=$model->del($id);
        $this->result('',$result['code'],$result['msg'],'json');
    }
    /**
     * @title 获取分类属性
     * @description
     * @author
     * @url /admin/cate/catarr
     * @method GET
     * @return data:返回状态
     */
    public function catarr(Request $request,model $model){
        $cat_id=$request->get('id',null);
        $attr_id=$model->where('id',$cat_id)->field('attr_id')->find();
        $data=$attr_id['attr_id']['attr'];

//        if($attr_id){
//            $attr=$attr_id['attr_id'];
//            foreach ($attr['id'] as $key=>$value){
//                $data[$key]['id']=(String)$value;
//                $data[$key]['name']=$attr['name'][$key];
//                $data[$key]['attr']=$attr['name'][$key];
//            }
//        }

        $this->result($data,200,'','json');
    }
}
