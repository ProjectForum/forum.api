<?php


namespace App\Supports\Model;

use Str;

trait CamelCase
{
    protected function keyTransform($key)
    {
        return Str::camel($key);
    }

    public function getAttribute($key)
    {
        return parent::getAttribute(Str::snake($key));
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(Str::snake($key), $value);
    }

    protected function addMutatedAttributesToArray(array $attributes, array $mutatedAttributes)
    {
        foreach ($mutatedAttributes as $key) {
            if (!array_key_exists($this->keyTransform($key), $attributes)) {
                continue;
            }
            $attributes[$this->keyTransform($key)] = $this->mutateAttributeForArray(
                $this->keyTransform($key), $attributes[$this->keyTransform($key)]
            );
        }
        return $attributes;
    }

    public function jsonSerialize()
    {
        $array = [];
        foreach ($this->toArray() as $key => $value) {
            $array[$this->keyTransform($key)] = $value;
        }
        return $array;
    }

    public function toArray()
    {
        $array = [];
        foreach (parent::toArray() as $key => $value) {
            $array[$this->keyTransform($key)] = $value;
        }
        return $array;
    }

    public function toOriginalArray()
    {
        return parent::toArray();
    }
}
