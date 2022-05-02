<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/reference
 */
class ReferenceModel extends ModelBase
{
    public function init() : void {
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('id', IntGenericType::class);
        $this->registerProp('title', StringGenericType::class);
        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('data', RawGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('created_via', ViaModel::class);
    }
}
