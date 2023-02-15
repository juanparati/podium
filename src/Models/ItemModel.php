<?php

namespace Juanparati\Podium\Models;

use Illuminate\Support\Arr;
use Juanparati\Podium\Exceptions\MissingRelationshipException;
use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\FloatGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Requests\AppRequest;
use Juanparati\Podium\Requests\ItemRequest;
use Juanparati\Podium\Requests\RequestBase;

class ItemModel extends ModelBase
{


    /**
     * Default cache time used for the schema cache.
     */
    protected const DEFAULT_SCHEMA_CACHE_TIME = 120;



    public function init(): void
    {
        $this->registerProp('item_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('external_id', StringGenericType::class);
        $this->registerProp('title', StringGenericType::class);
        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('rights', StringGenericType::class, true);
        $this->registerProp('created_on', DatetimeGenericType::class);
        $this->registerProp('app_item_id_formatted', StringGenericType::class);
        $this->registerProp('app_item_id', IntGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('created_via', ViaModel::class);

        $this->registerRelation('initial_revision', ItemRevisionModel::class);
        $this->registerRelation('current_revision', ItemRevisionModel::class);
        $this->registerRelation('fields', ItemFieldModel::class, static::RELATION_MANY);

        $this->registerProp('like_count', IntGenericType::class);
        $this->registerProp('is_liked', BoolGenericType::class);

        # Extra properties for full item
        $this->registerProp('ratings', RawGenericType::class);
        $this->registerProp('user_ratings', RawGenericType::class);
        $this->registerProp('last_event_on', DatetimeGenericType::class);
        $this->registerProp('participants', RawGenericType::class);
        $this->registerProp('tags', StringGenericType::class, true);
        $this->registerProp('refs', RawGenericType::class);
        $this->registerProp('linked_account_id', IntGenericType::class);
        $this->registerProp('subscribed', BoolGenericType::class);
        $this->registerProp('invite', RawGenericType::class);
        $this->registerProp('votes', RawGenericType::class);
        $this->registerProp('priority', FloatGenericType::class);
        $this->registerProp('subscribed_count', IntGenericType::class);
        $this->registerProp('revision', IntGenericType::class);
        $this->registerProp('push', RawGenericType::class);

        $this->registerRelation('app', AppModel::class);
        $this->registerRelation('ref', ReferenceModel::class);
        $this->registerRelation('reminder', ReminderModel::class);
        $this->registerRelation('recurrence', RecurrenceModel::class);
        $this->registerRelation('linked_account_data', LinkedAccountDataModel::class);
        $this->registerRelation('comments', CommentModel::class, static::RELATION_MANY);
        $this->registerRelation('revisions', ItemRevisionModel::class, static::RELATION_MANY);
        $this->registerRelation('files', FileModel::class, static::RELATION_MANY, ['json_value' => 'file_id', 'json_target' => 'file_ids']);
        $this->registerRelation('tasks', TaskModel::class, static::RELATION_MANY);
        $this->registerRelation('shares', AppMarketShareModel::class, static::RELATION_MANY);

        # When getting item collection
        /*
        $this->registerProp('comment_count', 'integer');
        $this->registerProp('file_count', 'integer');
        $this->registerProp('task_count', 'integer');
        */
    }


    public function setPropValue(string $prop, $value): static
    {
        // Preload all the fields schema.
        if ($prop === 'fields' && !empty($value) && !empty($this->app['app_id'])) {
            $appSchema = $this->retrieveAppSchema($this->app['app_id']);
            $value = array_merge($appSchema['fields'], $value);
        }

        return parent::setPropValue($prop, $value);
    }

    public function getPropValue(string $prop): mixed
    {
        if ($prop === 'fields') {
            $fields = [];

            foreach (($this->__props['fields']['value'] ?? []) as $field) {
                $fields[$field->decodeKey()] = $field;
            }

            return new ItemFieldsCollectionModel($fields);
        }

        return parent::getPropValue($prop);
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


    /**
     * Update item.
     *
     * @param bool $silent
     * @param bool $hook
     * @return void
     * @throws MissingRelationshipException
     */
    public function save(bool $silent = false, bool $hook = true)
    {
        $values = Arr::only(
            $this->decodeValueForPost(),
            [
                'revision',
                'external_id',
                'fields',
                'file_ids',
                'tags',
                'reminder',
                'recurrence',
                'linked_account_id',
                'ref'
            ]
        );

        $values['fields'] = $this->prepareValuesForPost();

        if ($this->item_id) {
            $this->request()->update(
                $this->item_id,
                $values,
                $silent,
                $hook
            );
        } else {

            if (empty($this->app['app_id']))
                throw new MissingRelationshipException('Model require an App definition.');

            $newValues = $this->request()->create(
                    $this->app['app_id'],
                    $values,
                    $silent,
                    $hook
                )->originalValues();

            $this->fillProps(Arr::except($newValues, 'fields'));
        }
    }


    /**
     * Prepare new item.
     *
     * @param string|int $appId
     * @param bool $force
     * @return $this
     */
    public function prepareNew(string|int $appId, bool $force = false): static
    {
        $this->reset();
        $appSchema = $this->retrieveAppSchema($appId, $force);

        $this->fillProps([
            'fields' => $appSchema['fields'],
            'app'    => Arr::except($appSchema, 'fields'),
        ]);

        return $this;
    }


    /**
     * Retrieve App Schema.
     *
     * @param string|int $appId
     * @param bool $force
     * @return array
     */
    protected function retrieveAppSchema(string|int $appId, bool $force = false): array
    {
        $key = 'schema:' . $appId;

        if (!$this->podium) {
            return ['fields' => []];
        }

        if (!($appSchema = $this->podium->getCacheStore()->get($key)) || $force) {
            $appSchema = (new AppRequest($this->podium))->get($appId)->originalValues();

            $this->podium->getCacheStore()->put($key, $appSchema, static::DEFAULT_SCHEMA_CACHE_TIME);
        }

        return $appSchema;
    }


    /**
     * Prepare field values for POST/PUT request.
     *
     * @return array
     */
    protected function prepareValuesForPost(): array
    {
        return collect($this->decodeValueType(true)['fields'])
            ->filter(fn($field) => $field['values'] !== null)
            ->keyBy('field_id')
            ->map(fn($field) => $field['values'])
            ->toArray();
    }
}