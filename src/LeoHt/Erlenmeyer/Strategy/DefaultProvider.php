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

			/*
			 * Percentage distribution.
			 *
			 */
			Strategy::create('distribute', function ($context, $options) {
				if (!isset($options['distribution']) || !is_array($options['distribution'])) {
					throw new \RuntimeException(sprintf("Missing configuration array 'distribution' for the distribute strategy."));
				}

				$distribution = $options['distribution'];
				$thresoldMap = array();
				$previousValue = 0;
				
				foreach ($distribution as $name => $value) {
					$thresoldMap[$name] = $value + $previousValue;
					$previousValue = $thresoldMap[$name];
				}

				$randomPercent = mt_rand(0, 100);
				$previousThresold = 0;

				foreach ($thresoldMap as $name => $thresold) {
					if ($previousThresold <= $randomPercent && $randomPercent < $thresold) {
						return $name;
					}

					$previousThresold = $thresold;
				}

				return $name;
			}),
		];
	}
}