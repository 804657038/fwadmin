<?php

namespace app\admin\model;

use think\Model;

class File extends Model
{
    /*
     * 写入图片管理数据表
     * */
    public function add($data){
        $this->insert([
            'user_id'=>$data['user_id'],
            'has_admin'=>$data['has_admin'],
            'oss_obj'=>$data['oss_obj'],
            'typename'=>$data['typename'],
            'filetype'=>$data['filetype'],
            'add_time'=>date('Y-m-d H:i:s'),
        ]);

    }
}
