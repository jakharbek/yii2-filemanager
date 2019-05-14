<?php

namespace jakharbek\filemanager\bootstrap;

use jakharbek\filemanager\factories\FileManagerFactory;
use jakharbek\filemanager\interfaces\iFileManagerFactory;
use jakharbek\filemanager\interfaces\iFileManagerRepository;
use jakharbek\filemanager\interfaces\iFileManagerServices;
use jakharbek\filemanager\repositories\FileManagerRepository;
use jakharbek\filemanager\services\FileManagerServices;
use yii\base\BootstrapInterface;

class setUp implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {

        $container = \Yii::$container;
        $container->set(iFileManagerServices::class, FileManagerServices::class);
        $container->set(iFileManagerRepository::class, FileManagerRepository::class);
        $container->set(iFileManagerFactory::class, FileManagerFactory::class);
    }

}