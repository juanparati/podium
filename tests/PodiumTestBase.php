<?php

namespace Juanparati\Podium\Tests;

use Illuminate\Cache\ArrayStore;
use Juanparati\Podium\Loggers\StdOutLogger;
use Juanparati\Podium\Podium;
use PHPUnit\Framework\TestCase;

abstract class PodiumTestBase extends TestCase
{

    /**
     * Podium client instance.
     *
     * @var Podium
     */
    protected static Podium $client;


    protected static $credentials;


    /**
     * Execute before all tests.
     *
     * @throws \Exception
     */
    public static function setUpBeforeClass(): void
    {
        // Note: Use the environment variable PODIUM_CREDENTIALS (Values are passes as a JSON string).
        if (!$cred= getenv('PODIUM_CREDENTIALS'))
            throw new \Exception('Please provide the credentials into the PODIOUM_CREDENTIALS environment variable');

        static::$credentials = json_decode($cred);

        if (JSON_ERROR_NONE !== json_last_error())
            throw new \Exception('Podium credentials are not correctly formatted');

        static::$client = new Podium(
            'default',
            static::$credentials->client_id,
            static::$credentials->client_secret,
            cache: new ArrayStore(),
            logger: new StdOutLogger()
        );
    }

}