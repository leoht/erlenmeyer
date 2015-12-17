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
	
	public static function create($name, array $variants = array())
	{
		return new static($name, $variants);
	}
	
	/**
	 * Constructor.
	 */
	public function __construct($name, array $variants = array())
	{
		$this->name = $name;
		
		if (empty($variants)) {
			$variants = ['enabled' => true, 'disabled' => false];
		}
		
		$this->variants = $variants;
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
 
}
