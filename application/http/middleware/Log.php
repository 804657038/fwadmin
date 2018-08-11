<?php

namespace app\http\middleware;
use app\admin\model\Log as model;
class Log
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $json=json_decode($response->getContent(),true);
        $this->setlog($json);
        return $response;
    }
    private function setlog($json){
        $method=request()->method();
        if($method=='POST'){
            $param=request()->param();
            $data['user_id']=USER['id'];
            $data['code']=$json['code'];
            $data['data']=json_encode($param);
            $data['msg']=$json['msg'];
            $data['add_time']=date('Y-m-d H:i:s');
            $data['module']=request()->module();
            $data['controller']=request()->controller();
            $data['action']=request()->action();
            $data['method']=$method;
            (new model)->setlog($data);
        }


    }
}
