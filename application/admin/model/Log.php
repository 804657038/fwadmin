<?php

namespace app\admin\model;

use think\Model;

class Log extends Model
{
    protected $field=[
        'user_id','code','method','module','controller','action','msg','data','add_time'
    ];
    /**
     * @title 日志写入
     * @param name:data type:string require:1 default: other: desc:日志数据包
     * */
    public function setlog($data){
        $this->allowField(true)->save($data);
    }
}
