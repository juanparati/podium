<?php

namespace Juanparati\Podium\Tests\test;

use Juanparati\Podium\Auths\AppAuth;
use Juanparati\Podium\Tests\PodiumTestBase;

class AuthenticationTest extends PodiumTestBase
{

    /**
     * Test authentication.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAuthentication() {
        static::$client->authenticate(
            new AppAuth(static::$credentials->app_id, static::$credentials->app_token)
        );

        // Not exception is raised.
        $this->assertTrue(true);
    }

}