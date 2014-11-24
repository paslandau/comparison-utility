<?php
namespace paslandau\ComparisonUtility;

class ComparisonObject implements ComparisonObjectInterface
{

    /**
     * @var ComperatorInterface
     */
    private $comperator;

    /**
     * @var mixed|null
     */
    private $expected;

    /**
     * Creates a new Object
     * @param ComperatorInterface $comperator
     * @param mixed|null $expected [optional]. Default: null.
     */
    function __construct(ComperatorInterface $comperator, $expected = null)
    {
        $this->comperator = $comperator;
        $this->SetExpected($expected);
    }

    /**
     * @param mixed|null $expected
     */
    public function SetExpected($expected = null)
    {
        $this->expected = $expected;
    }

    /**
     * @return mixed|null
     */
    public function getExpected()
    {
        return $this->expected;
    }

    /**
     * @param mixed $compareValue
     * @return bool
     */
    public function CompareToExpected($compareValue = null)
    {
        $res = $this->comperator->compare($compareValue, $this->expected);
        return $res;
    }

    public function __toString(){
        return json_encode($this,JSON_PRETTY_PRINT);
    }
}