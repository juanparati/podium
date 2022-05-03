<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Models\AppModel;

class AppRequest extends RequestBase
{
    public function get(string|int $appId) {
        return new AppModel(
            $this->podium->request(static::METHOD_GET, "/app/{$appId}"),
            $this->podium
        );
    }
}