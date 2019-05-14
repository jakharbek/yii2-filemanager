<?php
/**
 * @var $model \jakharbek\filemanager\models\Files
 * @var $this \yii\web\View
 * @var $btn_check_js_func string
 */

use kartik\editable\Editable;

?>
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail file-manager-thumbnail">
            <?php if ($model->getIsImage()): ?>
                <div class="file-manager-thumbnail-data" style="background-image: url(<?= $model->getLink() ?>)"></div>
            <?php endif; ?>
            <div class="caption">
                <h4 class="file-manager-title">
                    <?php if ((Yii::$app->user->id == $model->user_id) && (!Yii::$app->user->isGuest)): ?>
                        <?= Editable::widget([
                            'model' => $model,
                            'formOptions' => ['action' => ['/files/files/update-editable', 'file_id' => $model->id]],
                            'attribute' => 'title',
                            'asPopover' => true,
                            'header' => 'Title',
                            'size' => 'md',
                            'options' => ['class' => 'form-control', 'placeholder' => Yii::t("files", "Enter title ...")]
                        ]); ?>
                    <?php else: ?>
                        <?= $model->title ?>
                    <?php endif; ?>
                </h4>
                <h5 class="file-manager-desc">
                    <?php if ((Yii::$app->user->id == $model->user_id) && (!Yii::$app->user->isGuest)): ?>
                        <?= Editable::widget([
                            'model' => $model,
                            'formOptions' => ['action' => ['/files/files/update-editable', 'file_id' => $model->id]],
                            'attribute' => 'description',
                            'asPopover' => true,
                            'header' => 'Description',
                            'size' => 'md',
                            'options' => ['class' => 'form-control', 'placeholder' => Yii::t("files", "Enter description ...")]
                        ]); ?>
                    <?php else: ?>
                        <?= $model->description ?>
                    <?php endif; ?>
                </h5>
            </div>
            <p class="file-manager-buttons">
                <a href="<?= $model->getLink() ?>" class="btn btn-warning file-manager-btn-download" role="button"><i
                            class="fa fa-download"></i></a>
                <a href="#" class="btn btn-success <?= $btn_check ?>" role="button"
                   data-file-id="<?= $model->id ?>"
                   data-file-title="<?= $model->title ?>"
                   data-file-description="<?= $model->description ?>"
                   data-file-link="<?= $model->getLink() ?>"
                   data-file-user-id="<?= $model->user_id ?>"
                   data-file-status="<?= $model->status ?>"
                   data-file-ext="<?= $model->ext ?>"
                >
                    <i class="fa fa-check"></i>
                </a>
            </p>
        </div>
    </div>

<?php
$js = <<<JS
    $('.{$btn_check}').click({$btn_check_js_func});
JS;
$this->registerJs($js);
