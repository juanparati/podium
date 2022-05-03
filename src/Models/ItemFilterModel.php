<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Requests\ItemRequest;
use Juanparati\Podium\Requests\RequestBase;

class ItemFilterModel extends ModelBase
{

    /**
     * Request payload.
     *
     * @var array
     */
    protected array $requestBody = [
        "sort_by"   => "created_on",
        "sort_desc" => false,
        "filters"   => [],
        "limit"     => 30,
        "offset"    => 0,
        "remember"  => false
    ];


    /**
     * Last requested App Id.
     *
     * @var mixed|null
     */
    protected mixed $lastAppId = null;



    public function init(): void
    {
        $this->registerProp('filtered', IntGenericType::class);
        $this->registerProp('total', IntGenericType::class);
        $this->registerRelation('items', ItemModel::class, static::RELATION_MANY);
    }


    public function setRequestBody(array $requestBody) : static {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function getRequestBody() : array {
        return $this->requestBody;
    }

    public function setSortBy(string $sortBy) : static {
        $this->requestBody['sort_by'] = $sortBy;

        return $this;
    }

    public function setSortDesc(bool $sortDesc) : static {
        $this->requestBody['sort_by'] = $sortDesc;

        return $this;
    }

    public function setFilters(array $filters) : static {
        $this->requestBody['filters'] = $filters;

        return $this;
    }


    public function setLimit(int $limit) : static {
        $this->requestBody['limit'] = $limit;

        return $this;
    }

    public function setOffset(int $offset) : static {
        $this->requestBody['offset'] = $offset;

        return $this;
    }


    public function setRemember(bool $remember) : static {
        $this->requestBody['remember'] = $remember;

        return $this;
    }


    /**
     * Perform filter request.
     *
     * @param string|int $appId
     * @return $this
     */
    public function filter(string|int $appId) {
        $this->lastAppId = $appId;

        $this->fillProps($this->request()->filter($appId, $this->requestBody, true));

        return $this;
    }


    /**
     * Load next page.
     *
     * @return $this
     */
    public function nextPage() : static {
        if (!$this->lastAppId)
            throw new \RuntimeException('Filter method was not previously called');

        $this->setOffset($this->requestBody['offset'] + $this->requestBody['limit']);

        return $this->filter($this->lastAppId);
    }


    /**
     * Load previous page.
     *
     * @return $this
     */
    public function prevPage() : static {
        if (!$this->lastAppId)
            throw new \RuntimeException('Filter method was not previously called');

        $offset = $this->requestBody['offset'] - $this->requestBody['limit'];

        if ($offset < 0)
            return $this;

        $this->setOffset($offset);

        return $this->filter($this->lastAppId);
    }


    /**
     * Load first page.
     *
     * @return $this
     */
    public function firstPage() : static {
        $this->filter($this->lastAppId);

        return $this;
    }


    /**
     * Generate items using a cursor.
     *
     * It handles automatically the pagination.
     *
     * @return \Generator
     */
    public function items(): \Generator
    {
        while (count($this->items)) {
            foreach ($this->getPropValue('items') as $item) {
                yield $item;
            }

            $this->nextPage();
        }
    }


    /**
     * Item request.
     *
     * @return ItemRequest
     */
    public function request(): RequestBase
    {
        return new ItemRequest($this->podium);
    }


}