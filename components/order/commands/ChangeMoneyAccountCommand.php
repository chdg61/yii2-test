<?php
namespace app\components\order\commands;


use app\models\Account;

class ChangeMoneyAccountCommand implements InterfaceCommand
{
    /** @var float  */
    private $money;
    /** @var \app\models\Account */
    private $account;
    /** @var string  */
    private $strategy;

    const PLUS_MONEY_STRATEGY = '+';
    const MINUS_MONEY_STRATEGY = '-';

    public function __construct(Account $account, $money, $strategy = self::PLUS_MONEY_STRATEGY)
    {
        $this->account = $account;
        $this->money = (float) $money;

        if($this->hasStrategy($strategy)){
            $this->strategy = $strategy;
        }else{
            $this->strategy = self::PLUS_MONEY_STRATEGY;
        }
    }

    public function execute()
    {
        if($this->strategy === self::MINUS_MONEY_STRATEGY){
            $this->minusMoney();
        }else{
            $this->plusMoney();
        }
    }

    protected function plusMoney()
    {
        $this->account->balance = $this->account->balance + $this->money;

        if(!$this->account->save()){
            throw new \Exception('Not save account');
        }
    }

    protected function minusMoney()
    {
        $this->account->balance = $this->account->balance - $this->money;

        if(!$this->account->save()){
            throw new \Exception('Not save account');
        }
    }

    private function hasStrategy($strategy)
    {
        return in_array($strategy,[
            self::PLUS_MONEY_STRATEGY,
            self::MINUS_MONEY_STRATEGY
        ], true);
    }
}
