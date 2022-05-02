<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;

/**
 * @see https://developers.podio.com/doc/questions
 */
class QuestionModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('question_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('text', StringGenericType::class);

        $this->registerRelation('ref', ReferenceModel::class);
        $this->registerRelation('answers', QuestionAnswerModel::class, static::RELATION_MANY);
        $this->registerRelation('options', QuestionOptionModel::class, static::RELATION_MANY);
    }
}
