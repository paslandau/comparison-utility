<?php

use paslandau\ComparisonUtility\ComparisonObject;
use paslandau\ComparisonUtility\ComperatorInterface;

class ComparisonObjectTest extends PHPUnit_Framework_TestCase{

    private $comp;

    public function setUp(){
        $this->comp = $this->getMock(ComperatorInterface::class);
        $callback = function($obj1, $obj2){
            return $obj1 == $obj2;
        };
        $this->comp->expects($this->any())->method("compare")->will($this->returnCallback($callback));
    }

	public function testTrue()
	{
        $input = true;
        $c = new ComparisonObject($this->comp, $input);
        $actual = $c->compareToExpected($input);
        $this->assertTrue($actual);
	}

    public function testNull()
    {
        $input = null;
        $c = new ComparisonObject($this->comp, $input);
        $actual = $c->compareToExpected($input);
        $this->assertTrue($actual);
    }
	
	public function testFalse()
	{
        $input = true;
        $c = new ComparisonObject($this->comp, !$input);
        $actual = $c->compareToExpected($input);
        $this->assertFalse($actual);
	}
}