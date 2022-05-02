<?php
namespace Juanparati\Podium\Models;

use Juanparati\Podium\Models\Generics\IntGenericType;
use Juanparati\Podium\Podium;

/**
 * @see https://developers.podio.com/doc/reminders
 */
class ReminderModel extends ModelBase
{
    public function init() : void
    {
        $this->registerProp('reminder_id', IntGenericType::class, false, ['id' => true]);
        $this->registerProp('remind_delta', IntGenericType::class);
    }
}
