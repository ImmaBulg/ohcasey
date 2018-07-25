<?php

namespace backend\controllers;

use Yii;
use backend\models\Banner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Banner::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banner model.
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
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();
        $model->scenario = Banner::SCENARIO_CREATE;

        if(Yii::$app->request->isPost){
            $fileMain = UploadedFile::getInstanceByName('Banner[path]');
            $fileMainName = "/files/banner_" . uniqid() . "." . $fileMain->extension;
            $fileMain->saveAs(Yii::getAlias("@frontend") . "/web$fileMainName" );

            $fileMobile = UploadedFile::getInstanceByName('Banner[mobile_path]');
            $fileMobileName = "/files/m_banner_" . uniqid() . "." .$fileMobile->extension;
            $fileMobile->saveAs(Yii::getAlias("@frontend") . "/web$fileMobileName" );

            $bannerPost = Yii::$app->request->post("Banner");

            $model->path = $fileMainName;
            $model->mobile_path = $fileMobileName;
            $model->link = $bannerPost['link'];
            $model->sort = $bannerPost['sort'];
            $model->active = $bannerPost['active'];
            $model->is_repeat = $bannerPost['is_repeat'];

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            $fileMain = UploadedFile::getInstanceByName('Banner[path]');
            if($fileMain){
                $fileMainName = "/files/banner_" . uniqid() . "." . $fileMain->extension;
                $fileMain->saveAs(Yii::getAlias("@frontend") . "/web$fileMainName" );
                $model->path = $fileMainName;
            }

            $fileMobile = UploadedFile::getInstanceByName('Banner[mobile_path]');
            if($fileMobile){
                $fileMobileName = "/files/m_banner_" . uniqid() . "." .$fileMobile->extension;
                $fileMobile->saveAs(Yii::getAlias("@frontend") . "/web$fileMobileName");
                $model->mobile_path = $fileMobileName;
            }

            $bannerPost = Yii::$app->request->post("Banner");

            $model->link = $bannerPost['link'];
            $model->sort = $bannerPost['sort'];
            $model->active = $bannerPost['active'];
            $model->is_repeat = $bannerPost['is_repeat'];

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banner model.
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
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
