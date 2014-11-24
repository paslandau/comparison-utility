<?php

use paslandau\ComparisonUtility\StringComperator;

class StringComperatorTest extends PHPUnit_Framework_TestCase{
	
	public function testStringComperatorContainsFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_CONTAINS);
		$res = $c->compare("Straßenschäden", "Straßenschaden auf dem Weg");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorContainsTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_CONTAINS);
		$res = $c->compare("Straßenschäden", "Straßenschäden auf dem Weg");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorContainsIgnoreCaseTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_CONTAINS, true);
		$res = $c->compare("straßenschäden", "Straßenschäden auf dem Weg");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorContainsIgnoreCaseFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_CONTAINS, false);
		$res = $c->compare("straßenschäden", "Straßenschäden auf dem Weg");
		$this->assertFalse($res);
	}

	public function testStringComperatorEndsWithFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_ENDS_WITH);
		$res = $c->compare("Straßenschäden", "Straßenschaden auf dem Weg");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorEndsWithTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_ENDS_WITH);
		$res = $c->compare("Straßenschäden", "Auf dem Weg Straßenschäden");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorEndsWithIgnoreCaseTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_ENDS_WITH, true);
		$res = $c->compare("straßenschäden", "Auf dem Weg Straßenschäden");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorEndsWithIgnoreCaseFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_ENDS_WITH, false);
		$res = $c->compare("straßenschäden", "Auf dem Weg Straßenschäden");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorStartsWithFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_STARTS_WITH);
		$res = $c->compare("Straßenschäden", "Straßenschaden auf dem Weg");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorStartsWithTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_STARTS_WITH);
		$res = $c->compare("Straßenschäden", "Straßenschäden auf dem Weg");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorStartsWithIgnoreCaseTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_STARTS_WITH, true);
		$res = $c->compare("straßenschäden", "Straßenschäden auf dem Weg");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorStartsWithIgnoreCaseFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_STARTS_WITH, false);
		$res = $c->compare("straßenschäden", "Straßenschäden auf dem Weg");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorEqualsFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_EQUALS);
		$res = $c->compare("Straßenschäden", "Straßenschaden auf dem Weg");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorEqualsTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_EQUALS);
		$res = $c->compare("Straßenschäden", "Straßenschäden");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorEqualsIgnoreCaseTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_EQUALS, true);
		$res = $c->compare("straßenschäden", "Straßenschäden");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorEqualsIgnoreCaseFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_EQUALS, false);
		$res = $c->compare("straßenschäden", "Straßenschäden");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorMatchesFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_MATCHES);
		$res = $c->compare("Straßenschäden auf dem Weg!!!", "#^[\\p{L}\\s]+$#u");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorMatchesTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_MATCHES);
		$res = $c->compare("Straßenschäden auf dem Weg", "#^[\\p{L}\\s]+$#u");
		$this->assertTrue($res);
	}
	
	public function testStringComperatorMatchesEmptyFalse()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_MATCHES, false, false);
		$res = $c->compare("", "#^[\\w]+$#");
		$this->assertFalse($res);
	}
	
	public function testStringComperatorMatchesNullTrue()
	{
		$c = new StringComperator(StringComperator::COMPARE_FUNCTION_MATCHES, false, true);
		$res = $c->compare(null, "#^[\\w]+$#");
		$this->assertTrue($res);
	}
}
