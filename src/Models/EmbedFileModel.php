<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/embeds
 */
class EmbedFileModel extends ModelBase
{
    public function init() : void    {
        $this->registerProp('embed', EmbedModel::class);
        $this->registerProp('file', FileModel::class);
    }
}
