<?php

namespace Juanparati\Podium\Models\ItemFields;


class NumberItemField extends ItemFieldBase
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $config = [
        'settings' => [
            'decimals' => 0
        ]
    ];


    /**
     * Set decimals.
     *
     * @param int $decimals
     * @return $this
     */
    public function setDecimals(int $decimals) : static {
        $this->config['settings']['decimals'] = $decimals;

        return $this;
    }


    /**
     * Get decimals.
     *
     * @return int
     */
    public function getDecimals() : int {
        return $this->config['settings']['decimals'] ?? 0;
    }


    public function decodeValue(): float|int|null
    {
        if (!isset($this->value['value']))
            return null;

        if ($decimals = $this->getDecimals())
            return round($this->value['value'], $decimals);

        return (int) $this->value['value'];
    }


    /**
     * Encode value.
     *
     * @param float|int $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        $this->value['value'] = $value;

        return $this;
    }


    /**
     * Decode value for POST/PUT operation.
     *
     * @return mixed
     */
    public function decodeValueForPost(): mixed
    {
        return $this->value['value'] ?? null;
    }
}