<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form about `common\models\Post`.
 */
class PostSearch extends Post
{
	/**
	 * 添加authorName属性
	 * {@inheritDoc}
	 * @see \yii\db\ActiveRecord::attributes()
	 */
	public function attributes()
	{
		return array_merge(parent::attributes(),['authorName']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title', 'content', 'tags', 'authorName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios(); 
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        // add conditions that should always apply here

        //数据提供者，设置分页排序等
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        		'pagination'=>['pageSize'=>10],
        		'sort'=>['defaultOrder'=>[
        				'id'=>SORT_DESC	
        		],
        				'attributes'=>['id','title']
        		],
        ]);
 
        //快赋值，把表单填写的数据 赋值给当前对象的属性
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions 构造查询条件
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);

            //对输入的作者模糊查询
            $query->join('inner join','Adminuser','post.author_id=Adminuser.id');
            $query->andFilterWhere(['like','Adminuser.nickname',$this->authorName]);
            
            //对作者排序
            $dataProvider->sort->attributes['authorName']=[
            		'asc'=>['Adminuser.nickname'=>SORT_ASC],
            		'desc'=>['Adminuser.nickname'=>SORT_DESC]
            ];
            
            
        return $dataProvider;
    }
}
