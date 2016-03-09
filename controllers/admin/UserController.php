<?php

namespace app\controllers\admin;

use app\models\forms\AddMoneyForm;
use app\models\admin\UserCreateForm;
use app\models\forms\TransferMoneyForm;
use app\models\OrderSearch;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user = $this->findUser($id);


        $addMoneyForm = new AddMoneyForm();
        $addMoneyForm->attachRecipientUser($user);
        if($addMoneyForm->load(Yii::$app->request->post())){
            if($addMoneyForm->handle()){
                Yii::$app->session->setFlash('money.success', 'Деньги успешно добавлены');
            }else{
                Yii::$app->session->setFlash('money.error', 'Ошибка при добавлении денег');
                Yii::error('Ошибка при добавлении денег', 'application.add_money.error');
            }
        }

        $transferMoneyForm = new TransferMoneyForm([
            'userSender' => $user
        ]);
        if($transferMoneyForm->load(Yii::$app->request->post())){
            if($transferMoneyForm->handle()){
                Yii::$app->session->setFlash('money.success', 'Деньги успешно переведены');
            }else{
                Yii::$app->session->setFlash('money.error', 'Ошибка при переводе денег');
                Yii::error('Ошибка при добавлении денег', 'application.add_money.error');
            }
        }


        $dataProvider = OrderSearch::findAllForUser($user);

        return $this->render('view', [
            'model'             => $user,
            'dataProvider'      => $dataProvider,
            'addMoneyForm'      => $addMoneyForm,
            'transferMoneyForm' => $transferMoneyForm
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserCreateForm();

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            return $this->redirect(['view', 'id' => $model->getUser()->getId()]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findUser($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->password = '';
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findUser($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Нет пользователя с id: '.$id);
        }
    }
}
