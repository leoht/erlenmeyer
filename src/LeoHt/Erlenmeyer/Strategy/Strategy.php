<?php

namespace LeoHt\Erlenmeyer\Strategy;

class Strategy
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var mixed
     */
    protected $voter;
    
    /**
     * @var array
     */
    protected $options;
    
    public static function create($name, $voter = null, $options = array())
    {
        return new static($name, $voter, $options);
    }
    
    /**
     * Constructor.
     */
    public function __construct($name, $voter = null, $options = array())
    {
        $this->name = $name;
        $this->voter = $voter;
        $this->options = $options;
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Get the value of Name 
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
 
    /** 
     * Set the value of Name 
     * 
     * @param string name
     * 
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
 
        return $this;
    }

    /**
     * Get the value of Voter
     * 
     * @return
     */
    public function getVoter()
    {
        return $this->voter;
    }
 
    /** 
     * Set the value of Voter
     * 
     * @param $voter
     * 
     * @return self
     */
    public function setVoter($voter)
    {
        $this->voter = $voter;
 
        return $this;
    }

    /**
     * Get the value of Options 
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
 
    /** 
     * Set the value of Options 
     * 
     * @param array options
     * 
     * @return self
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
 
        return $this;
    }
}
