<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/users
 */
class UserModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('user_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('profile_id', IntGenericType::class);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('avatar', IntGenericType::class);
        $this->registerProp('mail', StringGenericType::class);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('locale', StringGenericType::class);
        $this->registerProp('timezone', StringGenericType::class);
        $this->registerProp('flags', StringGenericType::class, true);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);

        $this->registerRelation('profile', ContactModel::class);
        $this->registerRelation('mails', UserMailModel::class);
    }
}
