<?php

namespace jakharbek\filemanager\factories;

use jakharbek\filemanager\dto\FileCreateDTO;
use jakharbek\filemanager\exceptions\FileManagerExceptions;
use jakharbek\filemanager\interfaces\iFileManagerFactory;
use jakharbek\filemanager\interfaces\iFileManagerServices;
use jakharbek\filemanager\models\Files;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

/**
 * Class FileManagerFactory
 * @package jakharbek\filemanager\factories
 */
class FileManagerFactory extends Component implements iFileManagerFactory
{
    /**
     * @param FileCreateDTO $fileCreateDTO
     * @return Files|null
     */
    public static function create(FileCreateDTO $fileCreateDTO): ?Files
    {
        /**
         * @var $service iFileManagerServices
         */
        $service = Yii::$container->get(iFileManagerServices::class);

        if(mb_strlen($fileCreateDTO->title) == 0){
            $fileCreateDTO->title = $fileCreateDTO->name;
        }
        if(mb_strlen($fileCreateDTO->description) == 0){
            $fileCreateDTO->description = $fileCreateDTO->name;
        }

        /**
         * @var $file Files
         */
        $file = Yii::createObject([
            'class' => Files::class,
            'title' => $fileCreateDTO->title,
            'description' => $fileCreateDTO->description,
            'slug' => $fileCreateDTO->slug,
            'name' => $fileCreateDTO->name,
            'ext' => $fileCreateDTO->ext,
            'file' => $fileCreateDTO->file,
            'folder' => $fileCreateDTO->folder,
            'domain' => $fileCreateDTO->domain,
            'created_at' => $fileCreateDTO->created_at,
            'upload_data' => Json::encode($fileCreateDTO->upload_data),
            'params' => Json::encode($fileCreateDTO->params),
            'path' => $fileCreateDTO->path,
            'size' => $fileCreateDTO->size
        ]);

        if(!$file->save()){
            throw new FileManagerExceptions(current($file->getErrors())[0]);
        }

        return $file;
    }
}