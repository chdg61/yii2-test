<?php
namespace app\components\order\commands;


use app\models\Account;
use app\models\Order;

class AddOrderCommand implements InterfaceCommand
{


    /**
     * @var \app\models\Account
     */
    private $sender;
    /**
     * @var \app\models\Account
     */
    private $recipient;
    /**
     * @var
     */
    private $money;

    public function __construct($money, Account $sender = null, Account $recipient = null)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->money = (float) $money;
    }

    public function execute()
    {
        $order = new Order();
        
        $order->setAttributes([
            'sum'                    => $this->money,
            'user_sender_id'         => $this->sender?$this->sender->id:0,
            'user_recipient_id'      => $this->recipient?$this->recipient->id:0,
            'cash_balance_sender'    => $this->sender?$this->sender->balance:0,
            'cash_balance_recipient' => $this->recipient?$this->recipient->balance:0
        ]);

        if(!$order->save()){
            throw new \Exception('Not save order');
        }
    }
}
