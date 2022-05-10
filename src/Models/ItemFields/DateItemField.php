<?php

namespace Juanparati\Podium\Models\ItemFields;

use Illuminate\Support\Carbon;
use Juanparati\Podium\Models\ItemFieldModel;

class DateItemField extends ItemFieldBase
{

    /**
     * Options.
     */
    const OPTION_TIMEZONE = 'timezone';
    const OPTION_FORMAT   = 'format';


    /**
     * Decode format.
     */
    const FORMAT_STRING    = 'string';
    const FORMAT_TIMESTAMP = 'timestamp';
    const FORMAT_OBJ       = 'obj';


    /**
     * Default options.
     *
     * @var array|string[]
     */
    protected array $options = [
        self::OPTION_TIMEZONE => 'UTC',
        self::OPTION_FORMAT   => self::FORMAT_STRING,
    ];


    /**
     * Constructor.
     */
    public function __construct(mixed $value = null) {
        $this->options[static::OPTION_TIMEZONE] = date_default_timezone_get();
        parent::__construct($value);
    }


    /**
     * Set timezone using for decode.
     *
     * @param string $timezone
     * @return $this
     */
    public function setTimezone(string $timezone) : static {
        $this->options[static::OPTION_TIMEZONE] = $timezone;

        return $this;
    }


    /**
     * Get timezone using for decode.
     *
     * @return string
     */
    public function getTimezone() : string {
        return $this->options[static::OPTION_TIMEZONE];
    }


    public function setOptions(array $options): static
    {
        return parent::setOptions($options);
    }


    public function decodeValue(): mixed
    {
        if ($this->value === null)
            return null;

        $dates = [
            'start' => $this->value['start'],
            'end'   => $this->value['end'] ?? null
        ];

        foreach ($dates as &$date) {
            if ($date === null)
                continue;

            $date = Carbon::parse($date);

            if ($this->options[static::OPTION_TIMEZONE] !== 'UTC')
                $date->setTimezone($this->options[static::OPTION_TIMEZONE]);

            if ($this->config['settings']['time'] === ItemFieldModel::STATE_DISABLED) {
                $date = match ($this->options[static::OPTION_FORMAT]) {
                    static::FORMAT_OBJ => $date->toDate(),
                    static::FORMAT_TIMESTAMP => $date->getTimestamp(),
                    default => $date->toDateString()
                };
            }
            else {
                $date = match ($this->options[static::OPTION_FORMAT]) {
                    static::FORMAT_OBJ => $date->toDateTime(),
                    static::FORMAT_TIMESTAMP => $date->getTimestamp(),
                    default => $date->toDateTimeString()
                };
            }
        }

        return $dates;
    }


    /**
     * Encode value.
     *
     * @param array $value
     * @return $this
     */
    public function encodeValue(mixed $value): static
    {
        foreach (['start', 'end'] as $datePosition) {
            if (isset($value[$datePosition])) {
                $this->value[$datePosition] = Carbon::parse($value[$datePosition]);

                if ($this->options[static::OPTION_TIMEZONE] !== 'UTC')
                    $this->value[$datePosition]->setTimezone('UTC');

                $this->value[$datePosition] = $this->value[$datePosition]->toDateTimeString();
            }
        }

        return $this;
    }


    /**
     * Decode for POST/PUT operations.
     *
     * @return mixed
     */
    public function decodeValueForPost(): mixed
    {
        if ($this->value === null)
            return null;

        $dates = [
            'start' => $this->value['start'],
            'end'   => $this->value['end'] ?? null
        ];

        foreach ($dates as &$date) {
            if ($date === null)
                continue;

            $date = Carbon::parse($date);

            if ($this->options[static::OPTION_TIMEZONE] !== 'UTC')
                $date->setTimezone($this->options[static::OPTION_TIMEZONE]);

            $date = $date->toDateTimeString();
        }

        return empty($dates['end']) ? $dates['start'] : $dates;
    }


}