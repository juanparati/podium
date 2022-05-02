<?php

namespace Juanparati\Podium\Models\ItemFields;


class LocationItemField extends ItemFieldBase
{
    public function decodeValue(): mixed
    {
        return $this->value;
    }


    public function encodeValue(mixed $value): static
    {
        $this->value = $value;
    }
}