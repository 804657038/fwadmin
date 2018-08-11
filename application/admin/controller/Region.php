<?php
namespace app\admin\controller;
/**
 * @title 后台地区管理控制器
 * @description ...
 */
use think\Request;

use app\admin\model\Region as model;
class Region extends Paren {
    /**
     * @title 获取地区列表
     * @description 返回树型图数据
     * @author
     * @url /admin/admin/lst
     * @method GET
     * @return data:树型图数据
     */
    public function getlist(model $region){
        $data=file_get_contents('./json/region.json');
        if(empty($data)){

            $list=$region->where('parent_id','>',0)->select();

            $data=treeregion($list,1);

            file_put_contents('./json/region.json', json_encode($data));
        }
        $this->result($data,200,'','json');
    }
    /**
     * @title 通过地区ID获取地区名称
     */
    public function getRegionName(Request $request,model $region){
        $ids=$request->get('ids',null);
        $names=$region->where('id','IN',$ids)->column('region_name');
        $this->result($names,200,'','json');
    }
}