<?php

namespace Juanparati\Podium\Models;


class PaginableModel implements \RecursiveIterator
{

    /**
     * Item key.
     *
     * @var int
     */
    protected int $itemKey = 0;


    public function __construct(
        protected array $collection,
        protected int $total,
        protected int $offset,
        protected PaginableModelContract $model
    ) {}


    public function current()
    {
        return $this->collection[$this->itemKey];
    }

    public function next()
    {

        // TODO: Implement next() method.
    }

    public function key()
    {
        return $this->itemKey + $this->offset;
    }

    public function valid()
    {
        // TODO: Implement valid() method.
    }

    public function rewind()
    {
        $this->model->firstPage();
    }

    public function hasChildren()
    {
        // TODO: Implement hasChildren() method.
    }

    public function getChildren()
    {
        // TODO: Implement getChildren() method.
    }
}