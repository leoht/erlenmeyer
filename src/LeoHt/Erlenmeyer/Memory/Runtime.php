<?php

namespace LeoHt\Erlenmeyer\Memory;

use LeoHt\Erlenmeyer\Feature\Feature;

class Runtime implements MemoryInterface
{
    public function save(Feature $feature, $userKey, $variant)
    {
        apc_store($this->getKeyName(), $variant);
    }
    
    public function get(Feature $feature, $userKey)
    {
        $key = $this->getKeyName($feature, $userKey);
        
        return apc_fetch($key);
    }
    
    public function clear(Feature $feature, $userKey)
    {
        apc_delete($this->getKeyName($feature, $userKey));
    }
    
    private function getKeyName($feature, $userKey)
    {
        return $feature->getName().'.user-'.$userKey;
    }
}
