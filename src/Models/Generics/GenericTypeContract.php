<?php

namespace Juanparati\Podium\Models\Generics;



interface GenericTypeContract
{
    /**
     * Fill value
     *
     * @param mixed $value
     * @return $this
     */
    public function fillProps(mixed $value) : static;


    /**
     * Decoded the value
     *
     * @return mixed
     */
    public function decodeValue() : mixed;


    /**
     * Decoded value for a POST/PUT.
     *
     * @return mixed
     */
    public function decodeValueForPost() : mixed;


    /**
     * Get original value with properties.
     *
     * @return mixed
     */
    public function getProps() : mixed;


    /**
     * Get original value without properties.
     *
     * @return mixed
     */
    public function originalValues() : mixed;


    /**
     * Set options for child models.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options) : static;

}