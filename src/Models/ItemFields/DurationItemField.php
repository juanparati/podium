<?php

namespace Juanparati\Podium\Models\ItemFields;


class DurationItemField extends ItemFieldBase
{
    function decodeValue(): ?int
    {
        $value = $this->value['value'] ?? ($this->value[0]['value'] ?? null);
        return $value ? (int) $value : null;
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