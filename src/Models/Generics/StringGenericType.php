<?php

namespace Juanparati\Podium\Models\Generics;

class StringGenericType extends GenericTypeBase
{
    public function decodeValue(): string
    {
        return (string) $this->value;
    }

    public function __toString() {
        return $this->decodeValue();
    }
}