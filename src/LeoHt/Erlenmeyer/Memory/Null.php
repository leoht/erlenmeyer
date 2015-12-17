<?php

namespace LeoHt\Erlenmeyer\Memory;

use LeoHt\Erlenmeyer\Feature\Feature;

/**
 * Null memory adapter. Does not save or retrieve anything.
 */
class Null implements MemoryInterface
{
	public function save(Feature $feature, $userKey, $variant)
    {
    	// var_dump('DEBUG: saving feature '.$feature->getName().' with variant '.(int)$variant.' for user '.$userKey);
    }
    
    public function get(Feature $feature, $userKey)
    {
    	return null;
    }
    
    public function clear(Feature $feature, $userKey)
    {
    }
}