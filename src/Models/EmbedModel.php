<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/embeds
 */
class EmbedModel extends ModelBase
{
    public function init() : void    {
        $this->registerProp('embed_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('original_url', StringGenericType::class);
        $this->registerProp('resolved_url', StringGenericType::class);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('title', StringGenericType::class);
        $this->registerProp('description', StringGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);
        $this->registerProp('provider_name', StringGenericType::class);
        $this->registerProp('embed_html', StringGenericType::class);
        $this->registerProp('embed_height', IntGenericType::class);
        $this->registerProp('embed_width', IntGenericType::class);

        // Read-only, provided by ItemField
        $this->registerProp('url', StringGenericType::class);
        $this->registerProp('hostname', StringGenericType::class);

        $this->registerRelation('files', FileModel::class, static::RELATION_MANY);
    }
}
