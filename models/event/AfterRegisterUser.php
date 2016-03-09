<?php
namespace app\models\event;

use yii\base\Event;

class AfterRegisterUser extends Event
{
    public $clientId = 0;
}
