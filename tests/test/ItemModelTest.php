<?php

namespace Juanparati\Podium\Tests\test;

use Juanparati\Podium\Models\ItemFieldModel;
use Juanparati\Podium\Models\ItemFields\DateItemField;
use Juanparati\Podium\Models\ItemFields\MoneyItemField;
use Juanparati\Podium\Models\ItemModel;
use PHPUnit\Framework\TestCase;

class ItemModelTest extends TestCase
{

    /**
     * Test structure integrity.
     *
     * @return void
     */
    public function testItemModelOriginalStructure() {

        $rawItem = json_decode(file_get_contents(__DIR__ . '/../assets/item.json'), true);

        $model = new ItemModel($rawItem);

        $this->assertEquals(static::cleanEmptyElements($rawItem), static::cleanEmptyElements($model->originalValues()));

        $decodedSpec = json_decode(file_get_contents(__DIR__ . '/../assets/item_dec_spec.json'), true);
        $this->assertEquals($decodedSpec, $model->decodeValue());
    }


    /**
     * Test decode options.
     *
     * @return void
     */
    public function testItemModelDecodeOptions() {
        $rawItem = json_decode(file_get_contents(__DIR__ . '/../assets/item.json'), true);

        $decoded = (new ItemModel($rawItem))
            ->setOptions([
                ItemFieldModel::class => [
                    ItemFieldModel::OPTION_KEY_AS => ItemFieldModel::KEY_AS_FIELD_ID,
                    DateItemField::class => [
                        DateItemField::OPTION_TIMEZONE => 'Europe/Bucharest',
                        DateItemField::OPTION_FORMAT => DateItemField::FORMAT_TIMESTAMP
                    ]
                ],
            ])
            ->decodeValue();

        $this->assertArrayHasKey('238632570', $decoded['fields']);
        $this->assertEquals(1648886400, $decoded['fields']['238634987']['values']['start']);
        $this->assertNull($decoded['fields']['238634987']['values']['end']);
    }


    public function testItemModelMutability() {
        $rawItem = json_decode(file_get_contents(__DIR__ . '/../assets/item.json'), true);

        $model = (new ItemModel($rawItem))
            ->setOptions([
                ItemFieldModel::class => [
                    ItemFieldModel::OPTION_KEY_AS => ItemFieldModel::KEY_AS_FIELD_ID
                ]
            ]);

        $model->fields->{'238632570'} = 'test_1';
        $model->fields->{'238632584'} = ['Two'];
        $fields = $model->fields->toArray();

        $this->assertEquals([['value' => 'test_1']], $fields[238632570]->originalValues()['values']);
    }


    public function testDirtyFields() {
        $rawItem = json_decode(file_get_contents(__DIR__ . '/../assets/item.json'), true);

        $model = new ItemModel($rawItem);
        $model->fields['title'] = 'Changed';
        $model->fields['categoryfield'] = ['Two'];
        $model->fields['moneyfield'] = new MoneyItemField(['currency' => 'DKK', 'value' => 1223.22]);

        $fields = $model->decodeValueForPost();

        $this->assertNotNull($fields['fields']['title']['values']);
        $this->assertNotNull($fields['fields']['categoryfield']['values']);
        $this->assertNotNull($fields['fields']['moneyfield']['values']);

        $this->assertNull($fields['fields']['datefield']['values']);
        $this->assertNull($fields['fields']['phonefield']['values']);
        $this->assertNull($fields['fields']['locationfield']['values']);


        // Test when came back to original values.
        $model->fields['categoryfield'] = ['One'];
        $model->fields['title'] = 'This is TextField';

        $fields = $model->decodeValueForPost();

        $this->assertNull($fields['fields']['title']['values']);
        $this->assertNull($fields['fields']['categoryfield']['values']);
        $this->assertNull($fields['fields']['datefield']['values']);
        $this->assertNull($fields['fields']['phonefield']['values']);
        $this->assertNull($fields['fields']['locationfield']['values']);

        // Test after the model was reset
        $model->fields['phonefield'] = [['type' => 'work', 'value' => '23344566']];

        $fields = $model->decodeValueForPost();
        $this->assertNotNull($fields['fields']['phonefield']['values']);
    }



    public function testSnakeCaseKey() {
        $rawItem = json_decode(file_get_contents(__DIR__ . '/../assets/item.json'), true);

        $model = (new ItemModel($rawItem))
            ->setOptions([
                ItemFieldModel::class => [
                    ItemFieldModel::OPTION_KEY_AS => ItemFieldModel::KEY_AS_SNAKECASE,
                ],
            ]);


        $this->assertArrayHasKey('title_second', $model->fields->decodeValue());
    }



    /**
     * Helper that removes empty values from an array.
     *
     * @param array $array
     * @return array
     */
    protected static function cleanEmptyElements(array $array) : array {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::cleanEmptyElements($value);
            }

            if (empty($value)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

}