<?php

namespace Juanparati\Podium\Models\ItemFields;

interface ItemFieldContract
{

    /**
     * Set item field config.
     *
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config) : static;


    /**
     * Encode value.
     *
     * @param mixed $value
     * @return $this
     */
    public function encodeValue(mixed $value) : static;
}