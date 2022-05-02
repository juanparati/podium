<?php

namespace Juanparati\Podium\Models\ItemFields;


class DurationItemField extends ItemFieldBase
{
    function decodeValue(): ?int
    {
        return isset($this->value['value']) ? (int) $this->value['value'] : null;
    }


    /**
     * Encode value.
     *
     * @param int $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        $this->value['value'] = (int) $value;

        return $this;
    }
}