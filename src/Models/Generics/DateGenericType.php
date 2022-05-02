<?php

namespace Juanparati\Podium\Models\Generics;

use Illuminate\Support\Carbon;

class DateGenericType extends GenericTypeBase
{
    public function decodeValue(): string
    {
        return Carbon::parse($this->value)->toDateString();
    }

    public function __toString()
    {
        return $this->decodeValue();
    }
}