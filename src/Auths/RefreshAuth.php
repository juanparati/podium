<?php

namespace Juanparati\Podium\Auths;

use GuzzleHttp\RequestOptions;
use Juanparati\Podium\Exceptions\AuthenticationException;

class RefreshAuth extends AuthBase
{

    /**
     * Constructor.
     *
     * @param string $refreshToken
     * @param string $endpoint
     */
    public function __construct(
        private string   $refreshToken,
        protected string $endpoint = 'https://api.podio.com/oauth/token'
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
        $response = $this->httpClient->post($this->endpoint, [
            RequestOptions::FORM_PARAMS => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $this->refreshToken,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new AuthenticationException('Unable to refresh access token');
        }

        $auth = json_decode($response->getBody(), true);

        if (JSON_ERROR_NONE !== json_last_error())
            throw new AuthenticationException(json_last_error_msg());

        return $auth;
    }
}