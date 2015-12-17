<?php

namespace LeoHt\Erlenmeyer\Loader;

use LeoHt\Erlenmeyer\Registry;

interface LoaderInterface
{
	public function load(Registry $registry);
}