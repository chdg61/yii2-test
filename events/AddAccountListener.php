<?php
namespace app\events;

use app\models\Account;
use app\models\event\AfterRegisterUser;

class AddAccountListener
{

    public static function onAfterRegisterUser(AfterRegisterUser $afterRegisterUser)
    {
        $account = new Account();
        $account->setAttribute('user_id', $afterRegisterUser->clientId);
        $account->setAttribute('balance', 0);
        
        $account->save();
    }
}
