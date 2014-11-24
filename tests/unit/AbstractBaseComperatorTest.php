<?php

use paslandau\ComparisonUtility\AbstractBaseComperator;

class AbstractBaseComperatorTest extends PHPUnit_Framework_TestCase {

    public function test_ShouldReturnFalseOnNotNull(){
        /** @var AbstractBaseComperator $c */
        $c = $this->getMockForAbstractClass(AbstractBaseComperator::class,[false]);
        $this->assertFalse($c->compare("",""), "Should be false [compareValue is null but canBeNull is false]");
    }

    public function test_ShouldThrowExceptionOnNull(){
        $this->setExpectedException(InvalidArgumentException::class);
        /** @var AbstractBaseComperator $c */
        $c = $this->getMockForAbstractClass(AbstractBaseComperator::class,[false]);
        $this->assertFalse($c->compare(null,null));
    }

    public function test_ShouldReturnTrueOnNull(){
        /** @var AbstractBaseComperator $c */
        $c = $this->getMockForAbstractClass(AbstractBaseComperator::class,[true]);
        $this->assertTrue($c->compare(null,""), "Should be false [compareValue is null but canBeNull is false]");
        $this->assertTrue($c->compare(null,""), "Should be false [expectedValue is null but canBeNull is false]");
    }
}
 