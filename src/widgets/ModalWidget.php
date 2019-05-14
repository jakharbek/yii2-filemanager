<?php

namespace jakharbek\filemanager\widgets;

use jakharbek\filemanager\assets\FileManagerAsset;
use jakharbek\filemanager\models\FilesSearch;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

class ModalWidget extends Widget
{
    public $modal_options = ['id' => 'file_modal'];
    public $modal_size = Modal::SIZE_LARGE;
    public $pageSize = 9;
    public $search_params = [];
    public $btn_check_js_func = null;
    public $btn_check = ".file-manager-btn-check";
    public $modal_client_options = ['show' => false];

    public function init()
    {
        FileManagerAsset::register(Yii::$app->view);
        parent::init();
    }

    /**
     * @return string|void
     */
    public function run()
    {

        Modal::begin([
            'header' => $this->render('modal-header'),
            'options' => $this->modal_options,
            'size' => $this->modal_size,
            'clientOptions' => $this->modal_client_options,
        ]);
        Pjax::begin(['id' => 'file-manager-' . $this->id]);

        $btn_check_js_func = $this->getCheckJsFunc();

        if ($this->btn_check_js_func !== null) {
            $btn_check_js_func = $this->btn_check_js_func;
        }

        $search = new FilesSearch();
        $params = Yii::$app->request->getQueryParams();
        $result = $search->search(ArrayHelper::merge($params, ['FilesSearch' => $this->search_params]));

        $result->pagination->pageSize = $this->pageSize;

        echo $this->render('modal-content', [
            'dataProvider' => $result,
            'modal_id' => $this->id,
            'search_model' => $search,
            'btn_check_js_func' => $btn_check_js_func,
            'btn_check' => $this->btn_check
        ]);

        Pjax::end();
        Modal::end();
    }

    public function getCheckJsFunc()
    {

        $js = <<<JS
            function(e){
                alert("asdasdsad");
            }
JS;

        return $js;
    }

}