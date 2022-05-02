<?php

namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;

class ViaModel extends ModelBase
{
    public function init() : void     {
        $this->registerProp('id', IntGenericType::class);
        $this->registerProp('auth_client_id', IntGenericType::class);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class);
        $this->registerProp('display', BoolGenericType::class);
    }
}