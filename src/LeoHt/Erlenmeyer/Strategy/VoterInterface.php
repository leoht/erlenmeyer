<?php

namespace LeoHt\Erlenmeyer\Strategy;

interface VoterInterface
{
	public function vote($context, $options = array());
}