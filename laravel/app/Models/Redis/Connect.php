<?php

declare(strict_types=1);
namespace App\Models\Redis;


class Connect
{
    public readonly object $redis;
    public function __construct()
    {
      $this->redis = new \Redis;
      $this->redis->connect(config('database.redis.default.host'),(int)config('database.redis.default.port'));//短链接，本地host，端口为6379，超过1秒放弃链接
      $this->redis->auth(config('database.redis.default.password'));//登录验证密码，返回【true | false】
    }
}
