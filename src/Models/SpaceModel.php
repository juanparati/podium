<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/spaces
 */
class SpaceModel extends ModelBase
{
    public function init() : void {
        $this->registerProp('space_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class);
        $this->registerProp('url_label', StringGenericType::class);
        $this->registerProp('org_id', IntGenericType::class);
        $this->registerProp('contact_count', IntGenericType::class);
        $this->registerProp('members', IntGenericType::class);
        $this->registerProp('role', StringGenericType::class);
        $this->registerProp('rights', StringGenericType::class, true);
        $this->registerProp('post_on_new_app', BoolGenericType::class);
        $this->registerProp('post_on_new_member', BoolGenericType::class);
        $this->registerProp('subscribed', BoolGenericType::class);
        $this->registerProp('privacy', StringGenericType::class);
        $this->registerProp('auto_join', BoolGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('premium', BoolGenericType::class);
        $this->registerProp('description', StringGenericType::class);

        $this->registerProp('created_on', DatetimeGenericType::class);
        $this->registerProp('last_activity_on', DatetimeGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('org', OrganizationModel::class);
    }
}
