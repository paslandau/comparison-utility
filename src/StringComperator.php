<?php
  namespace paslandau\ComparisonUtility;

use paslandau\DataFiltering\Util\StringUtil;

class StringComperator extends AbstractBaseComperator implements ComperatorInterface{
	const COMPARE_FUNCTION_STARTS_WITH = "startsWith";
	const COMPARE_FUNCTION_ENDS_WITH = "endsWith";
	const COMPARE_FUNCTION_CONTAINS = "contains";
	const COMPARE_FUNCTION_EQUALS = "equals";
	const COMPARE_FUNCTION_MATCHES = "matches";
	
	/**
	 * 
	 * @var string
	 */
	public $function;
	/**
	 *
	 * @var bool
	 */
	public $ignoreCase;

	/**
	 * 
	 * @param string $function. Member of self::COMPARE_FUNCTION_*
	 * @param bool $ignoreCase [optional]. Default: false.
	 * @param string $valueToTransformNullTo [optional]. Default: false.
	 */
	public function __construct($function, $ignoreCase = null, $valueToTransformNullTo = null){
        parent::__construct($valueToTransformNullTo);

		$constants = (new \ReflectionClass(__CLASS__))->getConstants();
		if(!in_array($function, $constants)){
			throw new \InvalidArgumentException("'$function' unknown. Possible values: ".implode(", ",$constants));
		}
		$this->function = $function;
		if($ignoreCase === null){
			$ignoreCase = false;
		}
		$this->ignoreCase = $ignoreCase;
	}

	
	/**
	 * Read: $expectedValue $this->function $compareValue, e.g
     * - $expectedValue contains $compareValue
     * - $expectedValue matches $compareValue
     * - ...
	 * @param string|null $compareValue
	 * @param string|null $expectedValue (or the pattern when using COMPARE_FUNCTION_MATCHES or the $haystack when using COMPARE_FUNCTION_CONTAINS)
	 * @throws \UnexpectedValueException if $this->function is unknown.
	 * @return bool. true if $expectedValue compares to $compareValue according to $this->function.
	 */
	public function compare($compareValue = null, $expectedValue = null){
        parent::updateNullValues($compareValue, $expectedValue);

        if($compareValue === null || $expectedValue === null){ // not defined for strings
            return false;
        }
		
		switch($this->function){
			case self::COMPARE_FUNCTION_EQUALS:
            {
                return $this->equals($expectedValue, $compareValue,$this->ignoreCase);
			}
			case self::COMPARE_FUNCTION_CONTAINS:{
				if($this->ignoreCase){
                    return mb_stristr($expectedValue, $compareValue) !== false;
				}
                return mb_strstr($expectedValue, $compareValue) !== false;
			}
			case self::COMPARE_FUNCTION_STARTS_WITH:{
                $length = mb_strlen($compareValue);
                $original = mb_substr($expectedValue, 0, $length);
                return $this->equals($original,$compareValue,$this->ignoreCase);
			}
			case self::COMPARE_FUNCTION_ENDS_WITH:{
                $length = mb_strlen($compareValue);
                $original = mb_substr($expectedValue, -$length);
                return $this->equals($original,$compareValue,$this->ignoreCase);
			}
			case self::COMPARE_FUNCTION_MATCHES:{
                return (preg_match($expectedValue, $compareValue) > 0);
			}
			default: {
				$constants = (new \ReflectionClass(__CLASS__))->getConstants();
				throw new \UnexpectedValueException("'$this->function' unknown. Possible values: ".implode(", ",$constants));
			}
		}
	}

    /**
     * @param string $expectedValue
     * @param string $compareValue
     * @param bool $ignoreCase
     * @return bool
     */
    private function equals($expectedValue, $compareValue, $ignoreCase){
        if($ignoreCase){
            return mb_strtolower($compareValue) == mb_strtolower($expectedValue);
        }
        return $compareValue == $expectedValue;
    }
}
?>