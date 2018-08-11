<?php
namespace app\admin\controller;
use think\Exception;
use think\Request;
use app\admin\model\Matching as model;
class Matching extends Paren{
    /**
     * @title 房产资源列表
     */
    public function lst(Request $request,model $matching){
        $limit=$request->get('limit',10);
        $pagelist=$matching->where(true)->paginate($limit);
        $this->result($pagelist,200,'获取成功','json');
    }
    /**
     * @title 编辑配套
     */
    public function add(Request $request,model $matching){
        $param=$request->param();
        $res=$matching->add($param);
        $this->result('',$res['code'],$res['msg'],'json');
    }
    /**
     * @title 通过id配套信息详情
     */
    public function desc(Request $request,model $matching){
        $id=$request->get('id',null);
        if(!$id) $this->result('',0,'参数错误','json');
        $data=$matching::get($id);
        $this->result($data,200,'','json');
    }
    /**
     * @title 配套删除
     */
    public function del(Request $request,model $matching){
        $id=$request->post('id',null);
        try{
            $data=$matching::get($id);
            (new Upload)->delefile2($data['thumb']);
            $matching::destroy($id);
            $this->result('',200,'删除成功','json');
        }catch (Exception $e){
            $this->result('',0,$e->getMessage(),'json');
        }
    }
    /**
     * @title 获取全部
     */
    public function lstAll(model $matching){
        $list=$matching->where(true)->select();
        $this->result($list,200,'','json');
    }
}