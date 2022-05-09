<?php

namespace Juanparati\Podium\Models\ItemFields;

class AppItemField extends ItemFieldBase
{

    function decodeValue(): mixed
    {
        return $this->value['value'] ?? null;
    }

    public function encodeValue(mixed $value): static
    {
        $this->value['value'] = $value;

        return $this;
    }
}