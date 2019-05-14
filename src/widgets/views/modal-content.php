<?php
$files = \jakharbek\filemanager\models\Files::find()->limit(9)->all();

use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url; ?>

<?php $form = ActiveForm::begin(['id' => 'file-manager-search-form', 'options' => ['data-pjax' => 1], 'method' => 'get']); ?>
<?php
echo FileInput::widget([
    'name' => 'files[]',
    'options' => ['multiple' => true],
    'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/files/files/upload']),]
]);
?>
<?= $form->field($search_model, 's')->textInput(['autofocus' => true]) ?>

<?php ActiveForm::end(); ?>
<div class="row">
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'modal-view',
        'pager' => [
            'options' => [
                'tag' => 'div',
                'class' => 'pagination file-manager-pager-wrapper'
            ],
        ],
        'viewParams' => ['dataProvider' => $dataProvider, 'btn_check_js_func' => $btn_check_js_func, 'btn_check' => $btn_check],
    ]); ?>
</div>
