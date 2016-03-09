<?php

namespace app\controllers;

use app\models\EmailConfirm;
use app\models\LoginForm;
use app\models\RegisterForm;
use Yii;

class AuthController extends \yii\web\Controller
{

    public function actionIndex()
    {
        return $this->redirect('/auth/login');
    }

    public function actionRegister()
    {
        $model = new RegisterForm();


        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            if ($model->confirmedUser()) {
                Yii::$app->session->setFlash('confirmedEmail');
                $model = new RegisterForm();
            }
        }

        return $this->render('register', ['model' => $model]);
    }

    public function actionConfirm($code)
    {
        try{
            /** @var EmailConfirm $confirm */
            $confirm = EmailConfirm::find()->where(['code' => $code])->one();

            if (!$confirm) {
                throw new \Exception('Not found confirm entity');
            }

            $user = $confirm->getUser();
            if (!$user) {
                throw new \Exception('Not found user');
            }
            $user->confirmed = 1;
            if (!$user->save()) {
                throw new \Exception('Not saved user');
            }
        }catch(\Exception $e){
            return $this->render('confirm/fail');
        }

        return $this->render('confirm/succes');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
