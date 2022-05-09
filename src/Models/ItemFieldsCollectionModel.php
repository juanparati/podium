<?php

namespace Juanparati\Podium\Models;

use Illuminate\Contracts\Support\Arrayable;

class ItemFieldsCollectionModel implements \Iterator, Arrayable
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

        foreach (array_keys($this->fields) as $key)
            $data[$key] = $this->{$key};

        return $data;
    }


    public function __get(string $field) {
        return isset($this->fields[$field]) ? $this->fields[$field]->decodeValue()['values'] : null;
    }

    public function __set(string $field, mixed $value) {
        $this->fields[$field]->setRawValue($value);
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
}