<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $path
 * @property string $mobile_path
 * @property string $link
 * @property integer $sort
 * @property integer $is_repeat
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 */
class Banner extends ActiveRecord
{
    const SCENARIO_CREATE = 'SCENARIO_CREATE';
    const SCENARIO_UPDATE = 'SCENARIO_UPDATE';

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
            [['path', 'mobile_path'], 'required', 'on' => self::SCENARIO_CREATE],
            [['sort', 'created_at', 'updated_at', 'active', 'is_repeat'], 'integer'],
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
            'path' => 'Квадратный баннер',
            'mobile_path' => 'Горизонтальный баннер',
            'link' => 'Ссылка',
            'sort' => 'Сортировка',
            'active' => 'Активен',
            'is_repeat' => 'Повторяется',
            'created_at' => 'Добавлен',
            'updated_at' => 'Изменён',
        ];
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
