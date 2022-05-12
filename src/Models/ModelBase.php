<?php

namespace Juanparati\Podium\Models;

use Illuminate\Support\Arr;
use Juanparati\Podium\Models\Generics\GenericTypeContract;
use Juanparati\Podium\Podium;
use Juanparati\Podium\Requests\RequestBase;

abstract class ModelBase implements ModelContract, GenericTypeContract
{

    /**
     * Relation types.
     */
    const RELATION_ONE = 'one';
    const RELATION_MANY = 'many';


    /**
     * Properties definition.
     *
     * @var array
     */
    protected array $__props = [];


    /**
     * Relations.
     *
     * @var array
     */
    protected array $__relations = [];


    /**
     * Options to be passed to child models.
     *
     * @var array
     */
    protected array $__options = [];


    /**
     * Podium instance.
     *
     * @var Podium|null
     */
    protected ?Podium $podium = null;


    /**
     * Constructor.
     *
     * @param array $attr
     * @param Podium|null $podium
     * @param bool $deferred
     */
    public function __construct(array $attr = [], ?Podium $podium = null) {

        if ($podium)
            $this->injectPodiumInstance($podium);

        $this->init();
        $this->fillProps($attr);
    }


    /**
     * Add change Podium instance.
     *
     * @param Podium $podium
     * @return $this
     */
    public function injectPodiumInstance(Podium $podium): static
    {
        $this->podium = $podium;

        return $this;
    }


    abstract public function init() : void;


    /**
     * Register a property.
     *
     * @param $name
     * @param string $type
     * @param bool $isArray
     * @param array $options
     * @return $this
     */
    public function registerProp($name, string $type, bool $isArray = false, array $options = []): static
    {
        $options = $options + ['array' => $isArray];

        if (!isset($this->__props[$name])) {
            $this->__props[$name] = [
                'type'      => $type,
                'options'   => $options,
                'isArray'   => $isArray,
                'value'     => $isArray ? [] : null
            ];
        }

        return $this;
    }


    /**
     * Register a relation.
     *
     * @param $name
     * @param string $model
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function registerRelation($name, string $model, string $type = self::RELATION_ONE, array $options = []): static
    {
        $this->registerProp($name, $model, $type === static::RELATION_MANY, $options);

        if (!isset($this->__relations[$name])) {
            $this->__relations[$name] = ['model' => $model, 'type' => $type];
        }

        return $this;
    }


    /**
     * Model initialization.
     *
     * @param array $attr
     * @return $this
     */
    public function fillProps(mixed $attr) : static {
        foreach ($attr as $attribute => $val) {
            if (isset($this->__props[$attribute]))
                $this->setPropValue($attribute, $val);
        }

        return $this;
    }


    /**
     * Set options to be passed to child models.
     *
     * @param array $options
     * @return static
     */
    public function setOptions(array $options) : static {
        $this->__options = array_merge($this->__options, $options);

        foreach ($this->__props as $prop) {
            if ($options = $this->__options[$prop['type']] ?? null) {

                $value = Arr::wrap($prop['value']);

                collect($value)
                    ->filter(fn($i) => $i instanceof GenericTypeContract)
                    ->each(fn($i) => $i->setOptions($options));
            }
        }

        return $this;
    }


    public function setPropValue(string $prop, $value) : static {
        if (!isset($this->__props[$prop]))
            throw new \RuntimeException('Attribute not available');

        $instanceHelper = function ($type, $val) {
            /**
             * @var ModelContract|GenericTypeContract $instance
             */
            $instance = new $type;

            if (isset($this->__options[$type]) && $instance instanceof GenericTypeContract)
                $instance->setOptions($this->__options[$type]);

            if ($instance instanceof ModelContract) {
                if ($this->podium)
                    $instance->injectPodiumInstance($this->podium);

                $instance->init();
            }

            $instance->fillProps($val);

            return $instance;
        };

        if (is_array($value) && ($this->__props[$prop]['isArray'] ?? false)) {
            $this->__props[$prop]['value'] = [];

            foreach ($value as $subVal) {
                $this->__props[$prop]['value'][] = $subVal === null ? null : $instanceHelper($this->__props[$prop]['type'], $subVal);
            }
        } else {
            $this->__props[$prop]['value'] = $value === null ? null : $instanceHelper($this->__props[$prop]['type'], $value);
        }

        return $this;
    }


    public function getPropValue(string $prop) : mixed {
        return $this->__props[$prop]['value'] ?? null;
    }


    public function getProps() : array
    {
        return $this->__props;
    }


    /**
     * Decode all property values.
     *
     * @return array
     */
    public function decodeValue(): array
    {
        return $this->decodeValueType();
    }


    public function decodeValueForPost(): mixed
    {
        return $this->decodeValueType(true);
    }


    /**
     * Alias of setPropValue.
     *
     * @param string $prop
     * @param $value
     * @return void
     */
    public function __set(string $prop, $value) : void {
        $this->setPropValue($prop, $value);
    }

    /**
     * Alias of getPropValue.
     *
     * @param string $prop
     * @return mixed
     */
    public function __get(string $prop): mixed
    {
        $prop = $this->getPropValue($prop);

        if (is_array($prop)) {
            $values = [];

            foreach ($prop as $k => $item)
                $values[$k] = $item instanceof GenericTypeContract ? $item->decodeValue() : $prop;

            return $values;
        }

        return $prop instanceof GenericTypeContract ? $prop->decodeValue() : $prop;
    }


    public function originalValues() : array {
        $data = [];

        foreach ($this->__props as $propName => $prop) {
            if (is_array($prop['value']) && !Arr::isAssoc($prop['value'])) {
                foreach ($prop['value'] as $k => $subValue) {
                    $data[$propName][$k] = $subValue === null ? null : $subValue->originalValues();
                }
            } else
                $data[$propName] = $prop['value'] === null ? null : $prop['value']->originalValues();
        }

        return $data;
    }


    /**
     * Return the request base for the model.
     *
     * @return RequestBase|null
     */
    public function request() : ?RequestBase {
        return null;
    }


    /**
     * Reset a model.
     *
     * @return static
     */
    public function reset() : static {
        foreach ($this->__props as &$prop) {
            $prop['value'] = $prop['isArray'] ? [] : null;
        }

        return $this;
    }


    protected function decodeValueType(bool $forPostOperation = false): array
    {
        $data = [];

        $method = $forPostOperation ? 'decodeValueForPost' : 'decodeValue';

        foreach ($this->__props as $propName => $prop) {

            if (is_array($prop['value']) && !Arr::isAssoc($prop['value'])) {
                foreach ($prop['value'] as $k => $subValue) {
                    if (method_exists($subValue, 'decodeKey'))
                        $k = $subValue->decodeKey();

                    $data[$propName][$k] = $subValue === null ? null : call_user_func([$subValue, $method]);
                }
            } else
                $data[$propName] = $prop['value'] === null ? null : call_user_func([$prop['value'], $method]);
        }

        return $data;
    }
}