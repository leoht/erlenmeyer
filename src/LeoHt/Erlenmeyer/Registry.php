<?php

namespace LeoHt\Erlenmeyer;

use LeoHt\Erlenmeyer\Feature\Feature;
use LeoHt\Erlenmeyer\Strategy\Strategy;
use LeoHt\Erlenmeyer\Strategy\StrategyProviderInterface;

class Registry
{
    /**
     * @var array
     */
    private $features;
    
    /**
     * @var array
     */
    private $strategies;

    /**
     * @var array
     */
    private $featureStrategyMapping;

    public function registerProvider(StrategyProviderInterface $provider)
    {
        $strategies = $provider->getStrategies();

        foreach ($strategies as $strategy) {
            $this->registerStrategy($strategy);
        }
    }
    
    public function register(Feature $feature, $strategyName)
    {
        if (!$this->isStrategyRegistered($strategyName)) {
            throw new \InvalidArgumentException(sprintf("Strategy '%s' is not registered.", $strategyName));
        }

        $this->features[] = $feature;
        $this->featureStrategyMapping[$feature->getName()] = $strategyName;
    }

    public function registerStrategy(Strategy $strategy)
    {
        $this->strategies[] = $strategy;
    }
    
    public function resolveStrategy($featureName)
    {
        $name = $this->featureStrategyMapping[$featureName];

        foreach ($this->strategies as $strategy) {
            if ($name === $strategy->getName()) {
                return $strategy;
            }
        }

        return null;
    }
    
    public function isRegistered($name)
    {
        foreach ($this->features as $feature) {
            if ($feature->getName() === $name) {
                return true;
            }
        }
        
        return false;
    }

    public function isStrategyRegistered($name)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getName() === $name) {
                return true;
            }
        }
        
        return false;
    }

    public function getFeature($name)
    {
        foreach ($this->features as $feature) {
            if ($feature->getName() === $name) {
                return $feature;
            }
        }
        
        return null;
    }

    public function getStrategy($name)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getName() === $name) {
                return $strategy;
            }
        }
        
        return null;
    }

    /**
     * Get the value of Features 
     * 
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /** 
     * Set the value of Features 
     * 
     * @param array features
     * 
     * @return self
     */
    public function setFeatures(array $features)
    {
        $this->features = $features;
 
        return $this;
    }
 
    /**
     * Get the value of Strategies 
     * 
     * @return array
     */
    public function getStrategies()
    {
        return $this->strategies;
    }
 
    /** 
     * Set the value of Strategies 
     * 
     * @param array strategies
     * 
     * @return self
     */
    public function setStrategies(array $strategies)
    {
        $this->strategies = $strategies;
 
        return $this;
    }
 
    /**
     * Gets the value of featureStrategyMapping.
     *
     * @return array
     */
    public function getFeatureStrategyMapping()
    {
        return $this->featureStrategyMapping;
    }
}
