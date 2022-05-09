<?php

namespace Juanparati\Podium\Models\ItemFields;

use Illuminate\Support\Arr;
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
     * @param string|null $key
     * @param null $default
     * @return array
     */
    public function getConfig(?string $key = null, $default = null) : array {
        return Arr::get($this->config, $key, $default);
    }


    /**
     * Encode value.
     *
     * @param mixed $value
     * @return $this
     */
    abstract public function encodeValue(mixed $value) : static;
}