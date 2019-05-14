<?php

namespace jakharbek\filemanager\repositories;

use jakharbek\filemanager\interfaces\FileRepositoryInterface;
use jakharbek\filemanager\models\Files;

/**
 * Class FileManagerRepository
 * @package jakharbek\filemanager\repositories
 */
class FileRepository implements FileRepositoryInterface
{

    /**
     * @param $id
     * @return Files|null
     */
    public function getById($id): ?Files
    {
        /**
         * @var $file Files
         */
        $file = Files::findOne($id);

        if (!($file instanceof Files)) {
            throw new \DomainException("Files is not founded");
        }

        return $file;
    }

    /**
     * @param $id
     * @return Files|null
     */
    public function delete($id): ?Files
    {
        /**
         * @var $file Files
         */
        $file = $this->getById($id);

        if ($file->getIsImage()) {
            $thumbs = $file->getImageThumbs();
            foreach ($thumbs as $thumb) {
                $path = $thumb['path'];
                if (!file_exists($path)) {
                    continue;
                }
                unlink($path);
            }
        }

        if (file_exists($file->getDist())) {
            unlink($file->getDist());
        }

        $file->delete();

        return $file;
    }
}