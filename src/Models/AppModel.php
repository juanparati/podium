<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;
use Juanparati\Podium\Requests\AppRequest;
use Juanparati\Podium\Requests\RequestBase;

/**
 * @see https://developers.podio.com/doc/applications
 */
class AppModel extends ModelBase
{

    public function init() : void {
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('item_name', StringGenericType::class);

        $this->registerProp('app_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('original', IntGenericType::class);
        $this->registerProp('original_revision', IntGenericType::class);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('space_id', IntGenericType::class);
        $this->registerProp('token', StringGenericType::class);
        $this->registerProp('mailbox', StringGenericType::class);
        $this->registerProp('owner', RawGenericType::class);
        // $this->registerProp('owner_id', IntGenericType::class);
        $this->registerProp('config', RawGenericType::class);
        $this->registerProp('rights', StringGenericType::class, true);
        $this->registerProp('subscribed', BoolGenericType::class);

        $this->registerProp('icon', StringGenericType::class);
        $this->registerProp('icon_id', IntGenericType::class);

        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('link_add', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class);
        $this->registerProp('url_add', StringGenericType::class);

        $this->registerProp('url_label', StringGenericType::class);
        $this->registerProp('current_revision', IntGenericType::class);
        $this->registerProp('item_accounting_info', RawGenericType::class);

        $this->registerRelation('fields', AppFieldModel::class, static::RELATION_MANY);
        $this->registerRelation('integration', IntegrationModel::class);

        // When app is returned as part of large collection (e.g. for stream), some config properties is moved to the main object
    }


    /**
     * App requests collection.
     *
     * @return RequestBase
     */
    public function request(): RequestBase
    {
        return new AppRequest($this->podium);
    }
}
