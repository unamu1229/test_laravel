<?php

namespace App;

class Baz
{
    private int $baz;

    public function __construct(string $baz)
    {
        $this->baz = $baz;
    }

    public function __get($key)
    {
        return $this->baz;
    }
}
