<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Page extends ActiveRecord {
    public static function tableName()
    {
        return '{{%page}}';
    }

    public function rules()
    {
        return [
            [['text'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст',
            'slug' => 'Slug'
        ];
    }
}