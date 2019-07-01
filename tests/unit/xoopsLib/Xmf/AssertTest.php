<?php

/*
 * This file is part of the webmozart/assert package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Bernhard Schussek
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Xmf\Test;

use ArrayIterator;
use Error;
use Exception;
use LogicException;
use RuntimeException;
use stdClass;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class AssertTest extends \PHPUnit\Framework\TestCase
{
    private static $resource;

    public static function getResource()
    {
        if (!static::$resource) {
            static::$resource = fopen(__FILE__, 'rb');
        }

        return static::$resource;
    }

    public static function tearDownAfterClass()
    {
        @fclose(self::$resource);
    }

    public function getTests()
    {
        $resource = self::getResource();

        return [
            ['string', ['value'], true],
            ['string', [''], true],
            ['string', [1234], false],
            ['stringNotEmpty', ['value'], true],
            ['stringNotEmpty', [''], false],
            ['stringNotEmpty', [1234], false],
            ['integer', [123], true],
            ['integer', ['123'], false],
            ['integer', [1.0], false],
            ['integer', [1.23], false],
            ['integerish', [1.0], true],
            ['integerish', [1.23], false],
            ['integerish', [123], true],
            ['integerish', ['123'], true],
            ['float', [1.0], true],
            ['float', [1.23], true],
            ['float', [123], false],
            ['float', ['123'], false],
            ['numeric', [1.0], true],
            ['numeric', [1.23], true],
            ['numeric', [123], true],
            ['numeric', ['123'], true],
            ['numeric', ['foo'], false],
            ['boolean', [true], true],
            ['boolean', [false], true],
            ['boolean', [1], false],
            ['boolean', ['1'], false],
            ['scalar', ['1'], true],
            ['scalar', [123], true],
            ['scalar', [true], true],
            ['scalar', [null], false],
            ['scalar', [[]], false],
            ['scalar', [new stdClass()], false],
            ['object', [new stdClass()], true],
            ['object', [new RuntimeException()], true],
            ['object', [null], false],
            ['object', [true], false],
            ['object', [1], false],
            ['object', [[]], false],
            ['resource', [$resource], true],
            ['resource', [$resource, 'stream'], true],
            ['resource', [$resource, 'other'], false],
            ['resource', [1], false],
            ['isCallable', ['strlen'], true],
            ['isCallable', [[$this, 'getTests']], true],
            ['isCallable', [function () {}], true],
            ['isCallable', [1234], false],
            ['isCallable', ['foobar'], false],
            ['isArray', [[]], true],
            ['isArray', [[1, 2, 3]], true],
            ['isArray', [new ArrayIterator([])], false],
            ['isArray', [123], false],
            ['isArray', [new stdClass()], false],
            ['isTraversable', [[]], true],
            ['isTraversable', [[1, 2, 3]], true],
            ['isTraversable', [new ArrayIterator([])], true],
            ['isTraversable', [123], false],
            ['isTraversable', [new stdClass()], false],
            ['isInstanceOf', [new stdClass(), 'stdClass'], true],
            ['isInstanceOf', [new Exception(), 'stdClass'], false],
            ['isInstanceOf', [123, 'stdClass'], false],
            ['isInstanceOf', [[], 'stdClass'], false],
            ['notInstanceOf', [new stdClass(), 'stdClass'], false],
            ['notInstanceOf', [new Exception(), 'stdClass'], true],
            ['notInstanceOf', [123, 'stdClass'], true],
            ['notInstanceOf', [[], 'stdClass'], true],
            ['true', [true], true],
            ['true', [false], false],
            ['true', [1], false],
            ['true', [null], false],
            ['false', [false], true],
            ['false', [true], false],
            ['false', [1], false],
            ['false', [0], false],
            ['false', [null], false],
            ['null', [null], true],
            ['null', [false], false],
            ['null', [0], false],
            ['notNull', [false], true],
            ['notNull', [0], true],
            ['notNull', [null], false],
            ['isEmpty', [null], true],
            ['isEmpty', [false], true],
            ['isEmpty', [0], true],
            ['isEmpty', [''], true],
            ['isEmpty', [1], false],
            ['isEmpty', ['a'], false],
            ['notEmpty', [1], true],
            ['notEmpty', ['a'], true],
            ['notEmpty', [null], false],
            ['notEmpty', [false], false],
            ['notEmpty', [0], false],
            ['notEmpty', [''], false],
            ['eq', [1, 1], true],
            ['eq', [1, '1'], true],
            ['eq', [1, true], true],
            ['eq', [1, 0], false],
            ['notEq', [1, 0], true],
            ['notEq', [1, 1], false],
            ['notEq', [1, '1'], false],
            ['notEq', [1, true], false],
            ['same', [1, 1], true],
            ['same', [1, '1'], false],
            ['same', [1, true], false],
            ['same', [1, 0], false],
            ['notSame', [1, 0], true],
            ['notSame', [1, 1], false],
            ['notSame', [1, '1'], true],
            ['notSame', [1, true], true],
            ['greaterThan', [1, 0], true],
            ['greaterThan', [0, 0], false],
            ['greaterThanEq', [2, 1], true],
            ['greaterThanEq', [1, 1], true],
            ['greaterThanEq', [0, 1], false],
            ['lessThan', [0, 1], true],
            ['lessThan', [1, 1], false],
            ['lessThanEq', [0, 1], true],
            ['lessThanEq', [1, 1], true],
            ['lessThanEq', [2, 1], false],
            ['range', [1, 1, 2], true],
            ['range', [2, 1, 2], true],
            ['range', [0, 1, 2], false],
            ['range', [3, 1, 2], false],
            ['oneOf', [1, [1, 2, 3]], true],
            ['oneOf', [1, ['1', '2', '3']], false],
            ['contains', ['abcd', 'ab'], true],
            ['contains', ['abcd', 'bc'], true],
            ['contains', ['abcd', 'cd'], true],
            ['contains', ['abcd', 'de'], false],
            ['contains', ['', 'de'], false],
            ['startsWith', ['abcd', 'ab'], true],
            ['startsWith', ['abcd', 'bc'], false],
            ['startsWith', ['', 'bc'], false],
            ['startsWithLetter', ['abcd'], true],
            ['startsWithLetter', ['1abcd'], false],
            ['startsWithLetter', [''], false],
            ['endsWith', ['abcd', 'cd'], true],
            ['endsWith', ['abcd', 'bc'], false],
            ['endsWith', ['', 'bc'], false],
            ['regex', ['abcd', '~^ab~'], true],
            ['regex', ['abcd', '~^bc~'], false],
            ['regex', ['', '~^bc~'], false],
            ['alpha', ['abcd'], true],
            ['alpha', ['ab1cd'], false],
            ['alpha', [''], false],
            ['digits', ['1234'], true],
            ['digits', ['12a34'], false],
            ['digits', [''], false],
            ['alnum', ['ab12'], true],
            ['alnum', ['ab12$'], false],
            ['alnum', [''], false],
            ['lower', ['abcd'], true],
            ['lower', ['abCd'], false],
            ['lower', ['ab_d'], false],
            ['lower', [''], false],
            ['upper', ['ABCD'], true],
            ['upper', ['ABcD'], false],
            ['upper', ['AB_D'], false],
            ['upper', [''], false],
            ['length', ['abcd', 4], true],
            ['length', ['abc', 4], false],
            ['length', ['abcde', 4], false],
            ['length', ['äbcd', 4], true, true],
            ['length', ['äbc', 4], false, true],
            ['length', ['äbcde', 4], false, true],
            ['minLength', ['abcd', 4], true],
            ['minLength', ['abcde', 4], true],
            ['minLength', ['abc', 4], false],
            ['minLength', ['äbcd', 4], true, true],
            ['minLength', ['äbcde', 4], true, true],
            ['minLength', ['äbc', 4], false, true],
            ['maxLength', ['abcd', 4], true],
            ['maxLength', ['abc', 4], true],
            ['maxLength', ['abcde', 4], false],
            ['maxLength', ['äbcd', 4], true, true],
            ['maxLength', ['äbc', 4], true, true],
            ['maxLength', ['äbcde', 4], false, true],
            ['lengthBetween', ['abcd', 3, 5], true],
            ['lengthBetween', ['abc', 3, 5], true],
            ['lengthBetween', ['abcde', 3, 5], true],
            ['lengthBetween', ['ab', 3, 5], false],
            ['lengthBetween', ['abcdef', 3, 5], false],
            ['lengthBetween', ['äbcd', 3, 5], true, true],
            ['lengthBetween', ['äbc', 3, 5], true, true],
            ['lengthBetween', ['äbcde', 3, 5], true, true],
            ['lengthBetween', ['äb', 3, 5], false, true],
            ['lengthBetween', ['äbcdef', 3, 5], false, true],
            ['fileExists', [__FILE__], true],
            ['fileExists', [__DIR__], true],
            ['fileExists', [__DIR__ . '/foobar'], false],
            ['file', [__FILE__], true],
            ['file', [__DIR__], false],
            ['file', [__DIR__ . '/foobar'], false],
            ['directory', [__DIR__], true],
            ['directory', [__FILE__], false],
            ['directory', [__DIR__ . '/foobar'], false],
            // no tests for readable()/writable() for now
            ['classExists', [__CLASS__], true],
            ['classExists', [__NAMESPACE__ . '\Foobar'], false],
            ['subclassOf', [__CLASS__, 'PHPUnit\Framework\TestCase'], true],
            ['subclassOf', [__CLASS__, 'stdClass'], false],
            ['implementsInterface', ['ArrayIterator', 'Traversable'], true],
            ['implementsInterface', [__CLASS__, 'Traversable'], false],
            ['propertyExists', [(object) ['property' => 0], 'property'], true],
            ['propertyExists', [(object) ['property' => null], 'property'], true],
            ['propertyExists', [(object) ['property' => null], 'foo'], false],
            ['propertyNotExists', [(object) ['property' => 0], 'property'], false],
            ['propertyNotExists', [(object) ['property' => null], 'property'], false],
            ['propertyNotExists', [(object) ['property' => null], 'foo'], true],
            ['methodExists', ['RuntimeException', 'getMessage'], true],
            ['methodExists', [new RuntimeException(), 'getMessage'], true],
            ['methodExists', ['stdClass', 'getMessage'], false],
            ['methodExists', [new stdClass(), 'getMessage'], false],
            ['methodExists', [null, 'getMessage'], false],
            ['methodExists', [true, 'getMessage'], false],
            ['methodExists', [1, 'getMessage'], false],
            ['methodNotExists', ['RuntimeException', 'getMessage'], false],
            ['methodNotExists', [new RuntimeException(), 'getMessage'], false],
            ['methodNotExists', ['stdClass', 'getMessage'], true],
            ['methodNotExists', [new stdClass(), 'getMessage'], true],
            ['methodNotExists', [null, 'getMessage'], true],
            ['methodNotExists', [true, 'getMessage'], true],
            ['methodNotExists', [1, 'getMessage'], true],
            ['keyExists', [['key' => 0], 'key'], true],
            ['keyExists', [['key' => null], 'key'], true],
            ['keyExists', [['key' => null], 'foo'], false],
            ['keyNotExists', [['key' => 0], 'key'], false],
            ['keyNotExists', [['key' => null], 'key'], false],
            ['keyNotExists', [['key' => null], 'foo'], true],
            ['count', [[0, 1, 2], 3], true],
            ['count', [[0, 1, 2], 2], false],
            ['uuid', ['00000000-0000-0000-0000-000000000000'], true],
            ['uuid', ['ff6f8cb0-c57d-21e1-9b21-0800200c9a66'], true],
            ['uuid', ['ff6f8cb0-c57d-11e1-9b21-0800200c9a66'], true],
            ['uuid', ['ff6f8cb0-c57d-31e1-9b21-0800200c9a66'], true],
            ['uuid', ['ff6f8cb0-c57d-41e1-9b21-0800200c9a66'], true],
            ['uuid', ['ff6f8cb0-c57d-51e1-9b21-0800200c9a66'], true],
            ['uuid', ['FF6F8CB0-C57D-11E1-9B21-0800200C9A66'], true],
            ['uuid', ['zf6f8cb0-c57d-11e1-9b21-0800200c9a66'], false],
            ['uuid', ['af6f8cb0c57d11e19b210800200c9a66'], false],
            ['uuid', ['ff6f8cb0-c57da-51e1-9b21-0800200c9a66'], false],
            ['uuid', ['af6f8cb-c57d-11e1-9b21-0800200c9a66'], false],
            ['uuid', ['3f6f8cb0-c57d-11e1-9b21-0800200c9a6'], false],
            ['throws', [function () { throw new LogicException('test'); }, 'LogicException'], true],
            ['throws', [function () { throw new LogicException('test'); }, 'IllogicException'], false],
            ['throws', [function () { throw new Exception('test'); }], true],
            //array('throws', array(function() { trigger_error('test'); }, 'Throwable'), true, false, 70000),
            ['throws', [function () { trigger_error('test'); }, 'Unthrowable'], false, false, 70000],
            ['throws', [function () { throw new Error(); }, 'Throwable'], true, true, 70000],
        ];
    }

    public function getMethods()
    {
        $methods = [];

        foreach ($this->getTests() as $params) {
            $methods[$params[0]] = [$params[0]];
        }

        return array_values($methods);
    }

    /**
     * @dataProvider getTests
     * @param mixed $method
     * @param mixed $args
     * @param mixed $success
     * @param mixed $multibyte
     * @param null|mixed $minVersion
     */
    public function testAssert($method, $args, $success, $multibyte = false, $minVersion = null)
    {
        if ($minVersion && $minVersion > PHP_VERSION_ID) {
            $this->markTestSkipped(sprintf('This test requires php %s or upper.', $minVersion));

            return;
        }
        if ($multibyte && !function_exists('mb_strlen')) {
            $this->markTestSkipped('The function mb_strlen() is not available');

            return;
        }

        if (!$success) {
            $this->expectException('\InvalidArgumentException');
        }

        call_user_func_array(['Xmf\Assert', $method], $args);
        $this->assertTrue(true, 'Return type ensures this assertion is never reached on failure');
    }

    /**
     * @dataProvider getTests
     * @param mixed $method
     * @param mixed $args
     * @param mixed $success
     * @param mixed $multibyte
     * @param null|mixed $minVersion
     */
    public function testNullOr($method, $args, $success, $multibyte = false, $minVersion = null)
    {
        if ($minVersion && $minVersion > PHP_VERSION_ID) {
            $this->markTestSkipped(sprintf('This test requires php %s or upper.', $minVersion));

            return;
        }
        if ($multibyte && !function_exists('mb_strlen')) {
            $this->markTestSkipped('The function mb_strlen() is not available');

            return;
        }

        if (!$success && null !== reset($args)) {
            $this->expectException('\InvalidArgumentException');
        }

        call_user_func_array(['Xmf\Assert', 'nullOr' . ucfirst($method)], $args);
        $this->assertTrue(true, 'Return type ensures this assertion is never reached on failure');
    }

    /**
     * @dataProvider getMethods
     * @param mixed $method
     */
    public function testNullOrAcceptsNull($method)
    {
        call_user_func(['Xmf\Assert', 'nullOr' . ucfirst($method)], null);
        $this->assertTrue(true, 'Return type ensures this assertion is never reached on failure');
    }

    /**
     * @dataProvider getTests
     * @param mixed $method
     * @param mixed $args
     * @param mixed $success
     * @param mixed $multibyte
     * @param null|mixed $minVersion
     */
    public function testAllArray($method, $args, $success, $multibyte = false, $minVersion = null)
    {
        if ($minVersion && $minVersion > PHP_VERSION_ID) {
            $this->markTestSkipped(sprintf('This test requires php %s or upper.', $minVersion));

            return;
        }
        if ($multibyte && !function_exists('mb_strlen')) {
            $this->markTestSkipped('The function mb_strlen() is not available');

            return;
        }

        if (!$success) {
            $this->expectException('\InvalidArgumentException');
        }

        $arg = array_shift($args);
        array_unshift($args, [$arg]);

        call_user_func_array(['Xmf\Assert', 'all' . ucfirst($method)], $args);
        $this->assertTrue(true, 'Return type ensures this assertion is never reached on failure');
    }

    /**
     * @dataProvider getTests
     * @param mixed $method
     * @param mixed $args
     * @param mixed $success
     * @param mixed $multibyte
     * @param null|mixed $minVersion
     */
    public function testAllTraversable($method, $args, $success, $multibyte = false, $minVersion = null)
    {
        if ($minVersion && $minVersion > PHP_VERSION_ID) {
            $this->markTestSkipped(sprintf('This test requires php %s or upper.', $minVersion));

            return;
        }
        if ($multibyte && !function_exists('mb_strlen')) {
            $this->markTestSkipped('The function mb_strlen() is not available');

            return;
        }

        if (!$success) {
            $this->expectException('\InvalidArgumentException');
        }

        $arg = array_shift($args);
        array_unshift($args, new ArrayIterator([$arg]));

        call_user_func_array(['Xmf\Assert', 'all' . ucfirst($method)], $args);
        $this->assertTrue(true, 'Return type ensures this assertion is never reached on failure');
    }

    public function getStringConversions()
    {
        return [
            ['integer', ['foobar'], 'Expected an integer. Got: string'],
            ['string', [1], 'Expected a string. Got: integer'],
            ['string', [true], 'Expected a string. Got: boolean'],
            ['string', [null], 'Expected a string. Got: NULL'],
            ['string', [[]], 'Expected a string. Got: array'],
            ['string', [new stdClass()], 'Expected a string. Got: stdClass'],
            ['string', [self::getResource()], 'Expected a string. Got: resource'],

            ['eq', ['1', '2'], 'Expected a value equal to "2". Got: "1"'],
            ['eq', [1, 2], 'Expected a value equal to 2. Got: 1'],
            ['eq', [true, false], 'Expected a value equal to false. Got: true'],
            ['eq', [true, null], 'Expected a value equal to null. Got: true'],
            ['eq', [null, true], 'Expected a value equal to true. Got: null'],
            ['eq', [[1], [2]], 'Expected a value equal to array. Got: array'],
            ['eq', [new ArrayIterator([]), new stdClass()], 'Expected a value equal to stdClass. Got: ArrayIterator'],
            ['eq', [1, self::getResource()], 'Expected a value equal to resource. Got: 1'],
        ];
    }

    /**
     * @dataProvider getStringConversions
     * @param mixed $method
     * @param mixed $args
     * @param mixed $exceptionMessage
     */
    public function testConvertValuesToStrings($method, $args, $exceptionMessage)
    {
        $this->expectException('\InvalidArgumentException', $exceptionMessage);

        call_user_func_array(['Xmf\Assert', $method], $args);
    }
}
