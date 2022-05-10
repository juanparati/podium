<?php

namespace Juanparati\Podium\Models\ItemFields;

class ImageItemField extends ItemFieldBase
{
    public function decodeValue(): ?array
    {
        return $this->value['value'] ?? null;
    }

    public function encodeValue(mixed $value): static
    {
        $this->value['value'] = $value;

        return $this;
    }

    public function decodeValueForPost(): mixed
    {
        return $this->value['value']['file_id'] ?? null;
    }
}