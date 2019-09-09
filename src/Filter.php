<?php

namespace Esc;

final class Filter
{
    public const GLUE_AND = 'AND';
    public const GLUE_OR = 'OR';

    private $groups = [];

    private $rules = [];

    private $glue;

    /**
     * Filter constructor.
     * @param string $glue
     */
    private function __construct(string $glue)
    {
        $this->glue = $glue;
    }

    /**
     * @param string $glue
     * @return Filter
     */
    public static function getInstance(string $glue = self::GLUE_AND): self
    {
        return new self($glue);
    }

    public function addRule(Rule $rule): void
    {
        $this->rules[] = $rule;
    }

    public function addGroup(Filter $groups): void
    {
        $this->groups[] = $groups;
    }

    public function toArray(): array
    {
        $filters = [];

        $rules = [];
        $subFilters = [];

        foreach ($this->rules as $rule) {
            $rules[] = $rule->toArray();
        }

        if (!empty($rules)) {
            $filters['groupOperator'] = $this->glue;
            $filters['rules'] = $rules;
        }

        //subGroups

        foreach ($this->groups as $group) {
            $subFilters[] = $group->toArray();
        }

        if (!empty($subFilters)) {
            $filters['groupOperator'] = $this->glue;
            $filters['groups'] = $subFilters;
        }

        return $filters;
    }

    /**
     * @return string
     */
    public function getWhere(): string
    {
        $whereArray = [];

        $groupOp = $this->glue;

        /** @var Rule $rule */
        foreach ($this->rules as $rule) {
            $ruleWhere = $rule->getWhere();
            if (!empty($ruleWhere)) {
                $whereArray[] = $ruleWhere;
            }
        }

        /** @var Filter $group */
        foreach ($this->groups as $group) {
            $tempWhere = $group->getWhere();
            $whereArray[] = '(' . $tempWhere . ')';
        }

        $glue = " $groupOp ";
        return implode($glue, $whereArray);
    }

    /**
     * @return array $whereVal
     */
    public function getWhereParameters(): array
    {
        $whereParameters = [];

        /** @var Rule $rule */
        foreach ($this->rules as $rule) {
            $whereParameters[] = $rule->getWhereParameters();
        }

        /** @var Filter $group */
        foreach ($this->groups as $group) {
            $subWhereParameters = $group->getWhereParameters();

            foreach ($subWhereParameters as $whereParameter) {
                $whereParameters[] = $whereParameter;
            }
        }

        return array_filter($whereParameters, static function ($whereParameter) {
            return $whereParameter !== null;
        });
    }
}
