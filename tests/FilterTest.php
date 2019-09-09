<?php

namespace Esc\Tests;

use Esc\Filter;
use Esc\Rule;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testToArray()
    {
        $filters = Filter::getInstance();
        $filters->addRule(Rule::getInstance('foo', Rule::EQUALS, 'bar'));

        $filterGroup = Filter::getInstance(Filter::GLUE_OR);
        $filterGroup->addRule(Rule::getInstance('baz', Rule::EQUALS, 'value'));
        $filterGroup->addRule(Rule::getInstance('field1', Rule::EQUALS, 'valuefield1'));


        $filterGroup1 = Filter::getInstance(Filter::GLUE_AND);
        $filterGroup1->addRule(Rule::getInstance('baz', Rule::EQUALS, 'value'));
        $filterGroup1->addRule(Rule::getInstance('field1', Rule::EQUALS, 'valuefield1'));

        $filterGroup->addGroup($filterGroup1);

        $filters->addGroup($filterGroup);

        $expected = [
            'groupOperator' => Filter::GLUE_AND,
            'rules' => [
                [
                    'field' => 'foo',
                    'operator' => Rule::EQUALS,
                    'value' => 'bar',
                ]
            ],
            'groups' => [
                [
                    'groupOperator' => Filter::GLUE_OR,
                    'rules' => [
                        [
                            'field' => 'baz',
                            'operator' => Rule::EQUALS,
                            'value' => 'value',
                        ],
                        [
                            'field' => 'field1',
                            'operator' => Rule::EQUALS,
                            'value' => 'valuefield1',
                        ],
                    ],
                    'groups' => [
                        [
                            'groupOperator' => Filter::GLUE_AND,
                            'rules' => [
                                [
                                    'field' => 'baz',
                                    'operator' => Rule::EQUALS,
                                    'value' => 'value',
                                ],
                                [
                                    'field' => 'field1',
                                    'operator' => Rule::EQUALS,
                                    'value' => 'valuefield1',
                                ],
                            ],
                        ]
                    ]
                ]
            ],
        ];

        $this->assertEquals($expected, $filters->toArray());
    }
}
