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
    protected $_next;
    protected $_previous;

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

    public function getNext(){
        if(empty($this->_next)){
            $this->_next = self::find()->where('views <= '.$this->views)->andWhere("`link_hash` != '{$this->link_hash}'")->orderBy('views DESC')->limit(1)->one();
        }

        return $this->_next;
    }

    public function getPrevious(){
        if(empty($this->_previous)){
            $this->_previous = self::find()->where('views >= '.$this->views)->andWhere("`link_hash` != '{$this->link_hash}'")->orderBy('views ASC')->limit(1)->one();
        }

        return $this->_previous;
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
            'link'          => 'Ссылка',
            'name'          => 'Название',
            'views'         => 'Просмотров',
            'likes'         => 'Лайков',
            'dislikes'      => 'Дизлайков',
            'uploaded'      => 'Загружено на youtube',
            'checked'       => 'Обновлено',
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
