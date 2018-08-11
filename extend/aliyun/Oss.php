<?php
namespace aliyun;
use OSS\OssClient;
use OSS\Core\OssException;
use think\facade\config;
require_once __DIR__ . '/../../vendor/aliyuncs/oss-sdk-php/autoload.php';
class Oss{
    protected $oss;
    protected $bucket;
    public function __construct()
    {
        $config=config::get('aliyun_oss');
        $this->oss=new OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
        $this->bucket=$config['Bucket'];
    }
    public function uploadFile($obj,$Path){
        try{

            $res=$this->oss->uploadFile($this->bucket,$obj , $Path);
            return [
                'code'=>1,
                'data'=>$res
            ];
        } catch(OssException $e) {
            //如果出错这里返回报错信息
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }
    public function deleFile($obj){
        try{

            $res=$this->oss->deleteObject($this->bucket,$obj);
            return [
                'code'=>1,
                'msg'=>'删除成功'
            ];
        } catch(OssException $e) {
            //如果出错这里返回报错信息
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }

    /*多文件删除*/
    public function deleteFiles($obj){
        try{

            $res=$this->oss->deleteObject($this->bucket,$obj);
            return [
                'code'=>1,
                'msg'=>'删除成功'
            ];
        } catch(OssException $e) {
            //如果出错这里返回报错信息
            return [
                'code'=>0,
                'msg'=>$e->getMessage()
            ];
        }
    }
    /*base64转文件*/
    public function base642file($base64,$path="./upload/temp"){
        $data=explode(',',$base64);
        //获取base64内容，并解码
        $fileData=base64_decode($data[1]);
        //获取文件后缀
        $prefixData=explode('/',$data[0]);
        $prefix=$prefixData[1];
        $filename=md5(date('YmdHis').rand(100,10000)).'.'.$prefix;
        $res=file_put_contents($path.'/'.$filename,$fileData);
        if($res){
            return $path.'/'.$filename;
        }else{
            return false;
        }

    }
}