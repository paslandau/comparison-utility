<?php
namespace paslandau\ComparisonUtility;

interface ComparisonObjectInterface{

    /**
     * @param mixed $obj1
     * @return void
     */
    public function setExpected($obj1 = null);

    /**
     * @param mixed $obj2
     * @return bool
     */
    public function compareToExpected($obj2 = null);
}