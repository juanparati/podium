<?php

namespace Juanparati\Podium\Models;

use Illuminate\Support\Arr;
use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Models\Generics\RawGenericType;
use Juanparati\Podium\Models\Generics\StringGenericType;
use Juanparati\Podium\Models\Generics\UndefinedGenericType;
use Juanparati\Podium\Models\ItemFields\AppItemField;
use Juanparati\Podium\Models\ItemFields\CalculationItemField;
use Juanparati\Podium\Models\ItemFields\CategoryItemField;
use Juanparati\Podium\Models\ItemFields\DateItemField;
use Juanparati\Podium\Models\ItemFields\DurationItemField;
use Juanparati\Podium\Models\ItemFields\EmailItemField;
use Juanparati\Podium\Models\ItemFields\ImageItemField;
use Juanparati\Podium\Models\ItemFields\ItemFieldContract;
use Juanparati\Podium\Models\ItemFields\LocationItemField;
use Juanparati\Podium\Models\ItemFields\MoneyItemField;
use Juanparati\Podium\Models\ItemFields\NumberItemField;
use Juanparati\Podium\Models\ItemFields\PhoneItemField;
use Juanparati\Podium\Models\ItemFields\TextItemField;
use Juanparati\Podium\Podium;


class ItemFieldModel extends ModelBase
{

    /**
     * Field states.
     */
    const STATE_ENABLED = 'enabled';
    const STATE_DISABLED = 'disabled';


    /**
     * Field key resolution options.
     */
    const KEY_AS_EXTERN_ID = 'external_id';
    const KEY_AS_FIELD_ID = 'field_id';


    /**
     * Options.
     */
    const OPTION_KEY_AS = 'key_as';
    const OPTION_FIELDS = 'fields';



    /**
     * Default options.
     *
     * @var array
     */
    protected array $__options = [self::OPTION_KEY_AS => self::KEY_AS_EXTERN_ID];



