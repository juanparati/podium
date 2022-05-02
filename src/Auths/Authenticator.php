<?php

namespace Juanparati\Podium\Auths;

use GuzzleHttp\Client;

interface Authenticator
{
    /**
     * Inject HTTP client.
     *
     * @param Client $client
     * @return static
     */
    public function injectHttpClient(Client $client) : static;

    /**
     * Get authentication response.
     *
     * @return array
     */
    public function getAuthentication() : array;


    /**
     * Set the Client Id.
     *
     * @param string $clientId
     * @return static
     */
    public function setClientId(string $clientId) : static;


    /**
     * Set the Client secret.
     *
     * @param string $clientSecret
     * @return static
     */
    public function setClientSecret(string $clientSecret) : static;
}