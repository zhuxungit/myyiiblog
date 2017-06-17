<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 */
class Tag extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 128],
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
            'frequency' => 'Frequency',
        ];
    }
    
    /**
     * 通过正则表达式切割字符串
     * @return array 返回数组
     * */
    public static function string2array($tags)
    {
    	return preg_split('/\s*,\s*/', trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }
    
    /**
     *数组切分成字符串
     * @param unknown $tags
     */
    public static function array2string($tags)
    {
    	return implode(',', $tags);
    }
    
    /**
     * 如果标签不存在则新增，如果标签存在则加1
     * @param unknown $tags
     */
    public static function addTags($tags)
    {
    	if (empty($tags)) return ;
    	foreach ($tags as $name){
    		$aTag = Tag::find()->where(['name'=>$name])->one();
    		$aTagCount = Tag::find()->where(['name'=>$name])->count();
    
    		if (!$aTagCount) {
    			$tag = new Tag;
    			$tag->name = $name;
    			$tag->frequency = 1;
    			$tag->save();
    		}else {
    			$aTag->frequency +=1;
    			$aTag->save();
    		}
    	}
    }
    
    /**
     * 删除标签
     * @param unknown $tags
     */
    public static function removeTags($tags)
    {
    	if (empty($tags)) return ;
    	foreach ($tags as $name){
    		$aTag = Tag::find()->where(['name'=>$name])->one();
    		$aTagCount = Tag::find()->where(['name'=>$name])->count();
    		if ($aTagCount) {
    			if ($aTagCount && $aTag->frequency<=1){
    				$aTag->delete();
    			}else {
    				$aTag->frequency -=1;
    				$aTag->save();
    			}
    		}
    	}
    }
    
    /**
     * 实现标签更新
     * @param unknown $oldTags
     * @param unknown $newTags
     */
    public static function updateFrequency($oldTags,$newTags)
    {
    	if (!empty($oldTags)&&!empty($newTags)) {
    		$oldTagsArray = self::string2array($oldTags);
    		$newTagsArray = self::string2array($newTags);
    
    		self::addTags(array_values(array_diff($newTagsArray, $oldTagsArray)));
    		self::removeTags(array_values(array_diff($oldTagsArray, $newTagsArray)));
    	}
    }
    

}
