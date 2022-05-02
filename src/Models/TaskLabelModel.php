<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/tasks
 */
class TaskLabelModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('label_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('text', StringGenericType::class);
        $this->registerProp('color', StringGenericType::class);

        if (!isset($attr['color']))
            $this->fillProps(['color' => 'E9E9E9']);
    }
}
