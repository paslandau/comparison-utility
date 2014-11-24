<?php

use paslandau\Benchmark\Benchmark;
use paslandau\ComparisonUtility\ArrayComperator;
use paslandau\ComparisonUtility\ArrayComperatorPerformance;
use paslandau\ComparisonUtility\ComperatorInterface;

class ArrayComperatorPerformanceTest extends PHPUnit_Framework_TestCase {

    private $tests;

    protected function setUp()
    {
        $foo = new stdClass();
        $foo->foo = "foo";
        $bar = new stdClass();
        $bar->bar = "bar";
        $this->tests = [
            "equals-1-diff-types" => ["1-element arrays with string/int", [["1"],[1]]],
            "equals-1" => ["1-element string arrays with identical string", [["foo"],["foo"]]],
            "not-equals-1" => ["1-element string arrays with different string", [["foo"],["bar"]]],
            "equals-2-diff-order" => ["2-element string arrays with same strings but different order", [["foo","bar"],["bar","foo"]]],
            "equals-2" => ["2-element string arrays with same strings and same order", [["bar","foo"],["bar","foo"]]],
            "equals-1-obj" => ["1-element object arrays with equal but not identical objects", [[$foo],[$foo]]],
            "equals-1-multi" => ["1-element multi dimensional string arrays with identical string", [[["foo"]],[["foo"]]]],
            "equals-2-multi" => ["2-element multi dimensional string arrays with same strings and same order", [[["foo","bar"]],[["foo","bar"]]]],
            "equals-2-multi-diff-order" =>["2-element multi dimensional string arrays with same strings but different order", [[["foo","bar"]],[["bar","foo"]]]],
            "contains-1" => ["1-element string arrays with identical string", [["foo","bar"],["foo"]]],
            "not-contains-1" => ["1-element string arrays with different string", [["foo","baz"],["bar"]]],
            "contains-2-diff-order" => ["2-element string arrays with same strings but different order", [["foo","bar","baz"],["bar","foo"]]],
            "contains-2" => ["2-element string arrays with same strings and same order", [["bar","foo","baz"],["bar","foo"]]],
            "contains-1-obj" => ["1-element object arrays with equal but not identical objects", [[$foo, $bar],[$foo]]],
            "contains-1-multi" => ["1-element multi dimensional string arrays with identical string", [[["foo","bar"]],[["foo"]]]],
            "contains-2-multi" => ["2-element multi dimensional string arrays with same strings and same order", [[["foo","bar","baz"]],[["foo","bar"]]]],
            "contains-2-multi-diff-order" => ["2-element multi dimensional string arrays with same strings but different order", [[["foo","bar","baz"]],[["bar","foo"]]]],
            ## different keys
            "equals-1-diff-types-diff-keys" => ["1-element arrays with string/int", [["key1" => "1"],["key2" =>1]]],
            "equals-1-diff-keys" => ["1-element string arrays with identical string", [["key1" => "foo"],["key2" =>"foo"]]],
            "not-equals-1-diff-keys" => ["1-element string arrays with different string", [["key1" => "foo"],["key2" => "bar"]]],
            "equals-2-diff-order-diff-keys" => ["2-element string arrays with same strings but different order", [["key11" => "foo", "key12" =>"bar"],["key21" =>"bar","key22" =>"foo"]]],
            "equals-2-diff-keys" => ["2-element string arrays with same strings and same order", [["key11" =>"bar","key12" =>"foo"],["key21" =>"bar", "key22" =>"foo"]]],
            "equals-1-obj-diff-keys" => ["1-element object arrays with equal but not identical objects", [["key1" => $foo],["key2" => $foo]]],
            "equals-1-multi-diff-keys" => ["1-element multi dimensional string arrays with identical string", [[["key1" => "foo"]],[["key2" => "foo"]]]],
            "equals-2-multi-diff-keys" => ["2-element multi dimensional string arrays with same strings and same order", [[["key11" => "foo", "key12" =>"bar"]],[["key21" =>"foo","key22" =>"bar"]]]],
            "equals-2-multi-diff-order-diff-keys" =>["2-element multi dimensional string arrays with same strings but different order", [[["key11" => "foo", "key12" =>"bar"]],[["key21" =>"bar","key22" =>"foo"]]]],
            "contains-1-diff-keys" => ["1-element string arrays with identical string", [["key11" => "foo", "key12" =>"bar"],["key21" =>"foo"]]],
            "not-contains-1-diff-keys" => ["1-element string arrays with different string", [["key11" =>"foo","key12" =>"baz"],["key21" =>"bar"]]],
            "contains-2-diff-order-diff-keys" => ["2-element string arrays with same strings but different order", [["key11" => "foo", "key12" =>"bar","key13" =>"baz"],["key21" =>"bar","key22" =>"foo"]]],
            "contains-2-diff-keys" => ["2-element string arrays with same strings and same order", [["key11" =>"bar", "key12" =>"foo", "key13" =>"baz"],["key21" =>"bar","key22" =>"foo"]]],
            "contains-1-obj-diff-keys" => ["1-element object arrays with equal but not identical objects", [["key11" =>$foo, "key22" =>$bar],["key21" => $foo]]],
            "contains-1-multi-diff-keys" => ["1-element multi dimensional string arrays with identical string", [[["key11" => "foo", "key12" =>"bar"]],[["key21" =>"foo"]]]],
            "contains-2-multi-diff-keys" => ["2-element multi dimensional string arrays with same strings and same order", [[["key11" => "foo", "key12" =>"bar","baz"]],[["key21" => "foo", "key22" =>"bar"]]]],
            "contains-2-multi-diff-order-diff-keys" => ["2-element multi dimensional string arrays with same strings but different order", [[["key11" => "foo", "key12" =>"bar", "key13" => "baz"]],[["key21" =>"bar","key22" =>"foo"]]]],
            ##Edge cases
            "contains-2-multi-contains-mixed-types" => ["2-element multi dimensional string arrays with same strings but different order",
                [
                    [
                        $foo,
                        [
                            "key11" => $foo,
                            "key12" => "bar",
                            "key13" => "baz"
                        ],
                        $bar,
                        1,
                        "foo"
                    ],
                    [
                        $bar,
                        [
                            "key21" => "bar",
                            "key22" => $foo,
                            "key23" => "baz"
                        ],
                        1
                    ]
                ]
            ],
            "contains-2-multi-flipped-contains" => ["2-element multi dimensional string arrays with same strings but different order",
                [
                    [
                        [
                            "key11" => "foo",
                            "key12" => "bar",
                            "key13" => "baz"
                        ],
                        [
                            "key21" => "bar",
                            "key22" => "foo"
                        ],
                    ],
                    [
                        [
                            "key21" => "bar",
                            "key22" => "foo"
                        ],
                        [
                            "key11" => "foo",
                            "key12" => "bar",
                            "key13" => "baz"
                        ],
                    ]
                ]
            ],

        ];
    }

