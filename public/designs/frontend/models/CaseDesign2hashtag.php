<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%case_design2hashtag}}".
 *
 * @property integer $id
 * @property integer $case_design_id
 * @property integer $hashtag_id
 *
 * @property CaseDesign $caseDesign
 * @property Hashtag $hashtag
 */
class CaseDesign2hashtag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%case_design2hashtag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_design_id', 'hashtag_id'], 'required'],
            [['case_design_id', 'hashtag_id'], 'integer'],
            [['case_design_id'], 'exist', 'skipOnError' => true, 'targetClass' => CaseDesign::className(), 'targetAttribute' => ['case_design_id' => 'id']],
            [['hashtag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hashtag::className(), 'targetAttribute' => ['hashtag_id' => 'id']],
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
            'hashtag_id' => 'Hashtag ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseDesign()
    {
        return $this->hasOne(CaseDesign::className(), ['id' => 'case_design_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHashtag()
    {
        return $this->hasOne(Hashtag::className(), ['id' => 'hashtag_id']);
    }
}
