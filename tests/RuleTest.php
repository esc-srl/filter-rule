<?php

namespace Esc\Tests;

use Esc\Rule;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    public function testToArray()
    {
        $rule = Rule::getInstance('foo', Rule::EQUALS, 'bar');

        $expectedArray = [
            'field' => 'foo',
            'operator' => Rule::EQUALS,
            'value' => 'bar',
        ];

        $this->assertEquals($expectedArray, $rule->toArray());
    }

    /**
     * @param $operator
     * @param $expected
     * @dataProvider operatorWhereDataProvider
     */
    public function testGetWhere($operator, $expected)
    {
        $rule = Rule::getInstance('foo', $operator, 'bar');

        $this->assertEquals($expected, $rule->getWhere());
    }

    /**
     * @param $operator
     * @param $expected
     * @dataProvider operatorWhereParametersDataProvider
     */
    public function testGetWhereParameters($operator, $expected)
    {
        $rule = Rule::getInstance('foo', $operator, 'bar');

        $this->assertEquals($expected, $rule->getWhereParameters());
    }

    public function operatorWhereDataProvider()
    {
        return [
            [Rule::EQUALS, 'foo = ' . Rule::PLACEHOLDER],
            [Rule::MINOR, 'foo < ' . Rule::PLACEHOLDER],
            [Rule::MAJOR, 'foo > ' . Rule::PLACEHOLDER],
            [Rule::MINOREQUALS, 'foo <= ' . Rule::PLACEHOLDER],
            [Rule::MAJOREQUALS, 'foo >= ' . Rule::PLACEHOLDER],
            [Rule::NOT, 'foo <> ' . Rule::PLACEHOLDER],
            [Rule::ISNULL, 'foo IS NULL'],
            [Rule::NOTNULL, 'foo IS NOT NULL'],
            [Rule::CONTAINS, 'foo LIKE ' . Rule::PLACEHOLDER],
            [Rule::BEGINSWITH, 'foo LIKE ' . Rule::PLACEHOLDER],
            [Rule::ENDSWITH, 'foo LIKE ' . Rule::PLACEHOLDER],
            [Rule::NOTCONTAINS, 'foo NOT LIKE ' . Rule::PLACEHOLDER],
            [Rule::NOTBEGINSWITH, 'foo NOT LIKE ' . Rule::PLACEHOLDER],
            [Rule::NOTENDSWITH, 'foo NOT LIKE ' . Rule::PLACEHOLDER],
        ];
    }

    public function operatorWhereParametersDataProvider()
    {
        return [
            [Rule::EQUALS, 'bar'],
            [Rule::MINOR, 'bar'],
            [Rule::MAJOR, 'bar'],
            [Rule::MINOREQUALS, 'bar'],
            [Rule::MAJOREQUALS, 'bar'],
            [Rule::NOT, 'bar'],
            [Rule::ISNULL, null],
            [Rule::NOTNULL, null],
            [Rule::CONTAINS, '%bar%'],
            [Rule::NOTCONTAINS, '%bar%'],
            [Rule::BEGINSWITH, 'bar%'],
            [Rule::NOTBEGINSWITH, 'bar%'],
            [Rule::ENDSWITH, '%bar'],
            [Rule::NOTENDSWITH, '%bar'],
        ];
    }
}
