<?php

namespace Juanparati\Podium\Models\ItemFields;


class TextItemField extends ItemFieldBase
{

    /**
     * Text formats
     */
    const FORMAT_PLAIN = 'plain';
    const FORMAT_HTML = 'html';


    /**
     * Text format.
     *
     * @var string
     */
    protected string $format = self::FORMAT_PLAIN;


    public function setFormat(string $format) : static {
        $this->format = $format;

        return $this;
    }


    public function getFormat() : string {
        return $this->format;
    }


    public function decodeValue(): ?string
    {
        $value = $this->value['value'] ?? null;

        if (!$value)
            return null;

        if ($this->format === self::FORMAT_PLAIN) {
            return strip_tags($value);
        }

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