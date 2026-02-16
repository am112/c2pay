<?php

namespace Am112\C2pay\Facades;

use Illuminate\Support\Facades\Facade;

class C2pay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Am112\C2pay\C2pay::class;
    }
}