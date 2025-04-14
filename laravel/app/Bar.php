<?php

namespace App;

class Bar
{
    public function __set($key, $value)
    {
        $this->setProp($key, $value);
    }

    public function setProp($key, $value)
    {
        $this->$key = $value;
    }
}
