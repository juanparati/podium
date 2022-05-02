<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/applications
 */
class AppFieldModel extends ModelBase
{
    public function init() : void    {
        $this->registerProp('field_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('external_id', StringGenericType::class);
        $this->registerProp('config', RawGenericType::class);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('label', StringGenericType::class);
    }
}
