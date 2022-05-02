<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/items
 */
class ItemRevisionModel extends ModelBase
{
    public function init() : void     {
        $this->registerProp('revision', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('app_revision', IntGenericType::class);
        $this->registerProp('item_revision_id', IntGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('user', ByLineModel::class);

        $this->registerProp('created_on', DatetimeGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('created_via', ViaModel::class);
    }
}
