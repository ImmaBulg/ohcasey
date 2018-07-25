<?php

namespace backend\models;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\WebM;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%case_design_item}}".
 *
 * @property integer $id
 * @property integer $case_design_id
 * @property string $type
 * @property string $path
 * @property string $link
 * @property string $source_path
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CaseDesign $caseDesign
 */
class CaseDesignItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%case_design_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_design_id', 'type', 'path', 'source_path'], 'required'],
            [['case_design_id', 'created_at', 'updated_at'], 'integer'],
            [['type', 'path', 'source_path'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 500],
            [['case_design_id'], 'exist', 'skipOnError' => true, 'targetClass' => CaseDesign::className(), 'targetAttribute' => ['case_design_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_design_id' => 'Case Design ID',
            'type' => 'Type',
            'path' => 'Path',
            'link' => 'Link',
            'source_path' => 'Source Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDesign()
    {
        return $this->hasOne(CaseDesign::className(), ['id' => 'case_design_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public static function getFileType($fileName){
        $pieces = explode('.', $fileName);
        $ext = strtolower(end($pieces));
        if($ext == 'mp4'){
            return 'video';
        }elseif($ext == 'gif'){
            return 'gif';
        }

        return 'image';
    }

    public static function saveFrameFromVideo($videoFile){
        $videoFileName = explode('/', $videoFile);
        $videoFileName = end($videoFileName);
        $videoFileName = explode('.', $videoFileName);
        $videoFileName = array_slice($videoFileName , 0, -1);
        $videoFileName = implode('.', $videoFileName );
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open(Yii::getAlias('@frontend') . '/web' . $videoFile);
        $video->frame(TimeCode::fromSeconds(1))->save(Yii::getAlias('@frontend') . '/web/files/thumbnails/' . $videoFileName.  '.jpg');
//        $video->save(new WebM(), Yii::getAlias('@frontend') . '/web/files/webm/'  . $videoFileName.  '.webm' );

        $im = new \Imagick();
        $im->readImage(Yii::getAlias('@frontend') . '/web/files/thumbnails/' . $videoFileName.  '.jpg');
        $im->setImageAlphaChannel(11);
        $im->setImageBackgroundColor('white');
        $im->setImageFormat('png');
        $im->stripImage();
        $im->writeImage(Yii::getAlias('@frontend') . '/web/files/thumbnails/' . $videoFileName.  '.png');
        $im->clear();
        $im->destroy();

        unlink(Yii::getAlias('@frontend') . '/web/files/thumbnails/' . $videoFileName.  '.jpg');
    }

}
