<?php

namespace Juanparati\Podium\Tests\test;

use Juanparati\Podium\Auths\AppAuth;
use Juanparati\Podium\Models\ItemFilterModel;
use Juanparati\Podium\Models\ItemModel;
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


        $itemFilter = (new ItemFilterModel([], static::$client))
            ->setLimit(2)
            ->filter(static::$credentials->app_id);

        foreach ($itemFilter->items() as $item) {
            echo $item->fields->title . PHP_EOL;
        }

        /*
        $item = new ItemModel([], static::$client);

        $itemModel = $item->request()->get();
        */
    }

}