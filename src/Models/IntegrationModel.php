<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/integrations
 */
class IntegrationModel extends ModelBase
{
    public function init() : void {
        $this->registerProp('integration_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('app_id', IntGenericType::class);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('silent', BoolGenericType::class);
        $this->registerProp('config', RawGenericType::class);
        $this->registerProp('mapping', RawGenericType::class);
        $this->registerProp('updating', BoolGenericType::class);
        $this->registerProp('last_updated_on', DatetimeGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
    }

  
}
