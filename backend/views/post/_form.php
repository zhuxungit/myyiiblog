<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Poststatus;
use yii\helpers\ArrayHelper;
use common\models\Adminuser;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 6]) ?>

<?php 
//方法一
// $psObjs = Poststatus::find()->all();
// $allStatus = ArrayHelper::map($psObjs, 'id', 'name')
//方法二
// $psArray = Yii::$app->db->createCommand("select id,name from poststatus")->queryAll();
// $allstatus = ArrayHelper::map($psArray, 'id', 'name');
//方法三
// $allStatus = (new \yii\db\Query())
// ->select(['name','id'])
// ->from('poststatus')
// ->indexBy('id')
// ->column();
//方法四
// $psObjs = Poststatus::find()
// ->select(['name','id'])
// ->indexBy('id')
// ->column();

// echo '<pre>';
// print_r($psObjs);
// exit();
?>

    <?= $form->field($model, 'status')->dropDownList(Poststatus::find()->select(['name','id'])->indexBy('id')->column(),['prompt'=>'请选择状态']) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'author_id')->dropDownList(Adminuser::find()->select(['nickname','id'])->indexBy('id')->column(),['prompt'=>'请选择作者']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
