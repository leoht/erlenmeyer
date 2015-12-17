<?php

namespace LeoHt\Erlenmeyer\Loader;

use LeoHt\Erlenmeyer\Registry;

class CacheLoader implements LoaderInterface
{

	/**
	 * @var LoaderInterface
	 */
	private $cachedLoader;

	public function __construct(LoaderInterface $loader)
	{
		$this->cachedLoader = $loader;
	}

	public function load(Registry $registry)
	{
		// $this->loader->load($registry);
	}
}