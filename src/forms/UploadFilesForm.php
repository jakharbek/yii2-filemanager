<?php

namespace jakharbek\filemanager\forms;

use jakharbek\filemanager\dto\FileSaveDTO;
use jakharbek\filemanager\dto\FileUploadDTO;
use jakharbek\filemanager\helpers\FileManagerHelper;
use jakharbek\filemanager\services\FileService;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class UploadFilesForm
 * @package jakharbek\filemanager\forms
 */
class UploadFilesForm extends Model
{
    public $extensions = [];
    public $maxFiles = 100;
    public $files;
    public $title = "";
    public $description = "";

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['title', 'description'], 'string'],
            ['files', 'file', 'skipOnEmpty' => false, 'maxFiles' => $this->maxFiles, 'extensions' => $this->extensions],
        ]);
    }

    /**
     * @return bool
     * @throws \jakharbek\filemanager\exceptions\FileException
     */
    public function upload()
    {
        $service = new FileService();

        if (!$this->validate()) {
            return false;
        }

        $dto = new FileUploadDTO();
        $dto->files = $this->files;
        $dto->useFileName = FileManagerHelper::useFileName();
        $fileUploadedDTO = $service->upload($dto);
        $fileSaveDTO = new FileSaveDTO();
        $fileSaveDTO->title = $this->title;
        $fileSaveDTO->description = $this->description;
        $fileSaveDTO->domain = getenv('STATIC_URL');
        return $service->save($fileUploadedDTO, $fileSaveDTO);
    }
}