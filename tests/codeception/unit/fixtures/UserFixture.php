<?php
namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';

    private $accountTable = 'accounts';

    public function afterLoad()
    {
        parent::afterLoad();
        $this->db->createCommand()->delete($this->accountTable)->execute();
        $this->db->createCommand()->resetSequence($this->accountTable,1)->execute();
        foreach($this->data as $key => $item){
            $this->loadAccount($item['id']);
        };
    }

    private function loadAccount($userId)
    {
        $this->db->schema->insert($this->accountTable,[
            'user_id' => $userId,
            'balance' => 0
        ]);
    }


}