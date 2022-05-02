<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/app-store
 */
class AppMarketShareModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('share_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('status', StringGenericType::class);
        $this->registerProp('name', StringGenericType::class);
        $this->registerProp('description', StringGenericType::class);
        $this->registerProp('abstract', StringGenericType::class);
        $this->registerProp('language', StringGenericType::class);
        $this->registerProp('features', StringGenericType::class, true);
        $this->registerProp('filters', StringGenericType::class, true);
        $this->registerProp('integration', StringGenericType::class);
        $this->registerProp('categories', RawGenericType::class);
        $this->registerProp('org', RawGenericType::class);
        $this->registerProp('author_apps', IntGenericType::class);
        $this->registerProp('author_packs', IntGenericType::class);
        $this->registerProp('icon', StringGenericType::class);
        $this->registerProp('icon_id', IntGenericType::class);
        $this->registerProp('ratings', RawGenericType::class, true);
        $this->registerProp('user_rating', IntGenericType::class);
        $this->registerProp('video', StringGenericType::class);
        $this->registerProp('rating', IntGenericType::class);

        $this->registerRelation('author', ByLineModel::class);
        $this->registerRelation('app', AppModel::class);
        $this->registerRelation('space', SpaceModel::class);

        $this->registerRelation('children', AppMarketShareModel::class, static::RELATION_MANY);
        $this->registerRelation('parents', AppMarketShareModel::class, static::RELATION_MANY);
        $this->registerRelation('screenshots', FileModel::class, static::RELATION_MANY);
        $this->registerRelation('comments', CommentModel::class, static::RELATION_MANY);
    }
}
