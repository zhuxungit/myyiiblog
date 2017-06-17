<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 *
 * @property Comment[] $comments
 * @property Adminuser $author
 * @property Poststatus $status0
 */
class Post extends \yii\db\ActiveRecord
{
	/**
	 * 声明一个私有变量用来保存更新前标签数据
	 * @var unknown
	 */
	private $_oldTags;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminuser::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Poststatus::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'tags' => '标签',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'author_id' => '作者',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Adminuser::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Poststatus::className(), ['id' => 'status']);
    }
    
    /**
     *新增触发，更新触发
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
    		if($insert)
    		{
    			$this->create_time = time();
    		    $this->update_time = time();
    		}else 
    		{
    			$this->update_time = time();
    		}
    		return true;
    		
    	}else 
    	{
    		return false;
    	}
    	
    }
    

    
    /**
     * 查看当前文章详情， 保存标签数据
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind()
    {
    	//重写方法时都要调用父类的方法
    	parent::afterFind();
    	$this->_oldTags = $this->tags;
    }
    
    /**
     * 更新文章数据后，更新标签数据
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterSave()
     */
    public function afterSave($insert, $changedAttributes)
    {
//     	print_r($this->_oldTags);
//     	print_r($this->tags);
    	 
//     	exit;
    	 
    	parent::afterSave($insert, $changedAttributes);
    	Tag::updateFrequency($this->_oldTags, $this->tags);
    }
    
    /**
     * 删除文章数据后，删除标签
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterDelete()
     */
    public function afterDelete()
    {
    	parent::afterDelete();
    	Tag::updateFrequency($this->_oldTags, '');
    }
    
    
}
