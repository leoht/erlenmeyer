<?php

namespace LeoHt\Erlenmeyer;

use LeoHt\Erlenmeyer\Strategy\Strategy;
use LeoHt\Erlenmeyer\Feature\Feature;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Decider
{
	/**
	 * @var array
	 */
	protected $context;

	public function __construct(ExpressionLanguage $expressionLanguage)
	{
		$this->expressionLanguage = $expressionLanguage;
	}

	public function set($key, $value)
    {
        $this->context[$key] = $value;
    }

    public function get($key)
    {
    	return isset($this->context[$key]) ? $this->context[$key] : null;
    }

    public function decide(Strategy $strategy, Feature $feature, array $context = array())
    {
        $voter = $strategy->getVoter();
        $context = !empty($context) ? $context : $this->getContext();
		$context['_user'] = $this->get('_user');

        $options = array_merge($strategy->getOptions(), $feature->getOptions());

        if (is_callable($voter)) {

        	return $this->callVoter($voter, $context, $options);
        } else if (is_string($voter)) {

            $contextObj = $this->arrayToObject($context);
            $optionsObj = $this->arrayToObject($options);

        	return $this->expressionLanguage->evaluate($voter, [
        		'context' => $contextObj,
        		'options' => $optionsObj,
        		'user' => $this->get('_user')
        	]);
        }
    }

    protected function callVoter(callable $voter, $context, $options)
    {
    	$result = call_user_func_array($voter, [
            $context,
            $options
        ]);
        
        return $result;
    }

    private function arrayToObject(array $array)
    {
        $obj = new \stdClass();

        foreach ($array as $key => $value) {
            $obj->{$key} = $value;
        }

        return $obj;
    }

    /**
     * Gets the value of context.
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Sets the value of context.
     *
     * @param array $context the context
     *
     * @return self
     */
    protected function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }
}
