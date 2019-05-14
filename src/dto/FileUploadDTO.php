<?php

namespace jakharbek\filemanager\dto;

use yii\web\UploadedFile;

class FileUploadDTO
{
    /**
     * @var UploadedFile[]
     */
    public $files = [];
    public $useFileName = false;
}