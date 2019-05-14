<?php
namespace jakharbek\filemanager\interfaces;

use jakharbek\filemanager\dto\FileCreateDTO;
use jakharbek\filemanager\models\Files;

/**
 * Interface iFileManagerFactory
 * @package jakharbek\filemanager\interfaces
 */
interface iFileManagerFactory
{
    /**
     * @param FileCreateDTO $fileCreateDTO
     * @return Files|null
     */
    public static function create(FileCreateDTO $fileCreateDTO): ?Files;
}