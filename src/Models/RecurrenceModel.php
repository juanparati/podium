<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DateGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/recurrence
 */
class RecurrenceModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('recurrence_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('config', RawGenericType::class);
        $this->registerProp('step', IntGenericType::class);
        $this->registerProp('until', DateGenericType::class);
    }
}
