<?php

namespace Juanparati\Podium\Models\Generics;

class RawGenericType extends GenericTypeBase
{
    public function decodeValue(): mixed
    {
        return $this->value;
    }

    public function __toString() {
        return json_encode($this->value);
    }
}