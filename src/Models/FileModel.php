<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\BoolGenericType;
use Juanparati\Podium\Models\Generics\DatetimeGenericType;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;

/**
 * @see https://developers.podio.com/doc/files
 */
class FileModel extends ModelBase
{
    public function init() : void {
        $this->registerProp('file_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('description', StringGenericType::class);
        $this->registerProp('size', IntGenericType::class);
        $this->registerProp('rights', StringGenericType::class, true);
        $this->registerProp('link', StringGenericType::class);
        $this->registerProp('link_target', StringGenericType::class);
        $this->registerProp('perma_link', StringGenericType::class);
        $this->registerProp('thumbnail_link', StringGenericType::class);
        $this->registerProp('is_liked', BoolGenericType::class);
        $this->registerProp('like_count', IntGenericType::class);
        $this->registerProp('subscribed', BoolGenericType::class);
        $this->registerProp('subscribed_count', IntGenericType::class);
        $this->registerProp('created_on', DatetimeGenericType::class);

        $this->registerProp('hosted_by', StringGenericType::class);
        $this->registerProp('hosted_by_humanized_name', StringGenericType::class);

        $this->registerProp('mimetype', StringGenericType::class);

        $this->registerProp('context', RawGenericType::class);

        $this->registerRelation('created_by', ByLineModel::class);
        $this->registerRelation('created_via', ViaModel::class);
        $this->registerRelation('replaces', static::class, static::RELATION_MANY);
    }
}
