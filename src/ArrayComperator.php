<?php
  namespace paslandau\ComparisonUtility;

use paslandau\Benchmark\Benchmark;

class ArrayComperator extends AbstractBaseComperator{
    const COMPARE_FUNCTION_EQUALS = "equals";
    const COMPARE_FUNCTION_CONTAINS = "contains";
	
	/**
	 * @var string
	 */
	private $function;

    /**
     * @var callable
     */
    private $compareValuesFn;

    /**
     * @var callable
     */
    private $compareKeysFn;

    /**
     * @var bool
     */
    private $ignoreOrder;

    /**
     *
     * @param string $function . Member of self::COMPARE_FUNCTION_*
     * @param callable $compareValuesFn [optional]. Default: null (Uses "==" as compare operation for elements).
     * @param callable $compareKeysFn [optional]. Default: null (Does not compare keys => returns always true).
     * @param bool $ignoreOrder [optional]. Default: true.
     * @param bool $canBeNull [optional]. Default: false.
     * @internal param bool $ignoreCase [optional]. Default: false.
     */
	public function __construct($function, callable $compareValuesFn = null, callable $compareKeysFn = null, $ignoreOrder = null, $canBeNull = null){
        parent::__construct($canBeNull);

		$constants = (new \ReflectionClass(__CLASS__))->getConstants();
		if(!in_array($function, $constants)){
			throw new \InvalidArgumentException("'$function' unknown. Possible values: ".implode(", ",$constants));
		}
		$this->function = $function;
        if($compareValuesFn === null) {
            $compareValuesFn = function ($obj1, $obj2){
                // gettype($obj1) == gettype($obj2) &&
                return $obj1 === $obj2;
            };
        }
        $this->compareValuesFn = $compareValuesFn;
        if($compareKeysFn === null) {
            $compareKeysFn = function ($obj1, $obj2){
                return true;
            };
        }
        $this->compareKeysFn = $compareKeysFn;
        if($ignoreOrder === null) {
            $ignoreOrder = true;
        }
        $this->ignoreOrder = $ignoreOrder;
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
        if(parent::compare($compareValue, $expectedValue)){
            return true;
        }

        $cmpValueFn = $this->compareValuesFn;
        $cmpKeyFn = $this->compareKeysFn;

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
}
?>