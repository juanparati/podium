<?php

namespace Juanparati\Podium\Models\Generics;

class FloatGenericType extends GenericTypeBase
{
    public function decodeValue(): float
    {
        return (float) $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}