    /**
     * Constructor.
     *
     * @param array $attr
     * @param Podium|null $podium
     */
    public function init() : void
    {
        $this->registerProp('field_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('type', StringGenericType::class);
        $this->registerProp('external_id', StringGenericType::class);
        $this->registerProp('values', UndefinedGenericType::class);
        $this->registerProp('label', StringGenericType::class);
        $this->registerProp('config', RawGenericType::class);
        $this->registerProp('status', StringGenericType::class);
    }


    /**
     * Set values.
     *
     * @param string $attr
     * @param $value
     * @return void
     * @throws \Juanparati\Podium\Exceptions\DataIntegrityException
     */
    public function setPropValue(string $attr, $value) : static
    {
        if ($this->__props['values']['type'] === UndefinedGenericType::class
            && !empty($this->__props['type']['value'])
            && !empty($this->__props['config']['value'])
        ) {

            $originalValues = $attr === 'values' ? $value : ($this->__props['values']['value'] === null ? null : $this->__props['values']['value']->getProps());

            if ($originalValues) {

                $config = $this->__props['config']['value']->getProps();
                $this->__props['values']['wrapper'] = '';

                $this->setFieldBasicAttr($this->__props['type']['value']->getProps());

                switch ($this->__props['type']['value']->getProps()) {
                    case 'category':
                        $this->__props['values']['value'] = array_map(
                            fn($cat) => (new CategoryItemField())->setConfig($config)->fillProps($cat),
                            $originalValues
                        );
                        break;

                    case 'date':
                        $this->__props['values']['value'] = (new DateItemField())->setConfig($config)->fillProps($originalValues[0]);

                        break;

                    case 'contact':
                        $this->__props['values']['value'] = array_map(
                            fn($contact) => new ContactModel($contact, $this->podium),
                            Arr::flatten($originalValues, 1)
                        );

                        break;

                    case 'email':
                        $this->__props['values']['value'] = array_map(
                            fn($phone) => (new EmailItemField())->setConfig($config)->fillProps($phone),
                            $originalValues
                        );

                        break;

                    case 'phone':
                        $this->__props['values']['value'] = array_map(
                            fn($phone) => (new PhoneItemField())->setConfig($config)->fillProps($phone),
                            $originalValues
                        );

                        break;

                    case 'number':
                        $this->__props['values']['value'] = (new NumberItemField())
                            ->setConfig($config)
                            ->fillProps($originalValues[0] ?? ['value' => $config['default_value']]);

                        break;

                    case 'app':
                        $this->__props['values']['value'] = array_map(
                            fn($image) => (new AppItemField())->setConfig($config)->fillProps($image),
                            $originalValues
                        );

                        break;


                    case 'embed':
                        $this->__props['values']['value'] = array_map(
                            fn($embed) => new EmbedFileModel($embed),
                            $originalValues
                        );

                        break;

                    case 'image':
                        $this->__props['values']['value'] = array_map(
                            fn($image) => (new ImageItemField())->setConfig($config)->fillProps($image),
                            $originalValues
                        );

                        break;

                    case 'money':
                        $this->__props['values']['value'] = (new MoneyItemField())
                            ->setConfig($config)
                            ->fillProps($originalValues[0]);

                        break;


                    case 'calculation':
                        $this->__props['values']['value'] = (new CalculationItemField())
                            ->setConfig($config)
                            ->fillProps($originalValues[0]);

                        break;

                    case 'location':
                        $this->__props['values']['value'] = (new LocationItemField())
                            ->setConfig($config)
                            ->fillProps($originalValues[0]);

                        break;

                    case 'duration':
                        $this->__props['values']['value'] = (new DurationItemField())
                            ->setConfig($config)
                            ->fillProps($originalValues[0]);

                        break;

                    case 'text':
                        $this->__props['values']['value'] = (new TextItemField())
                            ->setConfig($config)
                            ->fillProps($originalValues[0]);
                }
            }
        }

        return parent::setPropValue($attr, $value);
    }


    /**
     * Override original values.
     *
     * @return array
     */
    public function originalValues(): array
    {
        $props = parent::originalValues();
        $data = [];

        foreach ($props as $propName => $propValue) {

            if ($propName === 'values') {
                if (!empty($this->__props['values']['wrapper'])) {
                    $propValue = array_map(fn($item) => [$this->__props['values']['wrapper'] => $item], $propValue);
                }
                else if (!$this->__props['values']['isArray'])
                    $propValue = [$propValue];
            }

            $data[$propName] = $propValue;
        }

        return $data;
    }


    /**
     * Set values in raw format.
     *
     * @param mixed $values
     * @return void
     */
    public function setRawValue(mixed $values) : void {

        $this->setFieldBasicAttr($this->__props['type']['value']->getProps());

        $type = $this->__props['values']['type'];
        $config = $this->__props['config']['value']->getProps();

        if ($this->__props['values']['isArray'] ?? false) {
            $this->__props['values']['value'] = [];

            foreach ($values as $value) {
                if ($value instanceof ItemFieldContract) {
                    $this->__props['values']['value'][] = $value;
                } else {
                    $this->__props['values']['value'][] = (new $type())
                        ->setConfig($config)
                        ->encodeValue($value);
                }
            }
        } else {

            if ($values instanceof ItemFieldContract)
                $this->__props['values']['value'] = $values;
            else {
                $this->__props['values']['value'] = (new $type())
                    ->setConfig($config)
                    ->encodeValue($values);
            }
        }
    }


    /**
     * Set/Get key resolution.
     *
     * @param string|null $keyAs
     * @return string
     */
    public function keyResolution(?string $keyAs = null) : string
    {
        if ($keyAs)
            $this->__options[static::OPTION_KEY_AS] = $keyAs;

        return $this->__options[static::OPTION_KEY_AS];
    }


    /**
     * Decode key in a custom way.
     *
     * @return string
     */
    public function decodeKey() : string {
        $key =  $this->__props[$this->__options[static::OPTION_KEY_AS]]['value']->getProps();
        return (string) $key;
    }


    /**
     * Set basic attributes for a field.
     *
     * @param string $type
     * @return void
     */
    protected function setFieldBasicAttr(string $type) {

        switch ($type) {
            case 'category':
                $this->__props['values']['type'] = CategoryItemField::class;
                $this->__props['values']['isArray'] = true;
                break;

            case 'date':
                $this->__props['values']['type'] = DateItemField::class;
                break;

            case 'contact':
                $this->__props['values']['type'] = ContactModel::class;
                $this->__props['values']['isArray'] = true;
                $this->__props['values']['wrapper'] = 'value';
                break;

            case 'email':
                $this->__props['values']['type'] = EmailItemField::class;
                $this->__props['values']['isArray'] = true;
                break;

            case 'phone':
                $this->__props['values']['type'] = PhoneItemField::class;
                $this->__props['values']['isArray'] = true;
                break;

            case 'number':
                $this->__props['values']['type'] = NumberItemField::class;
                break;

            case 'app':
                // @ToDo: Refactor AppItemModel in the future
                $this->__props['values']['type'] = AppItemField::class;
                $this->__props['values']['isArray'] = true;
                break;

            case 'embed':
                $this->__props['values']['type'] = EmbedFileModel::class;
                $this->__props['values']['isArray'] = true;
                break;

            case 'image':
                $this->__props['values']['type'] = ImageItemField::class;
                $this->__props['values']['isArray'] = true;
                break;

            case 'money':
                $this->__props['values']['type'] = MoneyItemField::class;
                break;

            case 'calculation':
                $this->__props['values']['type'] = CalculationItemField::class;
                break;

            case 'location':
                $this->__props['values']['type'] = LocationItemField::class;
                break;

            case 'duration':
                $this->__props['values']['type'] = DurationItemField::class;
                break;

            case 'text':
                $this->__props['values']['type'] = TextItemField::class;
                break;
        }
    }
}