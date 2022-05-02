<?php

namespace Juanparati\Podium\Auths;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Juanparati\Podium\Exceptions\AuthenticationException;

/**
 * App authentication method for Podio.
 *
 * @see: https://developers.podio.com/authentication/app_auth
 */
class AppAuth extends AuthBase
{

    /**
     * HTTP client used for authentication.
     *
     * @var Client
     */
    protected Client $httpClient;


    /**
     * Constructor.
     *
     * @param string $appId
     * @param string $appToken
     * @param string $endpoint
     */
    public function __construct(
        protected string $appId,
        protected string $appToken,
        protected string $route = 'oauth/token'
    ) {}


    /**
     * Get authentication.
     *
     * @return array{
     *     access_token: string,
     *     expires_in: int,
     *     token_type: string,
     *     scope: string,
     *     ref: array{type: string, id: int},
     *     refresh_token: string
     * }
     * @throws AuthenticationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuthentication(): array
    {
        $response = $this->httpClient->post($this->route, [
            RequestOptions::FORM_PARAMS => [
                'grant_type'    => 'app',
                'app_id'        => $this->appId,
                'app_token'     => $this->appToken,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new AuthenticationException('Unable to obtain access token for AppId: ' . $this->appId);
        }

        $auth = json_decode($response->getBody(), true);

        if (JSON_ERROR_NONE !== json_last_error())
            throw new AuthenticationException(json_last_error_msg());

        return $auth;
    }
}