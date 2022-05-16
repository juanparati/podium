<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Models\HookModel;

class HookRequest extends RequestBase
{

    /**
     * @see https://developers.podio.com/doc/hooks/validate-hook-verification-215241
     * @param string|int $hookId
     * @param string $code
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function validate(string|int $hookId, string $code) : array {
        return $this->podium->request(
            static::METHOD_POST,
            "/hook/{$hookId}/verify/validate",
            ['code' => $code]
        );
    }


    /**
     * @see https://developers.podio.com/doc/hooks/delete-hook-215291
     * @param string|int $hookId
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function delete(string|int $hookId) : bool {
        return $this->podium->request(
            static::METHOD_DELETE,
            "/hook/{$hookId}",
        );
    }


    /**
     * @see https://developers.podio.com/doc/hooks/request-hook-verification-215232
     * @param string|int $hookId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function verify(string|int $hookId) : array {
        return $this->podium->request(
            static::METHOD_POST,
            "/hook/{$hookId}/verify/request",
        );
    }


    /**
     * @see https://developers.podio.com/doc/hooks/create-hook-215056
     *
     * @param string|int $appId
     * @param string $url
     * @param string $type
     * @param string $refType
     * @return HookModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function create(string|int $appId, string $url, string $type, string $refType = 'app') : HookModel {
        $response = $this->podium->request(
            static::METHOD_POST,
            "/hook/{$refType}/{$appId}/",
            [
                'url' => $url,
                'type' => $type
            ]
        );

        return new HookModel([
            'hook_id' => $response['hook_id'],
            'status'  => 'inactive',
            'type'    => $type,
            'url'     => $url
        ], $this->podium);
    }


    /**
     * @see https://developers.podio.com/doc/hooks/get-hooks-215285
     * @param string|int $appId
     * @param string $refType
     * @return HookModel[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Podium\Exceptions\AuthenticationException
     * @throws \Juanparati\Podium\Exceptions\InvalidRequestException
     * @throws \Juanparati\Podium\Exceptions\RateLimitException
     */
    public function get(string|int $appId, string $refType = 'app') : array {
        $hooks = $this->podium->request(
            static::METHOD_GET,
            "/hook/{$refType}/{$appId}"
        );

        return $hooks ? array_map(fn($hook) => new HookModel($hook), $hooks) : [];
    }

}