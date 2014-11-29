<?php

use paslandau\ComparisonUtility\AbstractBaseComperator;

class AbstractBaseComperatorTest extends PHPUnit_Framework_TestCase {

    public function test_ShouldDoNothingIfNotNull(){
        /** @var AbstractBaseComperator $c */
        $c = $this->getMockForAbstractClass(AbstractBaseComperator::class,["test"]);
        $v1 = "foo";
        $v1Test = $v1;
        $v2 = "bar";
        $v2Test = $v2;
        $c->updateNullValues($v1Test,$v2Test);
        $this->assertEquals($v1Test,$v1, "Variables should still be the same because they are not null");
        $this->assertEquals($v2Test,$v2, "Variables should still be the same because they are not null");
    }

    public function test_ShouldConvertIfNull(){
        /** @var AbstractBaseComperator $c */
        $newValue = "test";
        $c = $this->getMockForAbstractClass(AbstractBaseComperator::class,[$newValue]);
        $v1 = null;
        $v1Test = $v1;
        $v2 = null;
        $v2Test = $v1;
        $c->updateNullValues($v1Test,$v2Test);
        $this->assertEquals($v1Test,$newValue, "Variables should be updated to '$newValue' because they are null");
        $this->assertEquals($v2Test,$newValue, "Variables should be updated to '$newValue' because they are null");
    }
}
 