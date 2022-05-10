<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DateGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;


/**
 * @see https://developers.podio.com/doc/contacts
 */
class ContactModel extends ModelBase
{
    public function init() : void    {
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('avatar', IntGenericType::class);
        $this->registerProp('birthdate', DateGenericType::class);
        $this->registerProp('organization', StringGenericType::class);
        $this->registerProp('department', StringGenericType::class);
        $this->registerProp('skype', StringGenericType::class);
        $this->registerProp('about', StringGenericType::class);
        $this->registerProp('address', StringGenericType::class, true);
        $this->registerProp('zip', StringGenericType::class);
        $this->registerProp('city', StringGenericType::class);
        $this->registerProp('state', StringGenericType::class);
        $this->registerProp('country', StringGenericType::class);
        $this->registerProp('location', StringGenericType::class, true);
        $this->registerProp('mail', StringGenericType::class, true);
        $this->registerProp('profile_id', IntGenericType::class);
        $this->registerProp('user_id', IntGenericType::class);
        $this->registerProp('phone', StringGenericType::class, true);
        $this->registerProp('title', StringGenericType::class, true);
        $this->registerProp('linkedin', StringGenericType::class);
        $this->registerProp('twitter', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class, true);
        $this->registerProp('skill', StringGenericType::class, true);

        $this->registerProp('app_store_about', StringGenericType::class);
        $this->registerProp('app_store_organization', StringGenericType::class);
        $this->registerProp('app_store_location', StringGenericType::class);
        $this->registerProp('app_store_title', StringGenericType::class);
        $this->registerProp('app_store_url', StringGenericType::class);


        $this->registerProp('vatin', StringGenericType::class);
        $this->registerProp('im', StringGenericType::class, true);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('space_id', IntGenericType::class);
        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('rights', StringGenericType::class, true);
        $this->registerProp('last_seen_on', DatetimeGenericType::class);
        $this->registerProp('is_employee', BoolGenericType::class);

        // Only available for space contacts
        $this->registerProp('role', IntGenericType::class);
        $this->registerProp('removable', BoolGenericType::class);

        $this->registerRelation('image', FileModel::class);
    }


    /**
     * Decode for POST/PUT operation.
     *
     * @return mixed
     */
    public function decodeValueForPost(): mixed
    {
        return $this->profile_id;
    }


}
