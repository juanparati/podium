<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Podium;

interface ModelContract
{
    /**
     * Default attributes.
     *
     * @param array $attr
     * @param Podium|null $podium
     */
    public function __construct(array $attr = [], ?Podium $podium = null);


    /**
     * Initialize relations.
     *
     * @return void
     */
    public function init() : void;


    /**
     * Set property value.
     *
     * @param string $prop
     * @param $value
     * @return $this
     */
    public function setPropValue(string $prop, $value) : static;


    /**
     * Get property value.
     *
     * @param string $prop
     * @return mixed
     */
    public function getPropValue(string $prop) : mixed;


    /**
     * A way to inject a Podium sessions.
     *
     * @param Podium $podium
     * @return $this
     */
    public function injectPodiumInstance(Podium $podium) : static;


    /**
     * Getter.
     *
     * @param string $prop
     * @return mixed
     */
    public function __get(string $prop) : mixed;


    /**
     * Reset model.
     *
     * @return void
     */
    public function reset() : static;
}