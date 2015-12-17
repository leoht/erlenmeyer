<?php

namespace LeoHt\Erlenmeyer;

use LeoHt\Erlenmeyer\Strategy\Strategy;
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
    	return isset($this->context[$key]) ? $this->context[$key] : $key;
    }

    public function decide(Strategy $strategy, array $context = array())
    {
        $voter = $strategy->getVoter();
        $context = !empty($context) ? $context : $this->getContext();
		$context['_user'] = $this->get('_user');

        if (is_callable($voter)) {

        	return $this->callVoter($voter, $context, $strategy->getOptions());
        } else if (is_string($voter)) {

        	return $this->expressionLanguage->evaluate($voter, [
        		'context' => $context,
        		'options' => $strategy->getOptions(),
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