    private function evaluate(ComperatorInterface $c, $expecteds, $method){
        foreach($this->tests as $id => $test){
            if(!array_key_exists($id,$expecteds)){
                continue;
            }
            $expected = $expecteds[$id];
            $actual = $c->compare($test[1][1],$test[1][0]);

            $info = "$method\n".
                "ID      : $id\n".
                "Test    : ".$test[0]."\n".
                "Data    : ".json_encode($test[1])."\n".
                "Expected: ".($expected?"true":"false")."\n".
                "Actual  : ".($actual?"true":"false")."\n\n";
//            echo $info;
            $this->assertEquals($expected,$actual,$info);
        }
    }

    public function test_ShouldEqualsIgnoreOrder(){

        $base_expecteds = [
            "equals-1-diff-types" => false,
            "equals-1" => true,
            "not-equals-1" => false,
            "equals-2-diff-order" => true,
            "equals-2" => true,
            "equals-1-obj" => true,
            "equals-1-multi" => true,
            "equals-2-multi" => true,
            "equals-2-multi-diff-order" => true,
            "contains-1" => false,
            "not-contains-1" => false,
            "contains-2-diff-order" => false,
            "contains-2" => false,
            "contains-1-obj" => false,
            "contains-1-multi" => false,
            "contains-2-multi" => false,
            "contains-2-multi-diff-order" => false,
        ];

        $expecteds = $base_expecteds;
        ## diff keys should not effect the result
        foreach($expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = $val;
        }
        ## edge cases
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = true;

        $ignoreOrder = true;
        $canContainMixedTypes = true;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_EQUALS,false,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__);


