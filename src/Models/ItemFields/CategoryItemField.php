<?php

namespace Juanparati\Podium\Models\ItemFields;


class CategoryItemField extends ItemFieldBase
{
    public function decodeValue(): ?array
    {
        return $this->value['value'] ?? null;
    }

    /**
     * Encode value.
     *
     * @param string $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        $this->value = ['value' => $value];

        return $this;
    }
}