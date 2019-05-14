<?php

namespace jakharbek\filemanager\dto;

use yii\web\UploadedFile;

/**
 * Class GeneratePathFileDTO
 * @package jakharbek\filemanager\dto
 */
class GeneratePathFileDTO
{
    /**
     * @var $file UploadedFile
     */
    public $file;

    public $useFileName = false;
}