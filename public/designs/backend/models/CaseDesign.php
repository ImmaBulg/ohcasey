<?php

namespace backend\models;

use common\models\Helper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "{{%case_design}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $likes_count
 * @property string $stickers
 * @property string $collection
 * @property string $description
 * @property string $h1
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CaseDesign2hashtag[] $caseDesign2hashtags
 * @property CaseDesignItem[] $caseDesignItems
 */
class CaseDesign extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%case_design}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['likes_count', 'created_at', 'updated_at'], 'integer'],
            [['stickers', 'collection', 'h1', 'meta_title', 'meta_keywords', 'meta_description'], 'filter', 'filter' => function($value){
                return HtmlPurifier::process($value);

            }],
            [['stickers', 'collection', 'h1', 'link', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 500],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название дизайна',
            'likes_count' => 'Количество лайков',
            'stickers' => 'Стикеры',
            'description' => 'Описание',
            'h1' => 'Заголовок h1',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'collection' => 'Коллекция дизайнов',
            'created_at' => 'Добавлен',
            'updated_at' => 'Изменён',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDesign2hashtags()
    {
        return $this->hasMany(CaseDesign2hashtag::className(), ['case_design_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDesignItems()
    {
        return $this->hasMany(CaseDesignItem::className(), ['case_design_id' => 'id']);
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
