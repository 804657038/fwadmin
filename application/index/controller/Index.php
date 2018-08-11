<?php
namespace app\index\controller;

use think\facade\Session;
use think\Controller;
/**
 * @title 测试
 * @description 接口说明
 */
class Index extends Controller
{
    /**
     * @title 测试
     * @description 接口说明
     * @author 开发者姓名
     * @url /index/index/index
     *
     * @param name: type:int require:1 default:1 other: desc:
     *
     * @return id:分类ID
     * @return name:分类名称
     * @return attrs:分类属性@
     * @attrs name:属性名称 id:属性ID attr:属性的属性
     */
   public function index(){
        $list=db('cate')->where('display',1)->order('listorder asc')->select();
        foreach ($list as $key => $value) {
            $attrs=db('cate_attr')->where('id','in',$value['attr_id'])->select();
            $list[$key]['attrs']=$attrs;    
        }
        $this->result($list,200,'调用成功!','json');
   }

}
