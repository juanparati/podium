<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/organizations
 */
class OrganizationModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('org_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('logo', IntGenericType::class);
        $this->registerProp('url', StringGenericType::class);
        $this->registerProp('user_limit', IntGenericType::class);
        $this->registerProp('url_label', StringGenericType::class);
        $this->registerProp('premium', BoolGenericType::class);
        $this->registerProp('role', StringGenericType::class);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('sales_agent_id', IntGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);
        $this->registerProp('domains', StringGenericType::class, true);
        $this->registerProp('rights', StringGenericType::class, true);
        $this->registerProp('rank', IntGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('image', FileModel::class);
        $this->registerRelation('spaces', SpaceModel::class, static::RELATION_MANY);
    }


}
