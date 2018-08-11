<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Facade\{Env,Config};
// 应用公共文件
/*
 * 通用返回
 * @$status=状态码
 * @$msg=留言
 * @$url=跳转链接
 * @$param=其他参数，参数内需要带英文的:号，否则不执行
 * */
function result(int $status=1,string $msg='',string $url='',string ...$param)
{
    $data=[
        'status'=>$status,
        'msg'=>$msg,
        'url'=>$url
    ];
    if(!empty($param)){
        foreach ($param as $value){
            $v=explode(':',$value);
            if(count($v)>=2){
                $data[$v[0]]=$v[1];
            }
        }
    }

    return json($data);
}
/*
 * 获取模版变量
 * */
function __template(array $param=[]){
    $controller=request()->controller();
    $action=request()->action();
    $lang=lang('view');
    $template=[
        'title'=>$lang['title'][$controller][$action]
    ];
    return $template;
}
/*
 * 语言设置
 * @$model:语言模块
 * @$lang：语言索引
 * */
function lang(string $model,string $lang=''){
    $default_lang=Config::get('default_lang');
    switch ($default_lang){
        case "en":
            $files='lang_en';
            break;
        default:
            $files='lang_ch';
            break;
    }
    return Config::get($files.'.'.$model)[$lang]??Config::get($files.'.'.$model);
}

/*
 * 通用CURL
 * $param
 * @$url:请求链接
 * @$method:请求方式
 * @$data：请求方式
 * @$header:请求头，必须是数组
  * retrun:$result
 * */
function get_curl_contents($url, $method ='GET', $data = '',array $header=[]) {
    if ($method == 'POST') {
        //使用crul模拟
        $ch = curl_init();
        //禁用https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        //允许请求以文件流的形式返回
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json; charset=utf-8"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if(!empty($header)){
//            array("Content-Type: application/json; charset=utf-8")

        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch); //执行发送
        curl_close($ch);
    }else {
        if (ini_get('allow_fopen_url') == '1') {
            $result = file_get_contents($url);
        }else {
            //使用crul模拟
            $ch = curl_init();
            //允许请求以文件流的形式返回
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            //禁用https
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch); //执行发送
            curl_close($ch);
        }
    }
    return $result;
}
/*
 * 接口数据处理
 * */

function decode_result($data){
    $result=json_decode($data,true);
    if($result['code']=='1003'){
        abort(408, '请重新登录', []);
    }
    return $result;
}

/*
 * 参数处理
 * 把get参数拼接成url
 * */
function url_param(array $param):string {
    $arr=[];
    if(isset($param['page']))unset($param['page']);
    foreach ($param as $key=>$value){
        $arr[]=$key.'='.$value;
    }
    $str=implode('&',$arr);
    return $str;
}
/*
 * 权限判断
 * */
function roles(array $roles,...$param){
    $return=true;
    $arr=[];
    foreach ($roles as $val){
        $arr[]=$val['code'];
    }

    foreach ($param as $val){
        if(!in_array($val,$arr)){
            $return=false;
            break;
        }
    }
    return $return;
}

// 通用加密
function encrypt(string $txt){
    $key=config('env')['APP_KEY'];
    $rand=(int)((double)microtime() * 1000000);
    srand($rand);
    $encrypt_key = md5((string)(rand(0, 32000)));
    $ctr = 0;
    $tmp = '';
    for($i = 0;$i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
    }
    $str=str_replace('=','@',base64_encode(passport_key($tmp, $key)));
    return $str;
}
// 通用解密
function decrypt($txt){
    $key=config('env')['APP_KEY'];
    $str=str_replace('@','=',$txt);
    $txt = passport_key(base64_decode($str), $key);
    $tmp = '';
    for($i = 0;$i < strlen($txt); $i++) {
        $md5 = $txt[$i];
        $tmp .= $txt[++$i] ^ $md5;
    }
    return $tmp;
}
//加密解密解析函数
function passport_key($txt, $encrypt_key) {
    $encrypt_key = md5(md5($encrypt_key));
    $ctr = 0;
    $tmp = '';
    for($i = 0; $i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}

/*随机字符串*/
/*
* randChar()    生成随机字符串
* $len       生成长度
*/
function randChar($len) {
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz_";
    $max = strlen($strPol)-1;
    $strPol = str_split($strPol);
    $string = '';
    for ($i = 0; $i < $len; $i++) {
        $string .= $strPol[mt_rand(0, $max)];
    }
    return $string;
}
//生成短信验证随机码
function get_smscode_key() {
    return mt_rand(100000, 999999);
}

//短信发送
function smspost($mobile,$content)
{
    $url="http://dxjk.51lanz.com/LANZGateway/DirectSendSMSs.asp";
    $UserID="936352";
    $Account='snimay';
    $PassWord=sha1("jisuanji253546");
    $data=[
        "UserID"=> mb_convert_encoding($UserID, "GB2312", "UTF-8"),
        "Account"=> mb_convert_encoding($Account, "GB2312", "UTF-8"),
        "PassWord"=> mb_convert_encoding($PassWord, "GB2312", "UTF-8"),
        "Content"=> mb_convert_encoding($content, "GB2312", "UTF-8"),
        "Phones"=> mb_convert_encoding($mobile, "GB2312", "UTF-8"),
        "ReturnXJ"=>mb_convert_encoding('1', "GB2312", "UTF-8"),
    ];
    $par=http_build_query($data);
    $link=$url.'?'.$par;
    $res=file_get_contents($link);
//    $res=get_curl_contents($url,'POST',$data);
    return json_decode($res,true);

}
/*通过IP获取门店*/
function GetIpLookup($ip = ''){
    $res = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
    if(empty($res)){ return false; }
    $json = json_decode($res, true);
    return $json['data'];
}

function decrypt2($data) {
    $data = base64_decode($data);

    //读取公钥
    $key_private = file_get_contents('../extend/rea/rsa_1024_priv.pem');

    //进行解密
    if(!openssl_private_decrypt($data, $data, openssl_pkey_get_private($key_private))) {
        return false;
    }
    return $data;
}
/*递归无限级分类*/
function treeShape($list,$parent_id){
    $tree=[];
    foreach ($list as $key=>$value){
        if($value['parent_id']==$parent_id){
            $value['children']=treeShape($list,$value['id']);
            $tree[]=$value;
        }
    }
    return $tree;
}
/*格式化递归无限极分类1*/
function treeShape1($list,$parent_id){
    $tree=[];
    foreach ($list as $key=>$value){
        if($value['parent_id']==$parent_id){
            $val=[];
            $val['value']=$value['id'];
            $val['label']=$value['name'];
            $val['parent_id']=$value['parent_id'];
            $children=treeShape1($list,$value['id']);
            if($children){
                $val['children']=$children;
            }

            $tree[]=$val;
        }
    }
    return $tree;
}


/*递归格式化地区*/
function treeregion($list,$parent_id){
    $tree=[];
    foreach ($list as $key=>$value){
        if($value['parent_id']==$parent_id){
            $val=[];
            $val['value']=$value['id'];
            $val['label']=$value['region_name'];
            $val['parent_id']=$value['parent_id'];
            $children=treeregion($list,$value['id']);
            if($children){
                $val['children']=$children;
            }

            $tree[]=$val;
        }
    }
    return $tree;
}
/*格式化图片链接*/
function setImgUrl($url){
    $oss=Config::get('aliyun_oss');
    $fileurl=$oss['fileurl'];
    return $fileurl.$url;
}
/*反格式化图片链接*/
function getImgUrl($url){
    $oss=Config::get('aliyun_oss');
    return str_replace($oss['fileurl'],'',$url);
}




