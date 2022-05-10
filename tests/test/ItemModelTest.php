<?php

namespace Juanparati\Podium\Tests\test;

use Juanparati\Podium\Models\ItemFieldModel;
use Juanparati\Podium\Models\ItemFields\DateItemField;
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