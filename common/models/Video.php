<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "videos".
 *
 * @property string $link
 * @property string $name
 * @property string $views
 * @property string $likes
 * @property string $dislikes
 * @property string $uploaded
 * @property string $checked
 * @property string $link_hash
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'videos';
    }

    public static function findByLinkHash($link_hash, $result = true){
        $return = self::find()->where(['link_hash' => $link_hash]);

        if($result){
            return $return->one();
        }

        return $return;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['views', 'likes', 'dislikes'], 'integer'],
            [['checked'], 'safe'],
            [['link', 'name', 'uploaded', 'link_hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'link' => 'Link',
            'name' => 'Name',
            'views' => 'Views',
            'likes' => 'Likes',
            'dislikes' => 'Dislikes',
            'uploaded' => 'Uploaded',
            'checked' => 'Checked',
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord || empty($this->link_hash)){
            $this->link_hash = md5($this->link);
        }

        if(empty($this->views)){
            $this->views = 0;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
