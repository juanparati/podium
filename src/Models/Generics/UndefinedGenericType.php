<?php

namespace Juanparati\Podium\Models\Generics;

class UndefinedGenericType extends GenericTypeBase
{
    public function decodeValue() : mixed
    {
        return null;
    }

    public function __toString() {
        return '';
    }
}