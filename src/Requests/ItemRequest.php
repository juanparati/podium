<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Models\ItemFilterModel;
use Juanparati\Podium\Models\ItemModel;

class ItemRequest extends RequestBase
{

    /**
     * @see https://developers.podio.com/doc/items/add-new-item-22362
     *
     * @param string|int $appId
     * @param array $attr
     * @param bool $silent
     * @param bool $hook
     * @return ItemModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function create(
        string|int $appId,
        array $attr,
        bool $silent = false,
        bool $hook = false,
    ) : ItemModel {
        return new ItemModel(
            $this->podium->request(
                static::METHOD_POST,
                "/item/app/$appId/",
                $attr,
                array_filter(['hook' => $hook ? null : 'false', 'silent' => $silent ? '1' : null])
            ),
            $this->podium
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/bulk-delete-items-19406111
     *
     * @param string|int $appId
     * @param array $attr
     * @return void
     */
    public function bulkDelete(string|int $appId, array $attr) {
        return $this->podium->request(
            static::METHOD_POST,
            "/item/app/{$appId}/delete",
            $attr
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/calculate-67633
     *
     * @param string $appId
     * @param array $attr
     * @return void
     */
    public function calculate(string|int $appId, array $attr) {
        return $this->podium->request(
            static::METHOD_POST,
            "/item/app/{$appId}/calculate",
            $attr
        );
    }

    /**
     * Clone.
     *
     * @see https://developers.podio.com/doc/items/clone-item-37722742
     * @param string|int $itemId
     * @param bool $silent
     * @return mixed
     */
    public function duplicate(string|int $itemId, bool $silent = false) {
        return $this->podium->request(
            static::METHOD_POST,
            "/item/{$itemId}/clone",
            options: array_filter(['silent' => $silent])
        );
    }

    /**
     * @see https://developers.podio.com/doc/items/get-item-clone-96822231
     *
     * @param string|int $itemId
     * @return mixed
     */
    public function getCloned(string|int $itemId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/clone",
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/delete-item-22364
     *
     * @param string|int $itemId
     * @param bool $silent
     * @param bool $hook
     * @return mixed
     */
    public function delete(string|int $itemId, bool $silent = false, bool $hook = false) {
        return $this->podium->request(
            static::METHOD_DELETE,
            "/item/{$itemId}",
            options: array_filter(['hook' => $hook ? null : 'false', 'silent' => $silent ? '1' : null])

        );
    }


    /**
     * @see https://developers.podio.com/doc/items/delete-item-reference-7302326
     *
     * @param string|int $itemId
     * @return mixed
     */
    public function deleteReference(string|int $itemId) {
        return $this->podium->request(
            static::METHOD_DELETE,
            "/item/{$itemId}/ref"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/export-items-4235696
     *
     * @param string|int $appId
     * @param array $attr
     * @param string $exporter
     * @return mixed
     */
    public function export(string|int $appId, array $attr, string $exporter = 'xlsx') {
        return $this->podium->request(
            static::METHOD_POST,
            "/item/app/{$appId}/export/{$exporter}",
            $attr
        )['batch_id'];
    }


    /**
     * @see https://developers.podio.com/doc/items/filter-items-4496747
     *
     * @param string|int $appId
     * @param array $attr
     * @return mixed
     */
    public function filter(string|int $appId, array $attr, bool $raw = false) {
        $response = $this->podium->request(
            static::METHOD_POST,
            "/item/app/{$appId}/filter/",
            $attr
        );

        return $raw ? $response : new ItemFilterModel($response, $this->podium);
    }


    /**
     * https://developers.podio.com/doc/items/filter-items-by-view-4540284
     *
     * @param string|int $appId
     * @param string|int $viewId
     * @param array $attr
     * @return mixed
     */
    public function filterByView(string|int $appId, string|int $viewId, array $attr) {
        return $this->podium->request(
            static::METHOD_POST,
            "/item/app/{$appId}/filter/{$viewId}/",
            $attr
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/find-referenceable-items-22485
     *
     * @param string|int $fieldId
     * @param int $limit
     * @param string|null $text
     * @param array|null $notItemId
     * @return mixed
     */
    public function searchField(
        string|int $fieldId,
        int $limit = 13,
        ?string $text = null,
        ?array $notItemId = []
    ) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/field/{$fieldId}/find",
            options: array_filter(['limit' => $limit, 'text' => $text, 'not_item_id' => $notItemId])
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-field-ranges-24242866
     *
     * @param string|int $fieldId
     * @return mixed
     */
    public function fieldRange(string|int $fieldId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/field/$fieldId/range",
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-22360
     *
     * @param string|int $itemId
     * @param bool $markAsViewed
     * @return ItemModel|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function get(string|int $itemId, bool $markAsViewed = false) : ?ItemModel {
        $response = $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}",
            options: array_filter(['mark_as_viewed' => $markAsViewed])
        );

        return $response ? new ItemModel($response, $this->podium) : null;
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-by-app-item-id-66506688
     *
     * @param string|int $appId
     * @param string|int $appItemId
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function getByAppItemId(string|int $appId, string|int $appItemId) {
        $this->podium->request(
            static::METHOD_GET,
            "/app/{$appId}/item/{$appItemId}"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-by-external-id-19556702.
     *
     * @param string|int $appId
     * @param string|int $externalId
     * @return void
     */
    public function getByExternalId(string|int $appId, string|int $externalId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/app/{$appId}/external_id/{$externalId}"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-count-34819997
     *
     * @param string|int $appId
     * @param array $viewId
     * @return mixed
     */
    public function count(string|int $appId, array $viewId = []) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/app/{$appId}/count",
            options: array_filter(['view_id' => $viewId])
        );
    }


    /**
     * https://developers.podio.com/doc/items/get-item-field-values-22368.
     *
     * @param string|int $itemId
     * @param string|int $fieldId
     * @return mixed
     */
    public function getFieldValue(string|int $itemId, string|int $fieldId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/value/{$fieldId}"
        );
    }


    /**
     * https://developers.podio.com/doc/items/get-item-field-values-v2-144279511
     *
     * @param string|int $itemId
     * @param string|int $fieldId
     * @return mixed
     */
    public function getFieldValueV2(string|int $itemId, string|int $fieldId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/value/{$fieldId}/v2"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-preview-for-field-reference-7529318
     *
     * @param string|int $itemId
     * @param string|int $fieldId
     * @return mixed
     */
    public function getBasicByField(string|int $itemId, string|int $fieldId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/reference/{$fieldId}/preview"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-references-22439
     *
     * @param string|int $itemId
     * @param int $limit
     * @return mixed
     */
    public function getReferences(string|int $itemId, int $limit = 1000) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/reference/",
            options: ['limit' => $limit]
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-revision-22373
     *
     * @param string|int $itemId
     * @param string|int $revision
     * @return mixed
     */
    public function getRevision(string|int $itemId, string|int $revision) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/$itemId/revision/$revision",
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-values-22365
     *
     * @param string|int $itemId
     * @return mixed
     */
    public function getItemValues(string|int $itemId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/value"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-item-values-v2-144280791
     *
     * @param string|int $itemId
     * @return mixed
     */
    public function getItemValuesV2(string|int $itemId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/{$itemId}/value/v2"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/get-items-as-xlsx-63233
     *
     * @param string|int $appId
     * @param int $limit
     * @param int $offset
     * @param bool $deletedColumns
     * @param bool $remember
     * @param string|null $sortBy
     * @param bool $sortDesc
     * @param string|int|null $viewId
     * @param array $keys
     * @return mixed
     */
    public function xlsx(
        string|int      $appId,
        int             $limit          = 20,
        int             $offset         = 0,
        bool            $deletedColumns = false,
        bool            $remember       = true,
        ?string         $sortBy         = null,
        bool            $sortDesc       = true,
        string|int|null $viewId         = null,
        array $keys                     = []
    ) {

        $opts = array_filter([
            'limit'           => $limit,
            'offset'          => $offset,
            'deleted_columns' => $deletedColumns,
            'remember'        => $remember,
            'sort_by'         => $sortBy,
            'sort_desc'       => $sortDesc,
            'view_id'         => $viewId,
        ] + $keys);

        return $this->podium->request(
            static::METHOD_GET,
            "/item/app/$appId/xlsx/"
        );
    }


    /**
     * Get meeting URL.
     *
     * @param string|int $itemId
     * @return mixed
     */
    public function getMeetingUrl(string|int $itemId) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/$itemId/meeting/url"
        );
    }


    /**
     * https://developers.podio.com/doc/items/get-references-to-item-by-field-7403920
     *
     * @param string|int $itemId
     * @param string|int $fieldId
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getReferencesByField(string|int $itemId, string|int $fieldId, int $limit = 10, int $offset = 0) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/$itemId/reference/field/$fieldId",
            options: ['limit' => $limit, 'offset' => $offset]
        );
    }


    /**
     * https://developers.podio.com/doc/items/get-top-values-for-field-68334
     *
     * @param string|int $fieldId
     * @param int $limit
     * @param array $notItemId
     * @return mixed
     */
    public function getTopValuesByField(string|int $fieldId, int $limit = 13, array $notItemId = []) {
        return $this->podium->request(
            static::METHOD_GET,
            "/item/field/{$fieldId}/top/",
            options: array_filter(['limit' => $limit, 'not_item_id' => $notItemId])
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/rearrange-item-10617690
     *
     * @param string|int $itemId
     * @param array $attr
     * @return mixed
     */
    public function rearrangeItem(string|int $itemId, array $attr) {
        return $this->podium->request(
            static::METHOD_POST,
             "/item/$itemId/rearrange",
            $attr
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/revert-item-revision-953195
     *
     * @param string|int $itemId
     * @param string|int $revisionId
     * @return mixed
     */
    public function revertToRevision(string|int $itemId, string|int $revisionId) {
        return $this->podium->request(
            static::METHOD_POST,
            "/item/$itemId/revision/$revisionId/revert_to"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/set-participation-7156154
     *
     * @param string|int $itemId
     * @return mixed
     */
    public function participation(string|int $itemId) {
        return $this->podium->request(
            static::METHOD_PUT,
            "/item/{$itemId}/participation"
        );
    }


    /**
     * @see https://developers.podio.com/doc/items/update-item-22363
     *
     * @param string|int $itemId
     * @param array $attr
     * @param bool $silent
     * @param bool $hook
     * @return mixed
     */
    public function update(string|int $itemId, array $attr, bool $silent = false, bool $hook = true) {
        return $this->podium->request(
            static::METHOD_PUT,
            "/item/$itemId",
            $attr,
            array_filter(['hook' => $hook ? null : 'false', 'silent' => $silent ? '1' : null])
        );
    }


    /**
     * https://developers.podio.com/doc/items/update-item-values-22366.
     *
     * @param string|int $itemId
     * @param bool $silent
     * @param bool $hook
     * @return void
     */
    public function updateValues(string|int $itemId, bool $silent = false, bool $hook = true) {
        $this->podium->request(
            static::METHOD_PUT,
            "/item/$itemId/value",
            array_filter(['hook' => $hook ? null : 'false', 'silent' => $silent ? '1' : null])
        );
    }



}