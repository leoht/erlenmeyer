<?php

namespace LeoHt\Erlenmeyer\Memory;

use LeoHt\Erlenmeyer\Feature\Feature;
use \Redis;

class Redis implements MemoryInterface
{
    /**
     * @var Redis
     */
    private $redis;
    
    public function ($host = '127.0.0.1', $port = 6379, $prefix = 'features_memory_')
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        $this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE); 
        $this->redis->setOption(Redis::OPT_PREFIX, $prefix);
    }

    public function save(Feature $feature, $userKey, $variant)
    {
        $this->redis->set($this->getKeyName($feature, $userKey), $variant);
    }
    
    public function get(Feature $feature, $userKey)
    {
        $key = $this->getKeyName($feature, $userKey);
        
        return $this->redis->get($key);
    }
    
    public function clear(Feature $feature, $userKey)
    {
        $this->redis->delete($this->getKeyName($feature, $userKey));
    }
    
    private function getKeyName($feature, $userKey)
    {
        return $feature->getName().'.user-'.$userKey;
    }
}
