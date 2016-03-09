<?php

namespace app\models\forms;

use app\components\order\commands\AddOrderCommand;
use app\components\order\commands\ChangeMoneyAccountCommand;
use app\components\order\OrderMoneyCommander;
use app\models\Account;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\base\Model;

class TransferMoneyForm extends Model
{
    public $sum;
    public $email;
    
    /** @var User */
    public $userRecipient;
    
    /** @var User */
    public $userSender;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sum','email'], 'required'],
            ['email', 'email'],
            ['email', 'validateEmailUser'],
            ['sum', 'validateAllowChangeSum']
        ];
    }

    public function attributeLabels()
    {
        return [
            'sum'   => 'Сумма перевода',
            'email' => 'Email адрес получателя'
        ];
    }


    public function validateEmailUser($attribute, $params)
    {
        $user = UserSearch::findUserByEmail($this->email);

        if(!$user){
            $this->addError($attribute, 'Пользователя с email: '.$this->email.' не существует.');
            return false;
        }

        if((int)$user->getId() === (int)$this->userSender->getId()){
            $this->addError($attribute, 'Нельзя перекинуть деньги самому себе.');
            return false;
        }

        $this->userRecipient = $user;
        return true;
    }

    public function validateAllowChangeSum($attribute, $params)
    {
        $sum = (float) $this->sum;

        $balance = (float) $this->userSender->getAccount()->balance;

        if($balance <= 0){
            $this->addError($attribute, 'На вашем счету нет денег');

            return false;
        }

        if($balance <= $sum){
            $this->addError($attribute, 'Запрошена слишком большая сумма, на вашем счету всего: '.$balance.' денежных едениц');

            return false;
        }

        return true;
    }

    public function handle()
    {
        if (!$this->validate()) {
            return false;
        }

        $accountSender = $this->userSender->getAccount();
        $accountRecipient = $this->userRecipient->getAccount();
        
        try{
            $orderCommander = new OrderMoneyCommander();
            $orderCommander->addCommand(new ChangeMoneyAccountCommand($accountSender, $this->sum, ChangeMoneyAccountCommand::MINUS_MONEY_STRATEGY))
                           ->addCommand(new ChangeMoneyAccountCommand($accountRecipient, $this->sum, ChangeMoneyAccountCommand::PLUS_MONEY_STRATEGY))
                           ->addCommand(new AddOrderCommand($this->sum, $accountSender, $accountRecipient))
                           ->execute();

            return true;
        }catch(\Exception $e){
            Yii:error($e->getMessage());
            
            return false;
        }
    }
}
