<?php
namespace jakharbek\filemanager\controllers;

use jakharbek\filemanager\models\Files;
use Yii;
use yii\console\Controller;

/**
 * Class ConverterController
 * @package jakharbek\filemanager\controllers
 *
 * console
 * controller map
 * jakharbek\filemanager\controllers
 */
class ConverterController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = "file";

    /**
     * @return int
     */
    public function actionFile(){
        Files::cron();
        return 0;
    }
}