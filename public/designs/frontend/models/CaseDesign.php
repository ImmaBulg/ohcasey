<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%case_design}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $likes_count
 * @property string $stickers
 * @property string $collection
 * @property string $description
 * @property string $link
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
class CaseDesign extends \yii\db\ActiveRecord
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
            [['name'], 'string', 'max' => 500],
            [['stickers', 'collection', 'link', 'h1', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'likes_count' => 'Likes Count',
            'stickers' => 'Stickers',
            'collection' => 'Collection',
            'description' => 'Description',
            'link' => 'Link',
            'h1' => 'Заголовок h1',
            'meta_title' => 'Meta title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
