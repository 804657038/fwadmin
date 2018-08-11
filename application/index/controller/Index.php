<?php
namespace app\index\controller;

use think\facade\Session;
use think\Controller;
/**
 * @title 测试demo
 * @description 接口说明
 */
class Index extends Controller
{
    /**
     * @title 测试demo接口
     * @description 接口说明
     * @author 开发者姓名
     * @url /index/index/index
     *
     * @param name:id type:int require:1 default:1 other: desc:唯一ID
     *
     * @return name:名称
     * @return mobile:手机号
     * @return list_messages:消息列表@
     * @list_messages message_id:消息ID content:消息内容
     */
   public function index(){
        $this->result([
            "name"=>'测试',
            "mobile"=>'手机号码',
            "list_messages"=>[
                "message_id"=>'ID',
                "content"=>"留言内容"
            ]
        ],200,'获取成功','json');
   }

}
