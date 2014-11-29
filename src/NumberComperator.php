<?php
  namespace paslandau\ComparisonUtility;
  
use paslandau\DataFiltering\Util\StringUtil;

class NumberComperator extends AbstractBaseComperator implements ComperatorInterface{
	const COMPARE_FUNCTION_LOWER = "<";
	const COMPARE_FUNCTION_LOWER_EQUALS= "<=";
	const COMPARE_FUNCTION_EQUALS = "=";
	const COMPARE_FUNCTION_GREATER_EQUALS = ">=";
	const COMPARE_FUNCTION_GREATER = ">";
	
	/**
	 * @var string
	 */
	public $function;
	
	/**
     * @param string $function . Member of self::COMPARE_FUNCTION_*
     * @param int|double|float $valueToTransformNullTo [optional]. Default: false.
	 */
	public function __construct($function, $valueToTransformNullTo = null)
    {
        parent::__construct($valueToTransformNullTo);
		$constants = (new \ReflectionClass(__CLASS__))->getConstants();
		if(!in_array($function, $constants)){
			throw new \InvalidArgumentException("'$function' unknown. Possible values: ".implode(", ",$constants));
		}
		$this->function = $function;
	}

    /**
     * Read: $compareValue $this->function $expectedValue, e.g
     * - $compareValue is lower than $expectedValue
     * - $compareValue is greather equals than $expectedValue
     * - ...
     * @param int|null $compareValue
     * @param int|null $expectedValue
     * @return bool
     * @throws \InvalidArgumentException if arguments are not numeric
     */
	public function compare($compareValue = null, $expectedValue = null) {
        parent::updateNullValues($compareValue, $expectedValue);

        if($compareValue === null || $expectedValue === null){ // not defined for numbers
            return false;
        }

		if(!is_numeric($compareValue) || !is_numeric($expectedValue)){
			throw new \InvalidArgumentException("First argument ('$compareValue') or second ('$expectedValue') argument is not numeric!");
		}
		
		switch($this->function){
			case self::COMPARE_FUNCTION_LOWER : return $compareValue < $expectedValue;
			case self::COMPARE_FUNCTION_LOWER_EQUALS: return $compareValue <= $expectedValue;
			case self::COMPARE_FUNCTION_EQUALS: return $compareValue == $expectedValue;
			case self::COMPARE_FUNCTION_GREATER_EQUALS: return $compareValue >= $expectedValue;
			case self::COMPARE_FUNCTION_GREATER: return $compareValue > $expectedValue;
			default: {
				$constants = (new \ReflectionClass(__CLASS__))->getConstants();
				throw new \UnexpectedValueException("Unknown function ('$this->function'). Possible values: ".implode(", ", $constants));
			}
		}
	}
}