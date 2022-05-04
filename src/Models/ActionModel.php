<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Requests\ActionRequest;
use Juanparati\Podium\Requests\RequestBase;

/**
 * @see https://developers.podio.com/doc/actions
 */
class ActionModel extends ModelBase
{

    public function init(): void
    {
        $this->registerProp('action_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('text', StringGenericType::class);
        $this->registerProp('data', RawGenericType::class);
        $this->registerProp('push', RawGenericType::class);
        $this->registerProp('comments', StringGenericType::class);
    }


    public function request(): ?RequestBase
    {
        return new ActionRequest($this->podium);
    }
}