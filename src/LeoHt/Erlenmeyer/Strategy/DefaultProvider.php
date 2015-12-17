<?php

namespace LeoHt\Erlenmeyer\Strategy;

class DefaultProvider implements StrategyProviderInterface
{
	public function getStrategies()
	{
		return [

			Strategy::create('random', function ($context, $options) {
				return mt_rand(0, 1) < 0.5;
			}),

			Strategy::create('percent', function ($context, $options) {
				return mt_rand(0, 100) < $options['thresold'];
			}),

			Strategy::create('percent_ranges', function ($context, $options) {
				return mt_rand(0, 100);
			}),
		];
	}
}