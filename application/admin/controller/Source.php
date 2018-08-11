<?php
namespace app\admin\controller;
use think\Request;
use think\Exception;
use app\admin\model\Source as model;
/**
 * @title 房源列表
 * @description ...
 */

class Source extends Paren{
    /**
     * @title 房源列表
     * @description
     * @author 
     * @url /admin/source/lst
     * @method GET
     * @return data:返回信息
     */
    public function lst(Request $request,model $model){
        //        self::adminAutho('test_add'); //权限验证，先注释
        $param=$request->param();
        $row=$param['limit']??10;
        $where=[
            'display'=>$param['display']
        ];
        $pagelist=$model->where($where)->paginate($row);
        $total=$pagelist->total();
        $list=$pagelist->all();
        foreach ($list as $key=>$val){
            $list[$key]['cat_name']=$val['cat_name'];
            $list[$key]['region_name']=$val['region_name'];
            /*获取上传的用户*/

        }
        $data=[
            'total'=>$total,
            'list'=>$list
        ];
        $this->result($data,200,'','json');
    }

    /**
     * @title 获取添加/编辑页面需要的数据
     * @description
     * @author 
     * @url /admin/source/addpage
     * @method GET
     * @return data:返回信息
     */
    public function desc(Request $request,model $model){
        $id=$request->get('id',null);
        $result=$model->addpage($id);

        $this->result($result['data'],$result['code'],'','json');
    }

	/**
     * @title 添加/编辑房源
     * @description
     * @author 
     * @url /admin/source/add
     * @method POST
     * @return data:返回信息
     */
   	public function add(Request $request,model $model){
   	//  self::adminAutho('test_add'); //权限验证，先注释
        $param=$request->param();
        $validate = new \app\admin\validate\Source;
        if (!$validate->check($param)) {
            $this->result('',0,$validate->getError(),'json');
        }
//        $param['thumb']=getImgUrl($param['thumb']);
        $result=$model->setSave($param);
        $this->result($result['data'],$result['code'],$result['msg'],'json');
   	}

   	/**
     * @title 删除房源
     * @description
     * @author 
     * @url /admin/source/del
     * @method GET
     * @return data:返回信息
     */
   	public function del(Request $request,model $model){
   		//        self::adminAutho('test_add'); //权限验证，先注释
        $result=$model->del($_POST['id']);
        $this->result('',$result['code'],$result['msg'],'json');
   	}
    /**
     * @title 房源配套信息
     * @description
     * @return data:返回信息
     */
    public function expand(Request $request,model $model){
        if($request->isPost()){
            try{
                $matching=$request->post('matching',null);
                $matching=implode(',',$matching);
                $id=$request->post('id',null);
                $model->where('id',$id)->update([
                    'matching'=>$matching
                ]);
                $this->result('',200,'修改成功','json');
            }catch (Exception $e){
                $this->result('',0,$e->getMessage(),'json');
            }
        }else{
            $id=$request->get('id',null);
            $data=$model->where('id',$id)->field('id,matching')->find();
            $this->result($data,200,'','json');
        }
    }
}