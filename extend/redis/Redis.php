<?php
namespace redis;
use think\cache\driver\Redis as model;
class Redis extends model{
    protected $config=[
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
    ];
    public function __construct()
    {
        parent::__construct($this->config);
    }

}