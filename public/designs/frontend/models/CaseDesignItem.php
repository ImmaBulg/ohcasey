<?php

namespace frontend\models;

use Yii;

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
class CaseDesignItem extends \yii\db\ActiveRecord
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
            [['case_design_id', 'type', 'path', 'source_path', 'created_at', 'updated_at'], 'required'],
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
            'link' => 'Link',
            'path' => 'Path',
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
}
