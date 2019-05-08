<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    public function getAttribute($key)
    {
        return parent::getAttribute(Str::snake($key));
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(Str::snake($key), $value);
    }

    public function jsonSerialize()
    {
        $array = [];
        foreach ($this->toArray() as $key => $value) {
            $array[Str::camel($key)] = $value;
        }
        return $array;
    }
}
