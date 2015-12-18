<?php

namespace LeoHt\Erlenmeyer\Feature;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->feature = Feature::create(
			'cool_feature',
			array('enabled', 'disabled'),
			array('thresold' => 30)
		);
	}

	public function testNameIsReturned()
	{
		$this->assertEquals($this->feature->getName(), 'cool_feature');
	}

	public function testVariantsAreReturned()
	{
		$this->assertEquals($this->feature->getVariants(), array('enabled', 'disabled'));
	}

	public function testOptionsAreReturned()
	{
		$this->assertEquals($this->feature->getOptions(), array('thresold' => 30));
	}
}