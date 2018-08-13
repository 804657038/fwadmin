<?php
namespace app\index\controller;

use think\facade\Session;
use think\Controller;
/**
 * @title 房网后台接口
 * @description 接口说明
 */
class Api extends Controller
{

    /**
     * @title 导航条
     * @description 接口说明
     * @author 开发者姓名
     * @url /index/Api/index
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

    /**
     * @title 标签
     * @description 接口说明
     * @author 小黑
     * @url /index/Api/Tag
     *
     * @param name: type:int require:1 default:1 other: desc:
     *
     * @return id:标签ID
     * @return name:标签名称
     * @return tag_id:标签属性@
     * @tag_id tag_id:标签ID id:属性ID  name:属性名称 
     */
    public function Tag(){
        $List=db('tag')->where('display',1)->order('listorder asc')->select();
        foreach ($List as $key => $value) {
            $tag_id=db('tag_attr')->where('tag_id','in',$value['id'])->select();
            $List[$key]['tag_id']=$tag_id;    
        }
        $this->result($List,200,'调用成功!','JSON');
   }
   

    /**
     * @title 房源
     * @description 接口说明
     * @author 小黑
     * @url /index/Api/Source
     *
     * @param name: type:int require:1 default:1 other: desc:
     *
     * @return id:房源ID
     * @return title:房源名称
     * @return cat_id:房源ID
     * @return father:父级数据@
     * @father id:父级ID  name:父级名称
     * @return matching:配套数据@
     * @matching id:配套ID  name:配套名称 
     */
    public function Source(){
        $source = db('source')->where('display','eq',0 and 'recommend',1)->select();
            //遍历数据获取父级的数据
            foreach ($source as $key => $value) {
                $type = db('cate')->where('id','in',$value['cat_id'])->select();
                $source[$key]['father'] = $type;
            }
            // 配套数据
            foreach ($source as $k => $v) {
                $dz = db('matching')->where('id','in',$v['matching'])->select();
                $source[$k]['matching'] = $dz;
            }
        $this->result($source,200,'调用成功!','JSON');
   }
   
}
