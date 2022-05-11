<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Models\AppModel;

class AppRequest extends RequestBase
{

    public function get(string|int $appId) : ?AppModel {

        $response = $this->podium->request(static::METHOD_GET, "/app/{$appId}");

        return $response ? new AppModel($response, $this->podium) : null;
    }
}