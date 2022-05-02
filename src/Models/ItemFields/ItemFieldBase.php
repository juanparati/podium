<?php

namespace Juanparati\Podium\Models\ItemFields;

use Juanparati\Podium\Models\Generics\GenericTypeBase;

abstract class ItemFieldBase extends GenericTypeBase implements ItemFieldContract {

    /**
     * Field settings.
     *
     * @var array
     */
    protected array $config = [];


    /**
     * ItemField options.
     *
     * @var array
     */
    protected array $options = [];


    public function __construct(mixed $value = null) {
        if ($value !== null)
            $this->encodeValue($value);
    }


    /**
     * Set field config.
     *
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config): static
    {
        $this->config = $config;

        return $this;
    }


    /**
     * Get field config.
     *
     * @param array $config
     * @return array
     */
    public function getConfig(array $config) : array {
        return $this->config;
    }


    /**
     * Encode value.
     *
     * @param mixed $value
     * @return $this
     */
    abstract public function encodeValue(mixed $value) : static;
}