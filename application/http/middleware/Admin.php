<?php

namespace app\http\middleware;
use app\admin\model\Admin as model;
class Admin
{
    public function handle($request, \Closure $next)
    {
        try{
            $header=request()->header('Authorization');
            $data=explode(' ', $header);

            if(!$data){
                return json([
                    'code'=>50008,
                    'msg'=>'登录已过期，请重新登录'
                ]);
            }

            $data_1=decrypt(decrypt2(($data[1])));
            if(!$data_1){
                return json([
                    'code'=>50008,
                    'msg'=>'登录已过期，请重新登录'
                ]);
            }
            $userData=(new model)->where('username',$data_1)->field('id,auth')->find();
            $cacheId=md5($data_1.'_'.$userData['id']);
            $user=cache($cacheId);
            if(!$user){
                return json([
                    'code'=>50008,
                    'msg'=>'登录已过期，请重新登录'
                ]);
            }
            $user['auth']=$userData['auth'];
            define('USER',$user);
        }catch (\Exception $e){
            return json([
                'code'=>50008,
                'msg'=>'登录已过期，请重新登录'
            ]);
        }
        return $next($request);
    }
}
