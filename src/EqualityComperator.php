<?php
namespace paslandau\ComparisonUtility;

class EqualityComperator extends AbstractBaseComperator
{
    const COMPARE_FUNCTION_EQUALITY = "==";
    const COMPARE_FUNCTION_IDENTITY = "===";
    const COMPARE_FUNCTION_NOT_EQUALITY = "!=";
    const COMPARE_FUNCTION_NOT_IDENTITY = "!==";

    /**
     *
     * @var string
     */
    public $function;

    /**
     * @param string $function . Member of self::COMPARE_FUNCTION_*
     * @param bool $canBeNull [optional]. Default: false.
     */
    public function __construct($function, $canBeNull = null)
    {
        parent::__construct($canBeNull);
        $constants = (new \ReflectionClass(__CLASS__))->getConstants();
        if (!in_array($function, $constants)) {
            throw new \InvalidArgumentException("'$function' unknown. Possible values: " . implode(", ", $constants));
        }
        $this->function = $function;
    }

    /**
     * @param mixed|null $compareValue
     * @param mixed|null $expectedValue
     * @throws \UnexpectedValueException if $this->function is unknown.
     * @return boolean. true if $compareValue equals $expectedValue according to $this->function.
     */
    public function compare($compareValue = null, $expectedValue = null)
    {
        if(parent::compare($compareValue,$expectedValue)){
            return true;
        }

        switch ($this->function) {
            case self::COMPARE_FUNCTION_EQUALITY:
        {
            $actual = $compareValue == $expectedValue;
        }
            break;
            case self::COMPARE_FUNCTION_IDENTITY:
            {
                $actual = $compareValue === $expectedValue;
            }
                break;
            case self::COMPARE_FUNCTION_NOT_EQUALITY:
            {
                $actual = $compareValue != $expectedValue;
            }
                break;
            case self::COMPARE_FUNCTION_NOT_IDENTITY:
            {
                $actual = $compareValue !== $expectedValue;
            }
                break;
            default:
                {
                $constants = (new \ReflectionClass(__CLASS__))->getConstants();
                throw new \UnexpectedValueException("'$this->function' unknown. Possible values: " . implode(", ", $constants));
                }
        }

        return $actual;
    }
}

?>