<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

class ByLineModel extends ModelBase
{

    public function init() : void    {
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('id', IntGenericType::class);
        $this->registerProp('avatar_type', StringGenericType::class);
        $this->registerProp('avatar_id', IntGenericType::class);
        $this->registerProp('image', RawGenericType::class);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class);
        $this->registerProp('user_id', IntGenericType::class);
        $this->registerProp('last_seen_on', DatetimeGenericType::class);

    }
}