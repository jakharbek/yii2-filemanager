<?php

namespace jakharbek\filemanager\api\actions;

use jakharbek\filemanager\interfaces\fileRepositoryInterface;
use Yii;
use yii\base\Action;
use yii\base\Controller;

/**
 * Class UploadAction
 * @package jakharbek\filemanager\api\actions
 */
class DeleteAction extends Action
{
    /**
     * @var fileRepositoryInterface
     */
    private $fileRepository;

    public function __construct(string $id, Controller $controller, FileRepositoryInterface $fileRepository, array $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param $id
     * @return \jakharbek\filemanager\models\Files|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function run($id)
    {
        return $this->fileRepository->delete($id);
    }
}