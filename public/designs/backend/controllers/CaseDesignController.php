<?php

namespace backend\controllers;

use backend\models\CaseDesign2hashtag;
use backend\models\CaseDesignItem;
use backend\models\search\CaseDesignSearch;
use common\models\Helper;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Yii;
use backend\models\CaseDesign;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\HtmlPurifier;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CaseDesignController implements the CRUD actions for CaseDesign model.
 */
class CaseDesignController extends Controller
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
     * Lists all CaseDesign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CaseDesignSearch();
       /* $dataProvider = new ActiveDataProvider([
            'query' => CaseDesign::find()->with(['caseDesign2hashtags.hashtag', 'caseDesignItems']),
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);*/
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 0;
        $dataProvider->sort->defaultOrder = ['created_at'=>SORT_DESC];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CaseDesign model.
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
     * Creates a new CaseDesign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CaseDesign();
        $hashTags = Yii::$app->request->post('hashtag', []);
        $files = Yii::$app->request->post('selectedFiles', '[]');
        $files = json_decode($files, true);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($hashTags as $hashTagId){
                $case2hashtag = new CaseDesign2hashtag();
                $case2hashtag->case_design_id = $model->id;
                $case2hashtag->hashtag_id = $hashTagId;
                $case2hashtag->save();
            }
            foreach ($files as $key => $file){
                //Сохраняем файл с новым названием в папку на фронтенде /files/selected
                $fileName = $file["file"];
                $modelId = $model->id;
                $filePathInfo = pathinfo(Yii::getAlias("@frontend") . "/web/files/$fileName");
                $newFileName = Helper::translit($model->name, "-") . "-$key-$modelId" . "." . $filePathInfo['extension'];
                @copy(Yii::getAlias("@frontend") . "/web/files/$fileName", Yii::getAlias("@frontend") . "/web/files/selected/$newFileName");

                $caseItem = new CaseDesignItem();
                $caseItem->case_design_id = $model->id;
                $caseItem->path = "/files/selected/$newFileName";
                $caseItem->source_path = "/files/$fileName";
                $caseItem->link = !empty($file["link"]) ? $file["link"] : $files[0]["link"];
                $caseItem->type = CaseDesignItem::getFileType($fileName);
                $caseItem->save();

                //Сохранение превью для видоса
                if($caseItem->type == 'video'){
                    CaseDesignItem::saveFrameFromVideo($caseItem->path);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CaseDesign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $hashTags = Yii::$app->request->post('hashtag', []);
        $files = Yii::$app->request->post('selectedFiles', '[]');
        $files = json_decode($files, true);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            CaseDesign2hashtag::deleteAll('case_design_id=:case_design_id', [':case_design_id' => $model->id]);
            foreach ($hashTags as $hashTagId){
                $case2hashtag = new CaseDesign2hashtag();
                $case2hashtag->case_design_id = $model->id;
                $case2hashtag->hashtag_id = $hashTagId;
                $case2hashtag->save();
            }

            //удаление старых файлов
            $oldItems = CaseDesignItem::find()->where("case_design_id = $id")->asArray()->all();
            foreach ($oldItems as $oldItem){
                unlink(Yii::getAlias("@frontend") . "/web" . $oldItem['path']);
            }
            CaseDesignItem::deleteAll('case_design_id=:case_design_id', [':case_design_id' => $model->id]);

            foreach ($files as $key => $file){
                //Сохраняем файл с новым названием в папку на фронтенде /files/selected
                $fileName = $file["file"];
                $modelId = $model->id;
                $filePathInfo = pathinfo(Yii::getAlias("@frontend") . "/web/files/$fileName");
                $newFileName = Helper::translit($model->name, "-") . "-$key-$modelId" . "." . $filePathInfo['extension'];
                @copy(Yii::getAlias("@frontend") . "/web/files/$fileName", Yii::getAlias("@frontend") . "/web/files/selected/$newFileName");

                $caseItem = new CaseDesignItem();
                $caseItem->case_design_id = $model->id;
                $caseItem->path = "/files/selected/$newFileName";
                $caseItem->source_path = "/files/$fileName";
                $caseItem->link = !empty($file["link"]) ? $file["link"] : $files[0]["link"];
                $caseItem->type = CaseDesignItem::getFileType($fileName);
                $caseItem->save();

                //Сохранение превью для видоса
                if($caseItem->type == 'video'){
                    CaseDesignItem::saveFrameFromVideo($caseItem->path);
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CaseDesign model.
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
     * Быстрая загрузка картинки аяксом прямо из формы
     * @return string - путь сохранённой картинки
     * @throws \yii\base\Exception
     */
    public function actionUpload(){
        $file = UploadedFile::getInstanceByName('image');
        if(empty($file)){
            throw new InvalidParamException();
        }

        $fileName = uniqid() . "." . $file->getExtension();
        if($file->saveAs(Yii::getAlias('@frontend') . "/web/files/" . $fileName )){
            return json_encode([
                'fileName' => $fileName,
                'filePath' => "/files/$fileName",
                'fileType' => $file->type == 'video/mp4' ? 'video' : 'image',
            ]);
        }

        throw new \yii\base\Exception();
    }

    public function actionSortItems()
    {
        $params = Yii::$app->request->bodyParams;
        if(!empty($params['nextId'])){
            $nextItem = $this->findModel($params['nextId']);
            $newTime = $nextItem->created_at + 1;
        }else if(!empty($params['prevId'])){
            $prevItem = $this->findModel($params['prevId']);
            $newTime = $prevItem->created_at - 1;
        }else{
            throw new InvalidParamException('no ids');
        }
		Yii::$app->getDb()->createCommand("UPDATE ". CaseDesign::tableName() . " SET created_at=created_at+1 WHERE created_at >= $newTime")->execute();
		Yii::$app->getDb()->createCommand("UPDATE ". CaseDesign::tableName() . " SET created_at=created_at-1 WHERE created_at < $newTime")->execute();
        $model = $this->findModel($params['elementId']);
        $model->created_at = $newTime;
        if(!$model->save()){
            throw new RuntimeException('Saving error');
        }

        return json_encode([
            'success' => true
        ]);
    }

    /**
     * Finds the CaseDesign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CaseDesign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CaseDesign::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
