<?php

namespace Juanparati\Podium\Models\ItemFields;


use Illuminate\Support\Arr;

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
        if ($value === null) {
            $this->value = [];
        } else if (!is_array($value)) {
            $this->value = Arr::first(
                $this->getConfig('settings.options', []),
                fn($e) => $e['text'] === $value
            );
        } else
            $this->value = ['value' => $value];

        return $this;
    }
}