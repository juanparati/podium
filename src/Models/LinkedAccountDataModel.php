<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

class LinkedAccountDataModel extends ModelBase
{
    public function init() : void {
        $this->registerProp('id', IntGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('info', StringGenericType::class);
        $this->registerProp('url', StringGenericType::class);
    }
}
