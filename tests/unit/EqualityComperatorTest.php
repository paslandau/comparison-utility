<?php

use paslandau\ComparisonUtility\EqualityComperator;

class EqualityComperatorTest extends PHPUnit_Framework_TestCase{
	
	public function testEqualityTrue()
	{
        $tests = array(
            ["1" , "1"],
            ["1" , 1],
            [1,true],
            ["1",true],
        );
		$c = new EqualityComperator(EqualityComperator::COMPARE_FUNCTION_EQUALITY);
        foreach($tests as $vals){
            $res = $c->compare($vals[0], $vals[1]);
            $msg = var_export($vals, true);
            $this->assertTrue($res,$msg);
        }
	}

    public function testEqualityFalse()
    {
        $tests = array(
            ["1" , "0"],
            ["1" , 0]
        );
        $c = new EqualityComperator(EqualityComperator::COMPARE_FUNCTION_EQUALITY);
        foreach($tests as $vals){
            $res = $c->compare($vals[0], $vals[1]);
            $msg = var_export($vals, true);
            $this->assertFalse($res,$msg);
        }
    }

    public function testIdentityTrue()
    {
        $tests = array(
            ["1" , "1"],
        );
        $c = new EqualityComperator(EqualityComperator::COMPARE_FUNCTION_IDENTITY);
        foreach($tests as $vals){
            $res = $c->compare($vals[0], $vals[1]);
            $msg = var_export($vals, true);
            $this->assertTrue($res,$msg);
        }
    }

    public function testIdentityFalse()
    {
        $tests = array(
            ["1" , "0"],
            ["1" , 1],
            [1,true],
            ["1",true],

        );
        $c = new EqualityComperator(EqualityComperator::COMPARE_FUNCTION_IDENTITY);
        foreach($tests as $vals){
            $res = $c->compare($vals[0], $vals[1]);
            $msg = var_export($vals, true);
            $this->assertFalse($res,$msg);
        }
    }
}