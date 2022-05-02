<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;

/**
 * @see https://developers.podio.com/doc/questions
 */
class QuestionOptionModel extends ModelBase
{
    public function init() : void {
        $this->registerProp('question_option_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('text', StringGenericType::class);
    }
}
