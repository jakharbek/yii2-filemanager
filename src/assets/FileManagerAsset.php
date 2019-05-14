<?php

namespace jakharbek\filemanager\assets;

use Yii;
use yii\web\AssetBundle;
/**
 * Class ChatAsset
 * @package jakharbek\chat\assets
 */
class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@jakharbek/filemanager/assets/web';

    public $js = [
        'js/FileManager.js',
        'js/CKEDITOR.plugin.js'
    ];

    public $css = [
        'https://use.fontawesome.com/releases/v5.8.2/css/all.css',
        'css/FileManager.css'
    ];

    public static function path($file = ""){
        return Yii::$app->assetManager->getBundle(self::className())->baseUrl."/".$file;
    }


    public $depends = [
        \yii\web\JqueryAsset::class
    ];
}