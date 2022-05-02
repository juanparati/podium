<?php

namespace Juanparati\Podium\Models\ItemFields;

class PhoneItemField extends ItemFieldBase
{
    public function decodeValue(): array
    {
        return $this->value;
    }


    /**
     * Encode value.
     *
     * @param string $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }
}