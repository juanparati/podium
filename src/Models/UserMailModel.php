<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/users
 */
class UserMailModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('mail', StringGenericType::class);
        $this->registerProp('verified', BoolGenericType::class);
        $this->registerProp('primary', BoolGenericType::class);
        $this->registerProp('disabled', BoolGenericType::class);
    }
}
