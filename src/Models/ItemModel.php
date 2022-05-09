<?php

namespace Juanparati\Podium\Models;

use Illuminate\Support\Arr;
use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\FloatGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Requests\ItemRequest;
use Juanparati\Podium\Requests\RequestBase;

class ItemModel extends ModelBase
{


    public function init() : void
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


    public function update() {
        $this->request()->update(
            $this->item_id,
            Arr::except($this->decodeValue(), 'item_id'),
        );
    }
}