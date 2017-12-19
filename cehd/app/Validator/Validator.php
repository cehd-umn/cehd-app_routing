<?php
namespace CEHD\Validator;

require_once __DIR__ . "/../../../cehd/app/Base.php";

use \CEHD\App\BaseClass;

class Validator extends BaseClass
{
    public $name;

    public $type;

    public $required;

    public $value;

    function __construct(string $name, string $type, bool $required)
    {
        $this->name = $name;

        $this->type = $type;

        $this->required = $required;

        $this->value = $value;
    }

    public function isValidType()
    {
        return gettype($this->value) === $this->type;
    }

    public function isType($type)
    {
        return gettype($this->value) === $type;
    }

    public function isArray()
    {
        return $this->isType("array");
    }

    public function isBoolean()
    {
        return $this->isType("boolean");
    }

    public function isBool()
    {
        return $this->isBoolean();
    }

    public function isFloat()
    {
        return $this->isType("float");
    }

    public function isInteger()
    {
        return $this->isType("integer");
    }

    public function isInt()
    {
        return $this->isInteger();
    }

    public function isObject()
    {
        return $this->isType("object");
    }

    public function isString()
    {
        return $this->isType("string");
    }

    public function canBeNull()
    {
        return $this->type === "null";
    }
}
