<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/tasks
 */
class TaskModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('task_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('group', StringGenericType::class);
        $this->registerProp('text', StringGenericType::class);
        $this->registerProp('description', StringGenericType::class);
        $this->registerProp('private', BoolGenericType::class);
        $this->registerProp('due_on', DatetimeGenericType::class);
        $this->registerProp('due_date', StringGenericType::class);
        $this->registerProp('due_time', StringGenericType::class);
        $this->registerProp('space_id', IntGenericType::class);
        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);
        $this->registerProp('completed_on', DatetimeGenericType::class);
        $this->registerProp('external_id', StringGenericType::class);

        $this->registerRelation('ref', ReferenceModel::class);
        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('completed_by', ByLineModel::class);
        $this->registerRelation('created_via', ViaModel::class);
        $this->registerRelation('deleted_via', ViaModel::class);
        $this->registerRelation('completed_via', ViaModel::class);
        $this->registerRelation('responsible', UserModel::class, static::RELATION_ONE, ['json_value' => 'user_id']);
        $this->registerRelation('reminder', ReminderModel::class);
        $this->registerRelation('recurrence', RecurrenceModel::class);

        $this->registerRelation('labels', TaskLabelModel::class, static::RELATION_MANY, ['json_value' => 'label_id', 'json_target' => 'label_ids']);
        $this->registerRelation('files', FileModel::class, static::RELATION_MANY, ['json_value' => 'file_id', 'json_target' => 'file_ids']);
        $this->registerRelation('comments', CommentModel::class, static::RELATION_MANY);
    }
}
