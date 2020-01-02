<?php

namespace jakharbek\filemanager\jobs;

use jakharbek\filemanager\interfaces\fileRepositoryInterface;
use jakharbek\filemanager\interfaces\fileServiceInterface;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;

class createThumbnailsJob extends BaseObject implements \yii\queue\JobInterface
{
    public $file_id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        /**
         * @var $service fileServiceInterface
         */
        $service = Yii::$container->get(fileServiceInterface::class);

        /**
         * @var $repository fileRepositoryInterface
         */
        $repository = Yii::$container->get(fileRepositoryInterface::class);
        $file = $repository->getById($this->file_id);
        echo $this->file_id."\n";
        $service->createThumbsImageByFile($file);
    }
}