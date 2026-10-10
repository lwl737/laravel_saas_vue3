<?php

declare(strict_types=1);

namespace App\Common\Models\Elasticsearch\Indices;

use App\Helpers\Func;
use App\Common\Models\Elasticsearch\Control;
use App\Common\Models\Redis\RedisMutexLock;
use App\Common\Models\Redis\Control as Redis;

abstract class BaseIndices
{
    public  $index;
    public  array  $settings = [
        // 一旦索引被创建，分片数量（index.number_of_shards）就是一个不可变的设置，
        // 你不能在索引创建后更改它。
        // 这个限制是为了防止数据丢失和维护索引的完整性。
        'number_of_shards' => 3,
        'number_of_replicas' => 3
    ];
    public  array  $mapping = [
        '_source' => [
            'enabled' => true
        ],
        'properties' => [
            'first_name' => [
                'type' => 'keyword'
            ],
            'age' => [
                'type' => 'integer'
            ],
            'age1' => [
                'type' => 'integer'
            ],
            'age4' => [
                'type' => 'integer'
            ],
            'age5' => [
                'type' => 'integer'
            ],
            // 'age2' => [
            //     'type' => 'integer'
            // ]
        ]
    ];
    public function __construct()
    {

        $class = explode('\\', get_class($this));  //获取子级类名

        $this->index =  $this->index ? $this->index : Func::toUnderScore(array_pop($class));  //转下划线 表名
    }

    public function create(array $body)
    {
        $this->initIndex();

        return Control::index(
            $this->index,
            $body
        );
    }
    public function get(array $body = [] , string|int|null $_id = null)
    {
        $this->initIndex();

        return Control::get(
            $this->index,
            $body,
            $_id
        );
    }

    public function delete()
    {
        // Control::indices()->delete();
        // return Control::indices()->create([
        //     'index' => $this->index,
        //     'body'  => $body
        // ]);
    }

    private function initIndex()
    {
        $redisConfig = ["DB" => 1, "key" => "ES_KEY_IDX", 'id' => $this->index];
        $idx = Redis::redishGetAll($redisConfig);
        $settings_serialize = serialize($this->settings);
        $mapping_serialize = serialize($this->mapping);
        if (empty($idx) || $idx["settings"] !== $settings_serialize || $idx["mapping"] !== $mapping_serialize) {
            //加悲观锁
            $redisMutexLock = new RedisMutexLock(["DB" => 1, "key" => "ES_LOCAL", 'id' => $this->index], 1);

            $redisMutexLock->tryGetDistributedLock();
            //再判断一次
            $idx = Redis::redishGetAll($redisConfig);
            if (empty($idx) || $idx["settings"] !== $settings_serialize || $idx["mapping"] !== $mapping_serialize) {
                if (Control::indices()->exists(["index" => $this->index])->getStatusCode() === 404) {
                    //不存在就创建索引
                    Control::indices()->create([
                        'index' => $this->index,
                        'body'  => [
                            'settings' =>  $this->settings,
                            'mapping' => $this->mapping,
                        ]
                    ]);
                    //记录settings配置 和  mapping配置
                    Redis::hMset(
                        [
                            'settings' =>  $settings_serialize,
                            'mapping' =>  $mapping_serialize,
                        ],
                        $redisConfig
                    );
                } else {
                    //已经存在就更新索引
                    $settings = $this->settings;
                    // 一旦索引被创建，分片数量（index.number_of_shards）就是一个不可变的设置，你不能在索引创建后更改它。这个限制是为了防止数据丢失和维护索引的完整性。
                    if (isset($settings["number_of_shards"]))  unset($settings["number_of_shards"]);

                    //settings配置
                    if (empty($idx) || $idx["settings"] !== $settings_serialize) {
                        Control::indices()->putSettings([
                            'index' => $this->index,
                            'body'  => $settings
                        ]);
                        Redis::hMset(['settings' =>  $settings_serialize], $redisConfig);
                    }

                    //mapping配置
                    if (empty($idx) || $idx["mapping"] !== $mapping_serialize) {
                        Control::indices()->putMapping([
                            'index' => $this->index,
                            'body'  => $this->mapping
                        ]);
                        Redis::hMset(['mapping' =>  $mapping_serialize], $redisConfig);
                    }
                };

                //创建完索引释放锁
                $redisMutexLock->releaseDistributedLock();
            }
        }
    }

    public static function static()
    {
        return new static;
    }
}
