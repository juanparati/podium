<?php

namespace Juanparati\Podium\Models;

use Illuminate\Contracts\Support\Arrayable;

class ItemFieldsCollectionModel implements \Iterator, \ArrayAccess, Arrayable
{

    /**
     * Constructor.
     *
     * @param array $fields
     */
    public function __construct(protected array $fields) {}


    /**
     * Decode value.
     *
     * @return array
     */
    public function decodeValue() : array {
        $data = [];

        foreach ($this->fields as $key => $field)
            $data[$key] = $field->decodeValue();

        return $data;
    }

    public function current()
    {
        return current($this->fields);
    }

    public function next()
    {
        next($this->fields);
    }

    public function key()
    {
        return key($this->fields);
    }

    public function valid()
    {
        return key($this->fields) !== null;
    }

    public function rewind()
    {
        reset($this->fields);
    }

    public function toArray()
    {
        return $this->fields;
    }

    public function offsetSet($field, $value) {
        $this->fields[$field]->setRawValue($value);
    }

    public function offsetExists($field) {
        return isset($this->fields[$field]);
    }

    public function offsetUnset($field) {
        unset($this->fields[$field]);
    }

    public function offsetGet($field) {
        return isset($this->fields[$field]) ? $this->fields[$field]->decodeValue()['values'] : null;
    }

    public function __get(string $field) {
        return $this->offsetGet($field);
    }

    public function __set(string $field, mixed $value) {
        $this->offsetSet($field, $value);
    }
}