        $expecteds = [];
        ## diff keys should fail every time
        foreach($base_expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = false;
        }
        ## edge cases
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = false;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_EQUALS,true,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__." force key equality");
    }

    public function test_ShouldEqualsNotIgnoreOrder(){

        $base_expecteds = [
            "equals-1-diff-types" => false,
            "equals-1" => true,
            "not-equals-1" => false,
            "equals-2-diff-order" => false,
            "equals-2" => true,
            "equals-1-obj" => true,
            "equals-1-multi" => true,
            "equals-2-multi" => true,
            "equals-2-multi-diff-order" => false,
            "contains-1" => false,
            "not-contains-1" => false,
            "contains-2-diff-order" => false,
            "contains-2" => false,
            "contains-1-obj" => false,
            "contains-1-multi" => false,
            "contains-2-multi" => false,
            "contains-2-multi-diff-order" => false,
        ];

        $expecteds = $base_expecteds;
        ## diff keys should not effect the result
        foreach($expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = $val;
        }
        ## edge cases
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = false;
        $ignoreOrder = false;
        $canContainMixedTypes = true;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_EQUALS,false,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__);


        $expecteds = [];
        ## diff keys should fail every time
        foreach($base_expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = false;
        }
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = false;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_EQUALS,true,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__." force key equality");
    }

    public function test_ShouldContainsIgnoreOrder(){

        $base_expecteds = [
            "equals-1-diff-types" => false,
            "equals-1" => true,
            "not-equals-1" => false,
            "equals-2-diff-order" => true,
            "equals-2" => true,
            "equals-1-obj" => true,
            "equals-1-multi" => true,
            "equals-2-multi" => true,
            "equals-2-multi-diff-order" => true,
            "contains-1" => true,
            "not-contains-1" => false,
            "contains-2-diff-order" => true,
            "contains-2" => true,
            "contains-1-obj" => true,
            "contains-1-multi" => false,
            "contains-2-multi" => false,
            "contains-2-multi-diff-order" => false,
        ];

        $expecteds = $base_expecteds;
        ## diff keys should not effect the result
        foreach($expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = $val;
        }
        $expecteds["contains-2-multi-contains-mixed-types"] = true;
        $expecteds["contains-2-multi-flipped-contains"] = true;
        $ignoreOrder = true;
        $canContainMixedTypes = true;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_CONTAINS,false,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__);


        $expecteds = [];
        ## diff keys should fail every time
        foreach($base_expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = false;
        }
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = false; // because first level arrays have different order -- hence different keys
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_CONTAINS,true,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__." force key equality");
    }

    public function test_ShouldContainsNotIgnoreOrder(){

        $base_expecteds = [
            "equals-1-diff-types" => false,
            "equals-1" => true,
            "not-equals-1" => false,
            "equals-2-diff-order" => false,
            "equals-2" => true,
            "equals-1-obj" => true,
            "equals-1-multi" => true,
            "equals-2-multi" => true,
            "equals-2-multi-diff-order" => false,
            "contains-1" => true,
            "not-contains-1" => false,
            "contains-2-diff-order" => false,
            "contains-2" => true,
            "contains-1-obj" => true,
            "contains-1-multi" => false,
            "contains-2-multi" => false,
            "contains-2-multi-diff-order" => false,
        ];

        $expecteds = $base_expecteds;
        ## diff keys should not effect the result
        foreach($expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = $val;
        }
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = false;
        $ignoreOrder = false;
        $canContainMixedTypes = true;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_CONTAINS,false,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__);


        $expecteds = [];
        ## diff keys should fail every time
        foreach($base_expecteds as $id => $val){
            $expecteds[$id."-diff-keys"] = false;
        }
        $expecteds["contains-2-multi-contains-mixed-types"] = false;
        $expecteds["contains-2-multi-flipped-contains"] = false;
        $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_CONTAINS,true,$ignoreOrder,$canContainMixedTypes,null);
        $this->evaluate($c,$expecteds,__METHOD__." force key equality");
    }

//    public function test_Performance(){
//
//        $revFill = function (&$arr, $level, $items,$rev = false) use(&$revFill) {
//            $level--;
//                for ($i = 0; $i < $items; $i++) {
//                    $inner = [];
//                    if ($level > 1) {
//                        $revFill($inner, $level, $items, $rev);
//                    }else{
//                        $inner = range(0,$items-1);
//                        if($rev){
//                            shuffle($inner);
////                            $inner = array_reverse($inner);
//                        }
//                    }
//                    $arr[$i] = $inner;
//                }
//        };
//        $level = 3;
//        $items = 25;
//        $arr = [];
//        $revFill($arr,$level,$items);
//        $arr2 = [];
//        $revFill($arr2,$level,$items,true);
//        $params = [$arr,$arr2];
//
//        $ignoreOrder = true;
//        $keysMustMatch = false;
//
//        $run = function($ignoreOrder,$keysMustMatch,$params) {
//            echo "Ignore order: ".($ignoreOrder?"true":"false")."\n";
//            echo "Keys must match: ".($keysMustMatch?"true":"false")."\n";
//
//            $c = new ArrayComperatorPerformance(ArrayComperatorPerformance::COMPARE_FUNCTION_EQUALS, $keysMustMatch, $ignoreOrder);
//            $keyCmp = null;
//            if ($keysMustMatch) {
//                $keyCmp = function ($k1, $k2) {
//                    return $k1 === $k2;
//                };
//            }
//            $c2 = new ArrayComperator(ArrayComperatorPerformance::COMPARE_FUNCTION_EQUALS, null, $keyCmp, $ignoreOrder);
//
//            $tests = [
//                "performance" => function ($params) use ($c) {
//                    echo "performance " . $c->compare($params[0], $params[1]) . "\n";
//                },
//                "normal" => function ($params) use ($c2) {
//                    echo "normal " . $c2->compare($params[0], $params[1]) . "\n";
//                },
//            ];
//
//            $runs = 1;
//            $b = new Benchmark($tests, $runs);
//            echo($b->run($params))."\n\n\n";
//        };
//
//        $run(true,true,$params);
//        $run(true,false,$params);
//        $run(false,true,$params);
//        $run(false,false,$params);
//    }
}
 