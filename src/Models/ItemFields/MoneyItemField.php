<?php

namespace Juanparati\Podium\Models\ItemFields;

use Juanparati\Podium\Exceptions\DataIntegrityException;

class MoneyItemField extends ItemFieldBase
{


    /**
     * Original value.
     *
     * @var array
     */
    protected mixed $value = [
        'currency' => null,
        'value'    => null
    ];


    /**
     * Fill value.
     *
     * @param mixed $value
     * @return $this
     * @throws DataIntegrityException
     */
    public function fillProps(mixed $value): static
    {
        $value['currency'] = strtoupper($value['currency']);

        if (!empty($this->config['settings']['allowed_currencies'])
            && !in_array($value['currency'],  $this->config['settings']['allowed_currencies'])
        ) {
            throw new DataIntegrityException('Currency is not accepted', 1);
        }

        return parent::fillProps($value);
    }


    function decodeValue(): array
    {
        $value = $this->value['value'] ?? null;

        return [
            'currency' => $this->value['currency'],
            'value'    => $value === null ? null : (float) $value,
        ];
    }


    /**
     * Encode value.
     *
     * @param array $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        $this->value['currency'] = strtoupper($value['currency']);
        $this->value['value'] = $value['value'] === null ? null : (float) $value['value'];

        return $this;
    }
}