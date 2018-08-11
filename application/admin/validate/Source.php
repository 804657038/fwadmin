<?php
namespace app\admin\validate;
use think\Validate;
class Source extends Validate{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'cat_id|资源分类'=>'require|number',
        'title|资源标题'=>'require|length:4,20',
        'regions|所属地区'=>'require|array',
        'addres|详细地址'=>'require',
        'thumb|封面图片'=>'require',
        'saletype|销售类型'=>'require|number',
        'price|价格'=>'require|number',
        'desc|描述'=>'require',
    ];
    protected $message  =   [
        'title.length' => '资源标题在4-20个字之间',
    ];

}