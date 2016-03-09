<?php

namespace app\controllers;

use app\models\forms\TransferMoneyForm;
use app\models\OrderSearch;
use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $orderDataProvider = OrderSearch::findAllForUser($user);


        return $this->render('index',[
            'account'           => $user->getAccount(),
            'user'              => $user,
            'orderDataProvider' => $orderDataProvider
        ]);
    }

    public function actionMoney()
    {
        $formModel = new TransferMoneyForm([
            'userSender' => Yii::$app->user->identity
        ]);

        if($formModel->load(Yii::$app->request->post())){
            if($formModel->handle()){
                Yii::$app->session->setFlash('money.success', 'Деньги успешно переведены');
                return $this->refresh();
            }else{
                Yii::$app->session->setFlash('money.error', 'Ошибка при переводе денег');
                Yii::error('Ошибка при добавлении денег', 'application.add_money.error');
            }
        }


        return $this->render('money',[
            'model' => $formModel
        ]);
    }
}
