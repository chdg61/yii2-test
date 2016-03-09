<?php
namespace app\components\order;


use app\components\order\commands\InterfaceCommand;
use yii\base\Component;


class OrderMoneyCommander extends Component
{
    /** @var InterfaceCommand[] */
    private $commands = [];

    public function addCommand(InterfaceCommand $command)
    {
        $this->commands[] = $command;
        return $this;
    }

    public function execute()
    {
        foreach($this->commands as $command){
            $command->execute();
        }
    }
}
