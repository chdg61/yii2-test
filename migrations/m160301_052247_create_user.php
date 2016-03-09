<?php

use yii\db\Migration;

class m160301_052247_create_user extends Migration
{

    public function up()
    {
        $this->createTable('user', [
            'id'        => $this->primaryKey(),
            'email'     => $this->string()->notNull(),
            'password'  => $this->string()->notNull(),
            'group'     => $this->integer(1)->defaultValue(2),
            'create'    => $this->dateTime(),
            'confirmed' => $this->boolean()->defaultValue(false)
        ]);
        $this->createTable('email_confirm', [
            'id'      => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'code'    => $this->string()->notNull()
        ]);
        $this->createTable('accounts', [
            'id'      => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'balance' => $this->money()->defaultValue(0)
        ]);
        $this->createTable('orders', [
            'id'                     => $this->primaryKey(),
            'sum'                    => $this->money()->defaultValue(0),
            'create'                 => $this->dateTime(),
            'user_sender_id'         => $this->integer()->notNull(),
            'user_recipient_id'      => $this->integer()->notNull(),
            'user_created'           => $this->integer()->notNull(),
            'cash_balance_sender'    => $this->money()->notNull(),
            'cash_balance_recipient' => $this->money()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
        $this->dropTable('email_confirm');
        $this->dropTable('accounts');
        $this->dropTable('orders');
    }
}
