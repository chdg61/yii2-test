<?php
namespace app\behaviors;


use Yii;
use yii\behaviors\TimestampBehavior;


class TimestampFormatBehavior extends TimestampBehavior
{

    public function init()
    {
        if(!$this->value){
            $this->value = function(){
                return (new \DateTime())->format('d-m-Y H:i:s');
            };
        }
        parent::init();
    }
}
