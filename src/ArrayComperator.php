<?php
  namespace paslandau\ComparisonUtility;

class ArrayComperator extends AbstractBaseComperator implements ComperatorInterface{
    const COMPARE_FUNCTION_EQUALS = "equals";
    const COMPARE_FUNCTION_CONTAINS = "contains";
	
	/**
	 * @var string
	 */
	private $function;

    /**
     * @var callable|null
     */
    private $compareValuesFn;

    /**
     * @var callable|null
     */
    private $compareKeysFn;

    /**
     * @var callable|null
     */
    private $sortFn;

    /**
     * @var
     */
    private $canContainMixedTypes;

    /**
     * @var
     */
    private $keysMustMatch;

    /**
     * @var bool
     */
    private $ignoreOrder;

    /**
     *
     * @param string $function . Member of self::COMPARE_FUNCTION_*
     * @param bool $keysMustMatch [optional]. Default: true.
     * @param bool $ignoreOrder [optional]. Default: false.
     * @param bool $canContainMixedTypes [optional]. Default: false.
     * @param array $valueToTransformNullTo [optional]. Default: false.
     */
	public function __construct($function, $keysMustMatch = null, $ignoreOrder = null, $canContainMixedTypes = null, $valueToTransformNullTo = null){
        parent::__construct($valueToTransformNullTo);

		$constants = (new \ReflectionClass(__CLASS__))->getConstants();
		if(!in_array($function, $constants)){
			throw new \InvalidArgumentException("'$function' unknown. Possible values: ".implode(", ",$constants));
		}
		$this->function = $function;
        if($keysMustMatch === null) {
            $keysMustMatch = true;
        }
        $this->keysMustMatch = $keysMustMatch;
        if($ignoreOrder === null) {
            $ignoreOrder = false;
        }
        $this->ignoreOrder = $ignoreOrder;
        $this->compareValuesFn = null;
        $this->compareKeysFn = null;
        $this->sortFn = null;
        if($canContainMixedTypes === null) {
            $canContainMixedTypes = false;
        }
        $this->canContainMixedTypes = $canContainMixedTypes;
	}

	
	/**
	 * Read: $expectedValue $this->function $compareValue, e.g
     * - $expectedValue contains $compareValue
     * - $expectedValue equals $compareValue
     * - ...
	 * @param string $compareValue
	 * @param string $expectedValue
	 * @throws \UnexpectedValueException if $this->function is unknown.
	 * @return bool. true if $expectedValue compares to $compareValue according to $this->function.
	 */
	public function compare($compareValue = null, $expectedValue = null){
        parent::updateNullValues($compareValue, $expectedValue);

        if($compareValue === null || $expectedValue === null){ // not defined for arrays
            return false;
        }

        // shortcuts
        if($this->function === self::COMPARE_FUNCTION_EQUALS) {
            if ($this->compareValuesFn === null && $this->keysMustMatch === true && $this->compareKeysFn === null && $this->ignoreOrder === false) {
                return $compareValue === $expectedValue;
            }
            if ($this->compareValuesFn === null && $this->compareKeysFn === null && $this->ignoreOrder === true) {
                $sortFn = $this->sortFn;
                if ($sortFn === null) {
                    $sortFn = function ($v1, $v2) {
                        if ($v1 === $v2) {
                            return 0;
                        }
                        $v1t = gettype($v1);
                        $v2t = gettype($v2);
                        if ($v1t !== $v2t) {
                            return $v1t > $v2t ? 1 : -1;
                        } else {
                            return $v1 > $v2 ? 1 : -1;
                        }
                    };
                }

                $recSort = function (&$arr) use (&$recSort, $sortFn) {
                    foreach ($arr as &$a) {
                        if (is_array($a)) {
                            $recSort($a);
                        }
                    }

                    if($this->canContainMixedTypes){
                        if($this->keysMustMatch === false) {
                            usort($arr, $sortFn);
                        }else{
                            uasort($arr, $sortFn);
                        }
                    }else {
                        if ($this->keysMustMatch === false) {
                            sort($arr);
                        } else {
                            asort($arr);
                        }
                    }
                };
                $recSort($compareValue);
                $recSort($expectedValue);
                return $compareValue === $expectedValue;
            }
        }

        $identity = function ($obj1, $obj2){
            return $obj1 === $obj2;
        };

        $cmpValueFn = $this->compareValuesFn;
        if($cmpValueFn === null) {
            $cmpValueFn = $identity;
        }

        $cmpKeyFn = $this->compareKeysFn;
        if($cmpKeyFn === null) {
            if($this->keysMustMatch === true) {
                $cmpKeyFn = $identity;
            }else{
                $cmpKeyFn = function () { return true; };
            }
        }

        foreach($compareValue as $ckey => $cvalue){
            $found = false;
            foreach($expectedValue as $ekey => $evalue){

                if(is_array($evalue) && is_array($cvalue)){ // call recursive if both values are arrays
                    // switch function to equality as that is what should be expected behaviour
                    $oldFn = $this->function;
                    $this->function = self::COMPARE_FUNCTION_EQUALS;
                    $foundValue = $this->compare($cvalue,$evalue);
                    // switch back
                    $this->function = $oldFn;
                }else{
                    $foundValue = $cmpValueFn($cvalue,$evalue);
                }
                $foundKey = $cmpKeyFn($ekey,$ckey);
                $found = $foundKey && $foundValue;
                if($found === true){ // found element, let's move on
                    unset($expectedValue[$ekey]);
                    break;
                }
                if($found === false && !$this->ignoreOrder){ // did not find the element
                    return false;                   // and the same order is required (which cannot be true anymore)
                }
            }
            if($found === false){ // did not find the element
                return false;
            }
        }
        if(count($expectedValue) > 0 && $this->function === self::COMPARE_FUNCTION_EQUALS){ // found all elements
            return false;                               // but there are still some left - which is forbidden
        }
        return true;
	}

