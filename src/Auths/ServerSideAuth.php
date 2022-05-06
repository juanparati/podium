<?php

namespace Juanparati\Podium\Auths;

use GuzzleHttp\Client;
use Juanparati\Podium\Exceptions\AuthenticationException;


/**
 * @see: https://developers.podio.com/authentication/server_side
 */
class ServerSideAuth extends AuthBase
{

    /**
     * HTTP client used for authentication.
     *
     * @var Client
     */
    protected Client $httpClient;


    /**
     * Refresh token.
     *
     * @var string|null
     */
    protected ?string $authCode = null;



    /**
     * Constructor.
     *
     * @param string $redirectUrl
     * @param array $scopes
     * @param string $route
     */
    public function __construct(
        protected string  $redirectUrl = 'http://localhost:8000/auth_response.php',
        protected array   $scopes = ['global' => ['all']],
        protected string  $route = 'oauth/token'
    ) {}



    public function setAuthCode(string $authCode) {
        $this->authCode = $authCode;
    }



    /**
     * Get authentication token.
     *
     * @return array
     * @throws AuthenticationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuthentication(): array
    {

        if (!$this->authCode) {
            $this->performAuthorization();
            return [];
        }

        $response = $this->httpClient->post($this->route, [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $this->getAuthCode(),
            'redirect_uri'  => $this->redirectUrl,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new AuthenticationException('Unable to obtain access token');
        }

        $auth = json_decode($response->getBody(), true);

        if (JSON_ERROR_NONE !== json_last_error())
            throw new AuthenticationException(json_last_error_msg());

        return $auth;
    }


    /**
     * Obtain access code.
     *
     * @param string $authCode
     * @return array
     * @throws AuthenticationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function obtainAccessToken(string $authCode) : array {

        $response = $this->httpClient->post($this->route, [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $authCode,
            'redirect_uri'  => $this->redirectUrl,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new AuthenticationException('Unable to obtain access token');
        }

        $auth = json_decode($response->getBody(), true);

        if (JSON_ERROR_NONE !== json_last_error())
            throw new AuthenticationException(json_last_error_msg());

        return $auth;
    }


    /**
     * Perform Authorization.
     *
     * @param string $url
     * @return void
     */
    public function performAuthorization(string $url = 'https://podio.com/oauth/authorize') {
        $url .= '?' . http_build_query([
            'response_type' => 'code',
            'client_id'    => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope'        => collect($this->scopes)
                ->map(fn($item, $key) => collect($item)->map(fn($scope) => $key . ':' .$scope))
                ->flatten()
                ->unique()
                ->join(' ')
        ]);

        if ('cli' === php_sapi_name()) {
            $urlSegments = parse_url($this->redirectUrl);
            $cmd = PHP_BINARY . ' -S ' . $urlSegments['host'] . ':' . $urlSegments['port'] . ' -t ' . realpath(__DIR__ . '/../../utils');
            echo "Podio authorization" . PHP_EOL;
            echo "===================" . PHP_EOL . PHP_EOL;
            echo "Please copy and paste the following URL in your web browser:" . PHP_EOL;
            echo $url . PHP_EOL . PHP_EOL;
            echo "Executing PHP internal webserver:" . PHP_EOL;
            echo $cmd . PHP_EOL;
            echo 'php -S ' . $urlSegments['host'] . ':' . $urlSegments['port'] . ' -t ' . realpath(__DIR__ . '/../../utils') . PHP_EOL;
            system($cmd);
        } else {
            header("Location: $url");
        }
    }


    /**
     * Handle the authorization response (CLI version).
     *
     * @return void
     */
    public static function handleAuthorizationResponse() : void {
        if (isset($_GET['error_reason'])) {
            echo 'Error requesting authorization token, reason: ' . ($_GET['error'] ?? 'unknown') . '<br />';
            die($_GET['error_description'] ?? '');
        }

        if (empty($_GET['code'])) {
            die("Unable to obtain code");
        }

        $authCode = trim($_GET['code']);

        echo "🔑 Congratulations, the authorization code was retrieved!<br />";
        echo "<pre><code>$authCode</code></pre>";
    }
}