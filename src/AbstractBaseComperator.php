<?php

namespace paslandau\ComparisonUtility;

abstract class AbstractBaseComperator{

    /**
     * @var mixed|null
     */
    private $valueToTransformNullTo;

    /**
     * @param mixed|null $valueToTransformNullTo [optional]. Default: null.
     */
    function __construct($valueToTransformNullTo = null)
    {
        $this->valueToTransformNullTo = $valueToTransformNullTo;
    }


    /**
     * @param mixed $obj1
     * @param mixed $obj2
     */
    public function updateNullValues(&$obj1 = null, &$obj2= null)
    {
        if($obj1 === null){
            $obj1 = $this->valueToTransformNullTo;
        }
        if($obj2 === null){
            $obj2 = $this->valueToTransformNullTo;
        }
    }

} 