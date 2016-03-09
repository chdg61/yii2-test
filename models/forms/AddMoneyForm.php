<?php

namespace app\models\forms;

use app\components\order\commands\AddOrderCommand;
use app\components\order\commands\ChangeMoneyAccountCommand;
use app\components\order\OrderMoneyCommander;
use app\models\Account;
use app\models\User;
use Yii;
use yii\base\Model;

class AddMoneyForm extends Model
{
    public $money;
    public $user_sender_id;
    public $user_recipient_id;
    /** @var User */
    private $recipient;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['money'], 'required'],
        ];
    }

    public function attachRecipientUser(User $recipient)
    {
        $this->recipient = $recipient;
    }

    public function handle()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $accountRecipient = $this->recipient->getAccount();
        
        try{
            $orderCommander = new OrderMoneyCommander();
            $orderCommander->addCommand(new ChangeMoneyAccountCommand($accountRecipient, $this->money, ChangeMoneyAccountCommand::PLUS_MONEY_STRATEGY))
                           ->addCommand(new AddOrderCommand($this->money, null, $accountRecipient))
                           ->execute();
            
            return true;
        }catch(\Exception $e){
            Yii::error($e->getMessage());
            
            return false;
        }
    }
}
