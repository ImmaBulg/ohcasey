<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%hashtag}}".
 *
 * @property integer $id
 * @property string $text
 * @property string $description
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $meta_title
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CaseDesign2hashtag[] $caseDesign2hashtags
 */
class Hashtag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hashtag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['h1'], 'required'],
            [['topText'], 'required'],
            [['bottomText'], 'required'],
            [['text'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            ['description', 'string'],
            [['text', 'meta_description', 'meta_keywords', 'meta_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'h1' => 'H1',
            'topText' => 'Текст вверху',
            'bottomText' => 'Текс внизу',
            'text' => 'Текст',
            'description' => 'Описание',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_title' => 'Meta Title',
            'created_at' => 'Добавлен',
            'updated_at' => 'Изменён',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDesign2hashtags()
    {
        return $this->hasMany(CaseDesign2hashtag::className(), ['hashtag_id' => 'id']);
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

}
