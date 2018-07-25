<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $path
 * @property string $mobile_path
 * @property string $link
 * @property integer $active
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'mobile_path'], 'required'],
            [['active', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['path', 'mobile_path', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'mobile_path' => 'Mobile Path',
            'link' => 'Link',
            'active' => 'Active',
            'sort' => 'Sort',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
