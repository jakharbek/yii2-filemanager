<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel jakharbek\filemanager\models\FilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('files', 'Files');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="files-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('files', 'Create Files'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'title',
        'description:ntext',
        'slug',
        'name:ntext',
        //'ext',
        //'file:ntext',
        //'folder:ntext',
        //'domain:ntext',
        //'created_at',
        //'updated_at',
        //'user_id',
        //'status',
        //'upload_data:ntext',
        //'params:ntext',
        //'path:ntext',
        //'size',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<?php
//echo \jakharbek\filemanager\widgets\ModalWidget::widget(['modal_options' => ['id' => 'file-modal'],'search_params' => ['ext' => ['mp3','jpg']]]);

?>
<div class="col-md-6">
<?php
echo \jakharbek\filemanager\widgets\FileInput::widget(['id' => 'file_input']);
?>
</div>


        <div class="col-md-6">
            <?php
            echo \jakharbek\filemanager\widgets\FileInput::widget(['id' => 'file_input_2']);
            ?>
        </div>