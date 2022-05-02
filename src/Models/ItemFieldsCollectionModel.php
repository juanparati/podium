<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\ItemFields\ItemFieldContract;

class ItemFieldsCollectionModel
{

    /**
     * Constructor.
     *
     * @param array $fields
     */
    public function __construct(protected array $fields) {}


    public function __get(string $field) {
        return isset($this->fields[$field]) ?  $this->fields[$field]->decodeValue()['values'] : null;
    }


    public function __set(string $field, mixed $value) {
        if ($value instanceof ItemFieldContract)
            $this->fields[$field] = $value;

        $this->fields[$field]->getPropValue('values')->encodeValue($value);
    }

}