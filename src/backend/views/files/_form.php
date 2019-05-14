<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model jakharbek\filemanager\models\Files */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="files-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'clientOptions'=>[
            'extraPlugins' => 'filemanager-jakhar',
            'justifyClasses'=>[ 'AlignLeft', 'AlignCenter', 'AlignRight', 'AlignJustify' ],
            'height'=>200,
            'toolbarGroups' => [
                ['name' => 'filemanager-jakhar']
            ],
        ],
        'preset' => 'short'
    ]);
    echo \jakharbek\filemanager\widgets\FileInput::widget(['id' => 'fileManagerEditor','editor' => true]);
    ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ext')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'folder')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'domain')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'upload_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'params')->widget(\jakharbek\filemanager\widgets\FileInput::class,[
            'id' => 'file_id'
    ]) ?>

    <?= $form->field($model, 'path')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'size')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('files', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
