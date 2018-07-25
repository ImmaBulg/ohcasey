<?php

namespace backend\controllers;

use Yii;
use backend\models\Hashtag;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HashtagController implements the CRUD actions for Hashtag model.
 */
class HashtagController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Hashtag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Hashtag::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hashtag model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Hashtag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hashtag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionAjaxCreate(){
        if(!Yii::$app->request->isAjax){
            throw new ForbiddenHttpException();
        }
        $hashTag = Yii::$app->request->post('hashTag', '');
        $hashTag = str_replace(' ', '_', $hashTag);
        $hashTag = '#' . str_replace('#', '', $hashTag);
        if(empty($hashTag)){
            throw new InvalidParamException();
        }
        $hashTagModel = Hashtag::find()->where(['text' => $hashTag])->one();
        if($hashTagModel){
            return json_encode([
                'success' => false,
                'error' => 'Такой хэштег уже существует',
            ]);
        }

        $model = new Hashtag();
        $model->text = $hashTag;
        $model->description = Yii::$app->request->post('description', '');
        if($model->save()){
            return json_encode([
                'success' => true,
                'hashTag' => $hashTag,
                'id' => $model->id,
            ]);
        }

        throw new Exception('Ошибка при добавлении в базу данных');

    }

    /**
     * Updates an existing Hashtag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Hashtag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Hashtag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hashtag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hashtag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
