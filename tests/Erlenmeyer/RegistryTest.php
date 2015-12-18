<?php

namespace LeoHt\Erlenmeyer;

use LeoHt\Erlenmeyer\Feature\Feature;
use LeoHt\Erlenmeyer\Strategy\Strategy;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->registry = new Registry();
		$this->feature = Feature::create('top_secret', array(
			'enabled',
			'disabled'
		), array(
			'whitelist' => array('127.0.0.1', '10.0.0.5')
		));

		$this->strategy = Strategy::create('ip_whitelist', function ($context, $options) {
			return in_array($context['client_ip'], $options['whitelist']);
		});

		// $this->strategy = Strategy::create('ip_whitelist', 'context.client_ip in options.whitelist');

		$this->registry->registerStrategy($this->strategy);
		$this->registry->register($this->feature, 'ip_whitelist');
	}

	public function testGetFeature()
	{
		$this->assertEquals($this->feature, $this->registry->getFeature('top_secret'));
	}

	public function testGetStrategy()
	{
		$this->assertEquals($this->strategy, $this->registry->getStrategy('ip_whitelist'));
	}

	public function testResolveStrategy()
	{
		$this->assertEquals($this->strategy, $this->registry->resolveStrategy('top_secret'));
	}
}