<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			['attribute'=>'id',
					'contentOptions'=>['width'=>'30px'],
			],
			['attribute'=>'content',
					'value'=>'beginning',
// 					'value'=>function ($model){
//     	$tmpStr = strip_tags($model->content);
//     	$tmpLen = mb_strlen($tmpStr);
//     	return mb_substr($tmpStr, 0, 20, 'utf-8').(($tmpLen>20)?'...':'');
//     }
    ],
        		['attribute'=>'user.username',
        				'label'=>'作者',
        				'value'=>'user.username'
        		],
            'status',
// 			['attribute'=>'status',
// 					'value'=>'status0.name',
// 					'filter'=>Commentstatus::find()
// 								->select(['name','id'])
// 								->orderBy('position')
// 								->indexBy('id')
// 								->column(),
// 			],
        	
//             'create_time:datetime',
			['attribute'=>'create_time',
					'format'=>['date','php:m-d H:i']
    ],
//             'userid',
            // 'email:email',
            // 'url:url',
            // 'post_id',
            'post.title',
             

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
