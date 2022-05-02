<?php

namespace Juanparati\Podium\Models\Generics;

class BoolGenericType extends GenericTypeBase
{
    public function decodeValue(): bool
    {
        return (bool) $this->value;
    }


    public function __toString() {
        return (string) $this->value;
    }
}