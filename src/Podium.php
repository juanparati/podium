<?php

namespace Juanparati\Podium;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Cache\ArrayStore;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Carbon;
use Juanparati\Podium\Auths\Authenticator;
use Juanparati\Podium\Auths\RefreshAuth;
use Juanparati\Podium\Exceptions\AuthenticationException;
use Juanparati\Podium\Exceptions\InvalidRequestException;
use Juanparati\Podium\Exceptions\RateLimitException;
use Juanparati\Podium\Loggers\NullLogger;
use Psr\Log\LoggerInterface;


class Podium
{

    const VERSION = '0.9.x';


    /**
     * Available request methods.
     */
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';



    /**
     * Cache store.
     *
     * @var Store
     */
    protected Store $cache;


    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;


    /**
     * HTTP client.
     *
     * @var Client
     */
    protected Client $httpClient;


    /**
     * Authenticator.
     *
     * @var Authenticator|null
     */
    private ?Authenticator $authenticator = null;


    /**
     * Constructor.
     *
     * @param string $session
     * @param string $clientId
     * @param string $clientSecret
     * @param Store|null $cache
     * @param LoggerInterface|null $logger
     * @param string $baseUri
     */
    public function __construct(
        protected string $session,
        private string   $clientId,
        private string   $clientSecret,
        Store            $cache = null,
        ?LoggerInterface $logger = null,
        string           $baseUri = 'https://api.podio.com/'
    )
    {
        $this->cache = $cache ?: new ArrayStore(true);
        $this->logger = $logger ?: new NullLogger();

        $this->httpClient = new Client([
            'base_uri'                  => $baseUri,
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS     => [
                'Accept'       => 'application/json',
                'User-Agent'   => 'Podium Client/' . static::VERSION,
            ]
        ]);
    }


    /**
     * Perform the authentication process.
     *
     * @param Authenticator $authenticator
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticate(
        Authenticator $authenticator
    ): array
    {

        $this->authenticator = $authenticator;

        // Try to obtain the token using the refresh token.
        if ($auth = $this->isAuthenticated()) {
            try {
                $auth = (new RefreshAuth($auth['refresh_token']))
                    ->injectHttpClient($this->httpClient)
                    ->setClientId($this->clientId)
                    ->setClientSecret($this->clientSecret)
                    ->getAuthentication();

            } catch (AuthenticationException $e) {
                $auth = null;

                $this->logger->warning('Unable to obtain access token', [
                    'client_id' => $this->clientId,
                    'grant'     => 'refresh_token',
                    'error'     => $e->getMessage()
                ]);
            }
        }

        if (!$auth) {
            $auth = $this->authenticator
                ->injectHttpClient($this->httpClient)
                ->setClientId($this->clientId)
                ->setClientSecret($this->clientSecret)
                ->getAuthentication();
        }

        // Save authentication into the cache
        $auth['expires_at'] = Carbon::now()->addSeconds($auth['expires_in'])->toDateTimeString();
        $this->cache->put('auth', $auth, $auth['expires_in'] * 2);

        return $auth;
    }


    /**
     * Forget authentication.
     *
     * @return void
     */
    public function clearAuthentication(): void
    {
        $this->cache->forget('auth');
    }


    /**
     * Verify if current token was expired.
     *
     * Returns the authentication response in case that authentication is available.
     *
     * @return array|null
     */
    public function isAuthenticated(): ?array
    {
        if ($auth = $this->cache->get('auth')) {
            if (Carbon::now()->lessThan($auth['expires_at']))
                return $auth;
        }

        return null;
    }


    /**
     * Perform low level request.
     *
     * @param string $method
     * @param string $url
     * @param array $attributes
     * @param array $options
     * @param bool $rawResult
     * @return mixed
     * @throws AuthenticationException
     * @throws InvalidRequestException
     * @throws RateLimitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(
        string $method,
        string $url,
        array $attributes = [],
        array $options = [],
        bool $rawResult = false
    ) : mixed
    {
        $method = strtoupper($method);

        /**
         * @var Client $client
         */
        $client = clone $this->httpClient;


        if (!($auth = $this->isAuthenticated())) {
            $auth = $this->authenticate($this->authenticator);
        }

        $headers = [
            'Authorization' => 'OAuth2 ' . $auth['access_token']
        ];

        $response = match ($method) {
            static::METHOD_POST => $client->post($url, [
                RequestOptions::JSON  => $attributes,
                RequestOptions::QUERY => $options,
                RequestOptions::HEADERS => $headers,
            ]),

            static::METHOD_PUT => $client->put($url, [
                RequestOptions::JSON  => $attributes,
                RequestOptions::QUERY => $options,
                RequestOptions::HEADERS => $headers,
            ]),

            static::METHOD_DELETE => $client->delete($url, [
                RequestOptions::JSON  => $attributes,
                RequestOptions::QUERY => $options,
                RequestOptions::HEADERS => $headers,
            ]),

            default => $client->get($url, [
                RequestOptions::QUERY => $options,
                RequestOptions::HEADERS => $headers
            ]),
        };


        switch ($response->getStatusCode()) {
            case 200:
            case 201:
            case 202:
                return $rawResult ? $response->getBody() : json_decode($response->getBody(), true);

            case 204:
                return true;

            case 400:
            case 401:
                $result = json_decode($response->getBody(), true);

                if (JSON_ERROR_NONE === json_last_error()) {
                    $error = $result['error'] ?? null;

                    if ('invalid_grant' === $error) {
                        throw new AuthenticationException(
                            'Invalid grant: ' . $response->getBody(),
                            $response->getStatusCode()
                        );
                    }

                    if ('invalid_token' === $error || 'expired_token' === ($result['error_description'] ?? null)) {
                        $this->clearAuthentication();

                        if ($this->authenticate($this->authenticator))
                            return $this->request($method, $url, $attributes, $options, $rawResult);
                        else
                            throw new AuthenticationException(
                                'Unable to perform authentication on request',
                                $response->getStatusCode()
                            );
                    }
                }

                break;

            case 403:
                throw new RateLimitException('Rate limit: ' . $response->getBody());
                break;

            case 404:
                $result = json_decode($response->getBody(), true);

                if (JSON_ERROR_NONE === json_last_error()) {
                    $error = $result['error'] ?? null;

                    if ('not_found' === $error)
                        return null;
                }

                break;
        }

        throw new InvalidRequestException(
            'Invalid request: ' . $response->getBody(),
            $response->getStatusCode()
        );
    }


    /**
     * Return the current session.
     *
     * @return string
     */
    public function getSession(): string
    {
        return $this->session;
    }


    /**
     * Return the cache store.
     *
     * @return Store
     */
    public function getCacheStore(): Store
    {
        return $this->cache;
    }
}