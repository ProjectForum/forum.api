<?php


namespace App\Supports\Entity;

use JsonSerializable;
use Str;
use Validator;

class BaseEntity implements JsonSerializable
{
    protected $_data = [];

    /**
     * 构造时的原始数据
     * @var array
     */
    protected $_originalData = [];

    public function __construct($data = [])
    {
        $this->_originalData = $data;
        $newData = [];
        foreach ($data as $key => $value) {
            $newData[Str::camel($key)] = $value;
        }
        $this->_data = $newData;
    }

    public function &__get($name)
    {
        if (method_exists($this, "_get{$name}")) {
            return $this->{"_get{$name}"}();
        } else if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        } else {
            $result = null;
            return $result;
        }
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __isset($name)
    {
        if (method_exists($this, "_get{$name}")) {
            return true;
        } else if (array_key_exists($name, $this->_data)) {
            return true;
        } else {
            return isset($this->{$name});
        }
    }

    public function jsonSerialize()
    {
        return $this->_data;
    }

    public function getOriginalData()
    {
        return $this->_originalData;
    }

    public function validate(array $rules, array $messages = [], array $customAttributes = [])
    {
        return Validator::validate($this->_data, $rules, $messages, $customAttributes);
    }
}
