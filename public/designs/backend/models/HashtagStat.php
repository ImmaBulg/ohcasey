<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%hashtag_stat}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $click_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class HashtagStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hashtag_stat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['click_count', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Хэштег',
            'click_count' => 'Количество кликов',
            'created_at' => 'Первый клик',
            'updated_at' => 'Последний клик',
        ];
    }
}
