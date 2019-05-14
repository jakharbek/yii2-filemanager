<?php

namespace jakharbek\filemanager\api\actions;

use jakharbek\filemanager\exceptions\FileException;
use jakharbek\filemanager\forms\UploadFilesForm;
use jakharbek\filemanager\models\Files;
use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class UploadAction
 * @package jakharbek\filemanager\api\actions
 */
class UploadAction extends Action
{
    public $attribute = "files";
    public $extensions = [];
    public $maxFiles = 100;
    public $isBack = false;

    /**
     * @return bool|UploadFilesForm
     * @throws FileException
     */
    public function run()
    {
        $model = new UploadFilesForm(['extensions' => $this->extensions, 'maxFiles' => $this->maxFiles]);

        if (!Yii::$app->request->isPost) {
            throw new FileException("Method is not POST");
        }


        $model->files = UploadedFile::getInstancesByName($this->attribute);


        if($this->isBack){
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        /**
         * @var $files Files[]
         */
        if (!$files = $model->upload()) {
            Yii::$app->response->statusCode = 400;
            return $model;
        }


        return $files;
    }
}