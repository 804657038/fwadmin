<?php
namespace app\admin\controller;
/**
 * @title 文件上传控制器
 * @description ...
 */
use think\Request;
use app\admin\model\File as fileconfig;
use aliyun\Oss;
class Upload extends Paren{
    /**
     * @title 图片上传
     * @description
     * @author
     * @url /admin/upload/img
     * @method GET
     * @return data:返回信息
     */
    public function img(Request $request,fileconfig $fileconfig,Oss $oss){
        $file = $request->file('file');
        $typename=$request->post('typename','temp');
        $path='./upload/temp';

        $info = $file->move($path);
        if($info){
            $filename=$info->getSaveName();
            $obj=$typename;
            $res=$oss->uploadFile($obj.'/'.$filename,$path.'/'.$filename);
            if($res['code']==1){
                unlink($path.'/'.$filename);
                $url=$res['data']['info']['url'];
                $fileconfig->add([
                    'user_id'=>USER['id'],
                    'has_admin'=>1,
                    'oss_obj'=>$obj.'/'.$filename,
                    'typename'=>$typename,
                    'filetype'=>'image',
                ]);
                $this->result($url,200,'上传成功','json');
            }else{
                unlink($path.'/'.$filename);
                $this->result('',0,'上传失败','json');
            }
        }else{
            $this->result('',0,'上传失败','json');
        }
    }

    /*
     * 请求的文件删除
     * */
    public function delefile(Request $request,fileconfig $fileconfig,Oss $oss){
        $url=$request->post('url');
        $url=getImgUrl($url);
        $fileconfig->where('oss_obj',$url)->delete();
        $oss->deleFile($url);
        $this->result('',200,'删除成功','json');
    }
    /*
     * 非请求删除
     * */
    public function delefile2($url){
        $url=getImgUrl($url);
        $fileconfig=new fileconfig();
        $oss=new Oss();
        $fileconfig->where('oss_obj',$url)->delete();
        $oss->deleFile($url);
    }
    /**多图上传**/
    public function imglist(Request $request,fileconfig $fileconfig,Oss $oss){
        $param=$request->param();
        $files=$param['files'];

    }

}