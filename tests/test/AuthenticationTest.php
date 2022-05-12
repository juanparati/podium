<?php

namespace Juanparati\Podium\Tests\test;

use Juanparati\Podium\Auths\AppAuth;
use Juanparati\Podium\Models\ItemFields\PhoneItemField;
use Juanparati\Podium\Models\ItemFilterModel;
use Juanparati\Podium\Models\ItemModel;
use Juanparati\Podium\Requests\ItemRequest;
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



        //$model = (new ItemRequest(static::$client))->get(2078296577)->loadAppSchema('27443515');

        $model = (new ItemRequest(static::$client))->get(2092728339);
        $model->fields->phonefield = [new PhoneItemField(['type' => 'work', 'value' => '44556667'])];
        die(var_dump($model->decodeValue()));

        /*
        $model->fields->multiplecategoryfield = ['Two', 'Three'];
        $model->fields->title = 'test';
        $model->save();
        */


        /*
        $model = new ItemModel([], static::$client);
        $model->prepareNew(27443515);
        $model->fields->title = 'foobar';
        $model->fields->multiplecategoryfield = ['One'];
        $model->save();
        */


        /*
        $itemFilter = (new ItemFilterModel([], static::$client))
            ->setLimit(2)
            ->setSortDesc(true)
            ->filter(static::$credentials->app_id);

        foreach ($itemFilter->items() as $item) {
            echo $item->fields->title . PHP_EOL;
        }
        */

        /*
        $item = new ItemModel([], static::$client);

        $itemModel = $item->request()->get();
        */
    }

}