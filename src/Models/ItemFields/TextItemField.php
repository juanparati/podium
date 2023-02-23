<?php

namespace Juanparati\Podium\Models\ItemFields;


class TextItemField extends ItemFieldBase
{

    public function decodeValue(): ?string
    {
        $value = $this->value['value'] ?? ($this->value[0]['value'] ?? null);

        if (!$value)
            return null;

        return $value;
    }


    /**
     * Encode value.
     *
     * @param mixed $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        $this->value['value'] = $value;

        return $this;
    }
}