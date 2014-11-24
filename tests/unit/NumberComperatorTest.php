<?php


use paslandau\ComparisonUtility\NumberComperator;

class NumberComperatorTest extends PHPUnit_Framework_TestCase{
	
	public function testNumberComperatorLowerLower()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_LOWER);
		$res = $c->compare(1, 2);
		$this->assertTrue($res);
	}
	
	public function testNumberComperatorLowerGreater()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_LOWER);
		$res = $c->compare(2, 1);
		$this->assertFalse($res);
	}
	
	public function testNumberComperatorLowerFalseEquals()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_LOWER);
		$res = $c->compare(2, 2);
		$this->assertFalse($res);
	}

	public function testNumberComperatorLowerEqualsLower()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_LOWER_EQUALS);
		$res = $c->compare(1, 2);
		$this->assertTrue($res);
	}
	
	public function testNumberComperatorLowerEqualsGreater()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_LOWER_EQUALS);
		$res = $c->compare(2, 1);
		$this->assertFalse($res);
	}
	
	public function testNumberComperatorLowerEqualsTrueEqual()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_LOWER_EQUALS);
		$res = $c->compare(2, 2);
		$this->assertTrue($res);
	}
	
	public function testNumberComperatorEqualsLower()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_EQUALS);
		$res = $c->compare(1, 2);
		$this->assertFalse($res);
	}
	
	public function testNumberComperatorEqualsGreater()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_EQUALS);
		$res = $c->compare(2, 1);
		$this->assertFalse($res);
	}
	
	public function testNumberComperatorEqualsTrueEqual()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_EQUALS);
		$res = $c->compare(2, 2);
		$this->assertTrue($res);
	}
	
	public function testNumberComperatorGreaterEqualsLower()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_GREATER_EQUALS);
		$res = $c->compare(1, 2);
		$this->assertFalse($res);
	}
	
	public function testNumberComperatorGreaterEqualsGreater()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_GREATER_EQUALS);
		$res = $c->compare(2, 1);
		$this->assertTrue($res);
	}
	
	public function testNumberComperatorGreaterEqualsTrueEqual()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_GREATER_EQUALS);
		$res = $c->compare(2, 2);
		$this->assertTrue($res);
	}	
	
	public function testNumberComperatorGreaterLower()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_GREATER);
		$res = $c->compare(1, 2);
		$this->assertFalse($res);
	}
	
	public function testNumberComperatorGreaterGreater()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_GREATER);
		$res = $c->compare(2, 1);
		$this->assertTrue($res);
	}
	
	public function testNumberComperatorGreaterTrueEqual()
	{
		$c = new NumberComperator(NumberComperator::COMPARE_FUNCTION_GREATER);
		$res = $c->compare(2, 2);
		$this->assertFalse($res);
	}
}