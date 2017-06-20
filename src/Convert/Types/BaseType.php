<?php

namespace Convert\Types;

abstract class BaseType implements \JsonSerializable
{
    private $id;
    
    /**
     * BaseType constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    function jsonSerialize()
    {
        throw new \Exception("Method not implimented.");
    }
}