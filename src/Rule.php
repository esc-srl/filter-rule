<?php

namespace Esc;

final class Rule
{
    public const EQUALS = 'equals';
    public const MINOR = 'minor';
    public const MAJOR = 'major';
    public const MINOREQUALS = 'minorEquals';
    public const MAJOREQUALS = 'majorEquals';
    public const NOT = 'not';
    public const ISNULL = 'isNull';
    public const NOTNULL = 'notNull';
    public const CONTAINS = 'contains';
    public const NOTCONTAINS = 'notContains';
    public const BEGINSWITH = 'beginsWith';
    public const NOTBEGINSWITH = 'notBeginsWith';
    public const ENDSWITH = 'endsWith';
    public const NOTENDSWITH = 'notEndsWith';

    public const PLACEHOLDER = '?';

    private $field;

    private $value;

    private $operator;

    /**
     * Rule constructor.
     * @param string $field
     * @param $value
     * @param string $operator
     */
    private function __construct(string $field, string $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @param string $field
     * @param $value
     * @param string $operator
     * @return Rule
     */
    public static function getInstance(string $field, string $operator, $value = null): Rule
    {
        return new self($field, $operator, $value);
    }

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'operator' => $this->operator,
            'value' => $this->value,
        ];
    }

    public function getWhere(): string
    {
        switch ($this->operator) {
            case self::EQUALS:
                return $this->field . ' = ' . self::PLACEHOLDER;
                break;
            case self::MINOR:
                return $this->field . ' < ' . self::PLACEHOLDER;
                break;
            case self::MAJOR:
                return $this->field . ' > ' . self::PLACEHOLDER;
                break;
            case self::MINOREQUALS:
                return $this->field . ' <= ' . self::PLACEHOLDER;
                break;
            case self::MAJOREQUALS:
                return $this->field . ' >= ' . self::PLACEHOLDER;
                break;
            case self::NOT:
                return $this->field . ' <> ' . self::PLACEHOLDER;
                break;
            case self::ISNULL:
                return $this->field . ' IS NULL';
                break;
            case self::NOTNULL:
                return $this->field . ' IS NOT NULL';
                break;
            case self::CONTAINS:
            case self::BEGINSWITH:
            case self::ENDSWITH:
                return $this->field . ' LIKE ' . self::PLACEHOLDER;
                break;
            case self::NOTCONTAINS:
            case self::NOTBEGINSWITH:
            case self::NOTENDSWITH:
                return $this->field . ' NOT LIKE ' . self::PLACEHOLDER;
                break;
            default:
                return $this->field . ' = ' . self::PLACEHOLDER;
        }
    }

    public function getWhereParameters(): ?string
    {
        switch ($this->operator) {
            case self::EQUALS:
            case self::MINOR:
            case self::MAJOR:
            case self::MINOREQUALS:
            case self::MAJOREQUALS:
            case self::NOT:
                return $this->value;
                break;
            case self::ISNULL:
            case self::NOTNULL:
                return null;
                break;
            case self::CONTAINS:
            case self::NOTCONTAINS:
                return '%' . $this->value . '%';
                break;
            case self::BEGINSWITH:
            case self::NOTBEGINSWITH:
                return $this->value . '%';
                break;
            case self::ENDSWITH:
            case self::NOTENDSWITH:
                return  '%' . $this->value;
                break;
            default:
                return null;
        }
    }
}
