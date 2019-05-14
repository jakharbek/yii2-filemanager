<?php

namespace jakharbek\filemanager\helpers;

use jakharbek\filemanager\models\Files;
use Yii;

/**
 * Class FileManagerHelper
 * @package jakharbek\filemanager\helpers
 */
class FileManagerHelper
{
    /**
     * @param $domain
     * @param bool $isAbsolute
     * @return string
     */
    public static function getDomain($domain, $isAbsolute = false): string
    {
        return $domain;
    }

    /**
     * @return mixed
     */
    public static function getThumbsImage()
    {
        if(!array_key_exists('thumbs',Yii::$app->params)){
            throw new \DomainException("'thumbs' params is not founded");
        }
        return Yii::$app->params['thumbs'];
    }

    /**
     * @return array
     */
    public static function getImagesExt()
    {
        if(!array_key_exists('images_ext',Yii::$app->params)){
            throw new \DomainException("'images_ext' params is not founded");
        }
        return Yii::$app->params['images_ext'];
    }

    /**
     * @return bool
     */
    public static function useFileName()
    {
        if(!array_key_exists('use_file_name',Yii::$app->params)){
            throw new \DomainException("'use_file_name' params is not founded");
        }
        return Yii::$app->params['use_file_name'];
    }

    /**
     * @return bool
     */
    public static function useQueue(): bool
    {
        if(!array_key_exists('use_queue',Yii::$app->params)){
            throw new \DomainException("'use_queue' params is not founded");
        }
        return Yii::$app->params['use_queue'];
    }

    /**
     * @param $ids
     * @param string $delimtr
     * @param bool $isArray
     * @return array|Files[]
     */
    public static function getFilesById($ids, $delimtr = ",", $isArray = false)
    {
        if (!is_array($ids)) {
            if (strlen($ids) !== 0) {
                $ids = explode($delimtr, $ids);
            }
        }

        if (count($ids) == 0) {
            return [];
        }

        if ($isArray) {
            return Files::find()->andWhere(['id' => $ids])->asArray()->all();
        }

        return Files::find()->andWhere(['id' => $ids])->all();
    }
}