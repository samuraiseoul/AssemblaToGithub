<?php

namespace Convert\Types\Converters;

use Convert\Types\BaseType;

abstract class BaseConverter
{
    /** @var  string */
    protected $type;
    
    /**
     * BaseConverter constructor.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }
    
    public function canConvert($type) {
        return $type == $this->type;
    }
    
    public abstract function convert($row, $map) : BaseType;
}