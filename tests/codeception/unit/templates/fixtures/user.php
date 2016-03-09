<?php

return [
    'email'     => $faker->email,
    'password'  => Yii::$app->getSecurity()->generatePasswordHash('password_'.$index),
    'group'     => 2,
    'create'    => (new \DateTime())->format('d-m-Y H:i:s'),
    'confirmed' => true,
];