<?php

namespace LeoHt\Erlenmeyer\Strategy\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class Provider implements ExpressionFunctionProviderInterface
{
	public function getFunctions()
	{
		return array(
			$this->buildRandomFunction()
		);
	}

	private function buildRandomFunction()
	{
		return new ExpressionFunction('random', function ($min, $max) {
			return sprintf("mt_rand(%d, %d)", $min, $max);
		}, function ($args, $min, $max) {
			return mt_rand($min, $max);
		});
	}
}