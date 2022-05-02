<?php

namespace Juanparati\Podium\Auths;

use GuzzleHttp\Client;
use JetBrains\PhpStorm\ArrayShape;

abstract class AuthBase implements Authenticator
{
    /**
     * Http client used in the authentication process.
     *
     * @var Client
     */
    protected Client $httpClient;


    /**
     * Podio client id.
     *
     * @var string|null
     */
    protected ?string $clientId = null;


    /**
     * Podio client secret.
     *
     * @var string|null
     */
    protected ?string $clientSecret = null;


    /**
     * Inject http client.
     *
     * @param Client $client
     * @return $this
     */
    public function injectHttpClient(Client $client) : static
    {
        $this->httpClient = $client;

        return $this;
    }


    /**
     * Set the client Id.
     *
     * @param string $clientId
     * @return $this
     */
    public function setClientId(string $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }


    /**
     * Set the client secret.
     *
     * @param string $clientSecret
     * @return $this
     */
    public function setClientSecret(string $clientSecret) : static
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }


    /**
     * Get authentication response.
     *
     * @return array
     */
    abstract public function getAuthentication() : array;
}