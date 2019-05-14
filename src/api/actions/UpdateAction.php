<?php

namespace jakharbek\filemanager\api\actions;

use jakharbek\filemanager\exceptions\FileException;
use jakharbek\filemanager\forms\UpdateFileForm;
use jakharbek\filemanager\forms\UploadFilesForm;
use Yii;
use yii\base\Action;

/**
 * Class UploadAction
 * @package jakharbek\filemanager\api\actions
 */
class UpdateAction extends Action
{
    /**
     * @param $id
     * @return UpdateFileForm|\jakharbek\filemanager\models\Files
     * @throws \yii\base\InvalidConfigException
     */
    public function run($id)
    {

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $model = new UpdateFileForm(['file_id' => $id]);
        $model->load($requestParams, '');
        if (!$model->update()) {
            return $model;
        }

        return $model->file;
    }
}