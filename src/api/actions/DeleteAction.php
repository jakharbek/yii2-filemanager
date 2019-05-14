<?php

namespace jakharbek\filemanager\api\actions;

use jakharbek\filemanager\interfaces\iFileManagerRepository;
use Yii;
use yii\base\Action;

/**
 * Class UploadAction
 * @package jakharbek\filemanager\api\actions
 */
class DeleteAction extends Action
{
    /**
     * @param $id
     * @return \jakharbek\filemanager\models\Files|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function run($id)
    {
        /**
         * @var $repository iFileManagerRepository
         */
        $repository = Yii::$container->get(iFileManagerRepository::class);

        return $repository->delete($id);
    }
}