<?php

namespace Juanparati\Podium\Models\Generics;


abstract class GenericTypeBase implements GenericTypeContract
{

    /**
     * Original value.
     *
     * @var mixed|null
     */
    protected mixed $value = null;


    /**
     * Generic options.
     *
     * @var array
     */
    protected array $options = [];


    /**
     * Fill value.
     *
     * @param mixed $value
     * @return static
     */
    public function fillProps(mixed $value) : static {
        $this->value = $value;

        return $this;
    }


    /**
     * Decoded value.
     *
     * @return mixed
     */
    abstract function decodeValue() : mixed;


    /**
     * Original value.
     *
     * @return mixed
     */
    public function getProps(): mixed
    {
        return $this->value;
    }


    /**
     * Set options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): static
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }


    public function originalValues() : mixed
    {
        return $this->value;
    }
}