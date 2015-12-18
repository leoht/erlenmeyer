<?php

namespace LeoHt\Erlenmeyer\Feature;

class Feature
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $variants;

    /**
     * @var array
     */
    protected $options;
	
	public static function create($name, array $variants = array(), array $options = array())
	{
		return new static($name, $variants, $options);
	}
	
	/**
	 * Constructor.
	 */
	public function __construct($name, array $variants = array(), array $options = array())
	{
		$this->name = $name;
		
		if (empty($variants)) {
			$variants = ['enabled' => true, 'disabled' => false];
		}
		
		$this->variants = $variants;
        $this->options = $options;
	}

    public function resolveVariant($value)
    {
        foreach ($this->variants as $variant => $val) {
            if ($val === $value) {
                return $variant;
            }
        }

        return null;
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
     * Get the value of Variants 
     * 
     * @return array
     */
    public function getVariants()
    {
        return $this->variants;
    }
 
    /** 
     * Set the value of Variants 
     * 
     * @param array variants
     * 
     * @return self
     */
    public function setVariants(array $variants)
    {
        $this->variants = $variants;
 
        return $this;
    }
 
    /**
     * Gets the value of options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the value of options.
     *
     * @param array $options the options
     *
     * @return self
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}