    /**
     * @return mixed
     */
    public function getCanContainMixedTypes()
    {
        return $this->canContainMixedTypes;
    }

    /**
     * @param mixed $canContainMixedTypes
     */
    public function setCanContainMixedTypes($canContainMixedTypes)
    {
        $this->canContainMixedTypes = $canContainMixedTypes;
    }

    /**
     * @return callable|null
     */
    public function getCompareKeysFn()
    {
        return $this->compareKeysFn;
    }

    /**
     * @param callable|null $compareKeysFn
     */
    public function setCompareKeysFn($compareKeysFn)
    {
        $this->compareKeysFn = $compareKeysFn;
    }

    /**
     * @return callable|null
     */
    public function getCompareValuesFn()
    {
        return $this->compareValuesFn;
    }

    /**
     * @param callable|null $compareValuesFn
     */
    public function setCompareValuesFn($compareValuesFn)
    {
        $this->compareValuesFn = $compareValuesFn;
    }

    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * @return boolean
     */
    public function isIgnoreOrder()
    {
        return $this->ignoreOrder;
    }

    /**
     * @param boolean $ignoreOrder
     */
    public function setIgnoreOrder($ignoreOrder)
    {
        $this->ignoreOrder = $ignoreOrder;
    }

    /**
     * @return mixed
     */
    public function getKeysMustMatch()
    {
        return $this->keysMustMatch;
    }

    /**
     * @param mixed $keysMustMatch
     */
    public function setKeysMustMatch($keysMustMatch)
    {
        $this->keysMustMatch = $keysMustMatch;
    }

    /**
     * @return callable|null
     */
    public function getSortFn()
    {
        return $this->sortFn;
    }

    /**
     * @param callable|null $sortFn
     */
    public function setSortFn($sortFn)
    {
        $this->sortFn = $sortFn;
    }


}
?>