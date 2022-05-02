<?php

namespace Juanparati\Podium\Models\Generics;

class IntGenericType extends GenericTypeBase
{
    public function decodeValue(): int
    {
        return (int) $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}