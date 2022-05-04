<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Models\ActionModel;

class ActionRequest extends RequestBase
{

    /**
     * https://developers.podio.com/doc/actions/get-action-1701120
     *
     * @param string|int $actionId
     * @return ActionModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function get(string|int $actionId) {
        return new ActionModel(
            $this->podium->request(static::METHOD_GET, " /action/{{$actionId}"),
            $this->podium
        );
    }
}