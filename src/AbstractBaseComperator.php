<?php

namespace paslandau\ComparisonUtility;

abstract class AbstractBaseComperator implements ComperatorInterface{

    private $canBeNull;

    /**
     * @param bool $canBeNull [optional]. Default: false.
     */
    function __construct($canBeNull = null)
    {
        if($canBeNull === null) {
            $canBeNull = false;
        }
        $this->canBeNull = $canBeNull;
    }


    /**
     * Compares $obj1 to $obj2 and returns true or false.
     * Implementing classed should provide a suitable comparison function
     * @param mixed $obj1
     * @param mixed $obj2
     * @return bool
     */
    public function compare($obj1 = null, $obj2= null)
    {
        if(($obj1 === null || $obj2 === null)){
            if($this->canBeNull) {
                return true;
            }
            throw new \InvalidArgumentException("Arguments must not be null!");
        }
        return false;
    }

} 