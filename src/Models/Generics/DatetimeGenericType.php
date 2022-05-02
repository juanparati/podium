<?php

namespace Juanparati\Podium\Models\Generics;

use Illuminate\Support\Carbon;

class DatetimeGenericType extends GenericTypeBase
{
    public function decodeValue(): string
    {
        return Carbon::parse($this->value)->toDateTimeString();
    }

    public function __toString(): string
    {
        return $this->decodeValue();
    }
}