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

    public function current() : mixed
    {
        return current($this->fields);
    }

    public function next() : void
    {
        next($this->fields);
    }

    public function key() : string|int|null
    {
        return key($this->fields);
    }

    public function valid() : bool
    {
        return key($this->fields) !== null;
    }

    public function rewind() : void
    {
        reset($this->fields);
    }

    public function toArray() : array
    {
        return $this->fields;
    }

    public function offsetSet($offset, $value) : void {
        $this->fields[$offset]->setRawValue($value);
    }

    public function offsetExists($offset) : bool {
        return isset($this->fields[$offset]);
    }

    public function offsetUnset($offset) : void {
        unset($this->fields[$offset]);
    }

    public function offsetGet($field) : mixed {
        return isset($this->fields[$field]) ? $this->fields[$field]->decodeValue()['values'] : null;
    }

    public function getProp(string $field) : mixed {
        return $this->offsetGet($field);
    }

    public function setProp(string $field, mixed $value) : void {
        $this->offsetSet($field, $value);
    }

    public function __get(string $field) : mixed {
        return $this->getProp($field);
    }

    public function __set(string $field, mixed $value) : void {
        $this->setProp($field, $value);
    }
}
