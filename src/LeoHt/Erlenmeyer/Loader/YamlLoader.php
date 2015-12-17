<?php

namespace LeoHt\Erlenmeyer\Loader;

use LeoHt\Erlenmeyer\Registry;
use LeoHt\Erlenmeyer\Feature\Feature;
use LeoHt\Erlenmeyer\Strategy\Strategy;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements LoaderInterface
{
	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string
	 */
	private $filePath;

	/**
	 * @var boolean
	 */
	private $cacheEnabled;

	public function __construct($filePath, $options = array())
	{
		$this->filePath = $filePath;
		$this->cacheEnabled = isset($options['enable_cache']) ? $options['enable_cache'] : false;
	}

	public function load(Registry $registry)
	{
		if ($this->cacheEnabled && extension_loaded('apc')) {
			if ($cached = \apc_fetch('_feature_config_cache')) {
				$this->data = $cached;
			} else {
				$this->data = Yaml::parse(file_get_contents($this->filePath));
				\apc_store('_feature_config_cache', $this->data);
			}
		} else {
			$this->data = Yaml::parse(file_get_contents($this->filePath));
		}

		$this->loadStrategies($registry);
		$this->loadFeatures($registry);
	}

	private function loadStrategies($registry)
	{
		if (!isset($this->data['strategies'])) {
			return;
		}

		foreach ($this->data['strategies'] as $name => $config) {

			// Try to find an already registered strategy
			$strategy = $registry->getStrategy($name);

			if (!$strategy) {
				$voter = $config['voter'];

				if (!is_callable($voter) && !is_string($voter)) {
					throw new \RuntimeException(sprintf("Voter for strategy '%s' must be either a callable or a valid expression string.", $name));
				}

				$strategy = Strategy::create($name, $voter);

				$registry->registerStrategy($strategy);
			}

			if (isset($config['options'])) {
				$strategy->setOptions($config['options']);
			}
		}
	}

	private function loadFeatures($registry)
	{
		if (!isset($this->data['features'])) {
			return;
		}

		foreach ($this->data['features'] as $name => $config) {
			$strategyName = $config['strategy'];

			if (!$strategyName) {
				throw new \RuntimeException(sprintf("A strategy must be specified for feature '%s'.", $name));
			}

			$variants = isset($config['variants']) ? $config['variants'] : [
				'enabled' => true,
				'disabled' => false
			];

			$feature = Feature::create($name, $variants);

			$registry->register($feature, $strategyName);
		}
	}

    /**
     * Gets the value of data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the value of filePath.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Gets the value of cacheEnabled.
     *
     * @return boolean
     */
    public function getCacheEnabled()
    {
        return $this->cacheEnabled;
    }
}