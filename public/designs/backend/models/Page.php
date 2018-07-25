<?php

namespace backend\models;

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
            [['title'], 'required'],
            [['keywords'], 'required'],
            [['description'], 'required'],
            [['h1'], 'required'],
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
            'slug' => 'Slug',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'h1' => 'H1'
        ];
    }
}