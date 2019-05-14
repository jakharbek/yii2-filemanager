<?php

namespace jakharbek\filemanager\jobs;

use jakharbek\filemanager\interfaces\iFileManagerRepository;
use jakharbek\filemanager\interfaces\iFileManagerServices;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;

class createThumbsImageJob extends BaseObject implements \yii\queue\JobInterface
{
    public $file_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        /**
         * @var $service iFileManagerServices
         */
        $service = Yii::$container->get(iFileManagerServices::class);

        /**
         * @var $repository iFileManagerRepository
         */
        $repository = Yii::$container->get(iFileManagerRepository::class);
        $file = $repository->getById($this->file_id);
        echo $this->file_id."\n";
        $service->createThumbsImage($file);
    }
}