<?php

namespace LeoHt\Erlenmeyer;

use LeoHt\Erlenmeyer\Feature\Feature;
use LeoHt\Erlenmeyer\Strategy\Strategy;
use LeoHt\Erlenmeyer\Strategy\DefaultProvider;
use LeoHt\Erlenmeyer\Strategy\ExpressionLanguage\Provider;
use LeoHt\Erlenmeyer\Loader\YamlLoader;
use LeoHt\Erlenmeyer\Loader\XmlLoader;
use LeoHt\Erlenmeyer\Memory\MemoryInterface;
use LeoHt\Erlenmeyer\Memory\Null as NullAdapter;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Erlenmeyer
{
    private static $instance;
    
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Decider
     */
    protected $decider;

    /**
     * @var MemoryInterface
     */
    protected $memoryAdapter;

    public static function buildFromConfig($file)
    {
        $registry = new Registry();
        $registry->registerProvider(new DefaultProvider());

        $language = new ExpressionLanguage(null, [
            new Provider()
        ]);

        $loader = new YamlLoader($file);
        $loader->load($registry);

        $decider = new Decider($language);
        $memory = new NullAdapter();

        return static::getInstance($registry, $decider, $memory);
    }

    public static function build($useDefaultProvider = true)
    {
        $registry = new Registry();
        
        if ($useDefaultProvider) {
            $registry->registerProvider(new DefaultProvider());
        }

        $language = new ExpressionLanguage(null, [
            new Provider()
        ]);

        $decider = new Decider($language);
        $memory = new NullAdapter();

        return static::getInstance($registry, $decider, $memory);
    }
    
    public static function getInstance(Registry $registry, Decider $decider, MemoryInterface $memory)
    {
        if (null === static::$instance) {
            static::$instance = new static($registry, $decider, $memory);
        }
        
        return static::$instance;
    }
    
    private function __construct(Registry $registry, Decider $decider, MemoryInterface $memory)
    {
        $this->registry = $registry;
        $this->decider = $decider;
        $this->memoryAdapter = $memory;
    }
    
    private function __clone()
    {
    }
    
    public function feature($name, $strategyName, array $variants = array())
    {
        $feature = Feature::create($name, $variants);

        $this->registry->register($feature, $strategyName);
        
        return $feature;
    }

    public function setUserKey($userKey)
    {
        $this->decider->set('_user', $userKey);
    }

    public function getUserKey()
    {
        return $this->decider->get('_user') ?: null;
    }

    public function set($key, $value)
    {
        $this->decider->set($key, $value);
    }

    public function setStrategyOption($name, $key, $value)
    {
        $strategy = $this->registry->getStrategy($name);

        $strategy->setOption($key, $value);

        return $this;
    }
    
    public function strategy($name, $voter)
    {
        $strategy = Strategy::create($name, $voter);
        $this->registry->registerStrategy($strategy);
        
        return $strategy;
    }
    
    public function vary($featureName, array $context = array())
    {
        $strategy = $this->registry->resolveStrategy($featureName);
        $feature = $this->registry->getFeature($featureName);

        if (!$strategy) {
            throw new \RuntimeException(sprintf("No strategy was found for feature '%s'.", $featureName));
        }

        if ($userKey = $this->getUserKey() && $result = $this->memoryAdapter->get($feature, $userKey)) {
            return $result;
        } else {
            $userKey = $this->getUserKey();
            $result = $this->decider->decide($strategy, $feature, $context);

            if ($userKey) {
                $this->memoryAdapter->save(
                    $feature,
                    $userKey,
                    $result
                );
            }
        }

        return $result;
    }

    public function isVariant($featureName, $variant, array $context = array())
    {
        return $variant == $this->vary($featureName, $context);
    }

    /**
     * Sets the value of instance.
     *
     * @param mixed $instance the instance
     *
     * @return self
     */
    private function _setInstance($instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Gets the value of registry.
     *
     * @return Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Sets the value of registry.
     *
     * @param Registry $registry the registry
     *
     * @return self
     */
    protected function setRegistry(Registry $registry)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * Gets the value of decider.
     *
     * @return Decider
     */
    public function getDecider()
    {
        return $this->decider;
    }

    /**
     * Sets the value of decider.
     *
     * @param Decider $decider the decider
     *
     * @return self
     */
    protected function setDecider(Decider $decider)
    {
        $this->decider = $decider;

        return $this;
    }

    /**
     * Gets the value of memoryAdapter.
     *
     * @return MemoryInterface
     */
    public function getMemoryAdapter()
    {
        return $this->memoryAdapter;
    }

    /**
     * Sets the value of memoryAdapter.
     *
     * @param MemoryInterface $memoryAdapter the memory adapter
     *
     * @return self
     */
    protected function setMemoryAdapter(MemoryInterface $memoryAdapter)
    {
        $this->memoryAdapter = $memoryAdapter;

        return $this;
    }
}
