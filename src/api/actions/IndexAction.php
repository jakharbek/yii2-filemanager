<?php
/**
 * Created by PhpStorm.
 * User: jakhar
 * Date: 5/10/19
 * Time: 5:29 PM
 */

namespace jakharbek\filemanager\api\actions;

use jakharbek\filemanager\models\Files;
use jakharbek\filemanager\models\FilesSearch;

class IndexAction extends \yii\rest\IndexAction
{
    public $modelClass = Files::class;
    public $dataFilter = [
        'class' => 'yii\data\ActiveDataFilter',
        'searchModel' => FilesSearch::class,
    ];
}