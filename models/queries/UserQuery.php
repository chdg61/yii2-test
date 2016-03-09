<?php

namespace app\models\queries;

use app\models\User;

class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @param string $email
     *
     * @return null|User
     */
    public function findByEmail($email)
    {
        return $this->andWhere(['email' => $email])->one();
    }
} 