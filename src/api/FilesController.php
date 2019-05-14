<?php

namespace jakharbek\filemanager\api;

use jakharbek\filemanager\api\actions\DeleteAction;
use jakharbek\filemanager\api\actions\IndexAction;
use jakharbek\filemanager\api\actions\UpdateAction;
use jakharbek\filemanager\api\actions\UploadAction;
use jakharbek\filemanager\api\actions\ViewAction;
use yii\rest\Controller;

/**
 * Class FileManagerController
 * @package jakharbek\filemanager\api
 */
class FilesController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'upload' => UploadAction::class,
            'update' => UpdateAction::class,
            'view' => ViewAction::class,
            'delete' => DeleteAction::class,
            'index' => IndexAction::class
        ];
    }
}