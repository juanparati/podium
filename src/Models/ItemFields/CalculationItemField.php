<?php

namespace Juanparati\Podium\Models\ItemFields;


use Juanparati\Podium\Exceptions\DataIntegrityException;

class CalculationItemField extends ItemFieldBase
{
    public function decodeValue(): mixed
    {
        return $this->value['value'] ?? null;
    }

    public function encodeValue(mixed $value): static
    {
        throw new DataIntegrityException('Calculation fields are read-only');
    }

    public function decodeValueForPost(): mixed
    {
        return null;
    }
}