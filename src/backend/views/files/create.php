<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model jakharbek\filemanager\models\Files */

$this->title = Yii::t('files', 'Create Files');
$this->params['breadcrumbs'][] = ['label' => Yii::t('files', 'Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="files-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
