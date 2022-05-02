<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;

/**
 * @see https://developers.podio.com/doc/comments
 */
class CommentModel extends ModelBase
{
    public function init() : void    {
        $this->registerProp('comment_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('value', StringGenericType::class);
        $this->registerProp('rich_value', StringGenericType::class);
        $this->registerProp('external_id', StringGenericType::class);
        $this->registerProp('space_id', IntGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);
        $this->registerProp('like_count', IntGenericType::class);
        $this->registerProp('is_liked', BoolGenericType::class);
        $this->registerProp('rights', StringGenericType::class, true);

        $this->registerRelation('user', ByLineModel::class);
        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('created_via', ViaModel::class);
        $this->registerRelation('ref', ReferenceModel::class);

        $this->registerRelation('embed', EmbedModel::class, static::RELATION_ONE, ['json_value' => 'embed_id', 'json_target' => 'embed_id']);
        $this->registerRelation('embed_file', FileModel::class, static::RELATION_ONE, ['json_value' => 'file_id', 'json_target' => 'embed_file_id']);
        $this->registerRelation('files', FileModel::class, static::RELATION_MANY, ['json_value' => 'file_id', 'json_target' => 'file_ids']);
        $this->registerRelation('questions', QuestionModel::class, static::RELATION_MANY);
    }
}
