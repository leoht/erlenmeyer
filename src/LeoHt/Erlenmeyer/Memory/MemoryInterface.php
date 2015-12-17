<?php

namespace LeoHt\Erlenmeyer\Memory;

use LeoHt\Erlenmeyer\Feature\Feature;

interface MemoryInterface {
    
    /**
     * Save the decided variant for a feature.
     * @param Feature $feature
     * @param mixed $userKey a unique key to identify the current user
     * @param string $variant
     */
    public function save(Feature $feature, $userKey, $variant);
    
    /**
     * Get the saved variant for a feature.
     * @param Feature $feature
     * @param mixed $userKey a unique key to identify the current user
     * @return string|null Variant name or null
     */
    public function get(Feature $feature, $userKey);
    
    /**
     * Clear memory.
     */
    public function clear(Feature $feature, $userKey);
}
