<?php

namespace jakharbek\filemanager\interfaces;

use jakharbek\filemanager\models\Files;

interface iFileManagerRepository
{
    /**
     * @param $id
     * @return Files|null
     */
    public function getById($id): ?Files;

    /**
     * @param $id
     * @return Files|null
     */
    public function delete($id): ?Files;
}