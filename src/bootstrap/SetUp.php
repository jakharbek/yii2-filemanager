<?php

namespace jakharbek\filemanager\bootstrap;

use jakharbek\filemanager\factories\FileFactory;
use jakharbek\filemanager\interfaces\FileFactoryInterface;
use jakharbek\filemanager\interfaces\FileRepositoryInterface;
use jakharbek\filemanager\interfaces\FileServiceInterface;
use jakharbek\filemanager\repositories\FileRepository;
use jakharbek\filemanager\services\FileService;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $container->set(FileServiceInterface::class, FileService::class);
        $container->set(FileRepositoryInterface::class, FileRepository::class);
        $container->set(FileFactoryInterface::class, FileFactory::class);
    }

}