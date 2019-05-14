<?php

namespace jakharbek\filemanager\widgets;

use jakharbek\filemanager\assets\FileManagerAsset;
use jakharbek\filemanager\models\Files;
use kartik\sortable\Sortable;
use kartik\sortinput\SortableInput;
use Yii;
use yii\base\Widget;

class FileInput extends Widget
{
    public $id = "file_manager_file_input";
    public $attribute = null;
    public $model = null;
    public $name = "file_input";
    public $delimtr = ",";
    public $value = null;
    private $items = [];
    public $hideInput = true;
    public $editor = false;


    /**
     * @return array
     */
    public function getItemsIdArray()
    {
        if ((strlen($this->value) == 0) && ($this->model == null)) {
            return [];
        }
        if ($this->model == null) {
            return explode($this->delimtr, $this->value);
        }

        if ((strlen(($this->model)->{$this->attribute}) == 0) && ($this->model !== null)) {
            return [];
        }

        return explode($this->delimtr, $this->model->{$this->attribute});
    }

    /**
     * @param bool $isArray
     * @return array|Files[]
     */
    public function getItemsModels($isArray = false)
    {
        if ($isArray == true) {
            return Files::find()->andWhere(['id' => $this->getItemsIdArray()])->indexBy('id')->asArray()->all();
        }
        return Files::find()->andWhere(['id' => $this->getItemsIdArray()])->indexBy('id')->all();
    }

    public function init()
    {
        FileManagerAsset::register(Yii::$app->view);
        $this->initItems();
        parent::init();
    }

    public function initItems()
    {
        $itemsArray = $this->getItemsIdArray();
        if (count($itemsArray) == 0) {
            return;
        }
        /**
         * @var $models Files[]
         */
        $models = $this->getItemsModels();
        $items = [];

        foreach ($models as $model) {
            $items[$model->id] = $this->initItem($model);
        }

        $this->items = $items;
    }

    public function initItem($model)
    {
        return ['content' => $this->itemContent($model)];
    }

    public function getFileItemCloseBtn()
    {
        return "file-input-item-close-" . $this->id;
    }

    public function itemContent($model)
    {
        /**
         * @var $model Files
         */
        $content = <<<HTML
<div class="file-input-item" style="background-image: url({$model->getLink()})">
<div class="{$this->getFileItemCloseBtn()} btn btn-danger" data-file-id="{$model->id}"><i class="fa fa-minus"></i></div>
</div>
HTML;

        $js = <<<JS
        $('.{$this->getFileItemCloseBtn()}').click(function(){
            var file_id = $(this).data("file-id");
            document.{$this->getFileManagerModalId()}.removeItem(file_id,this);
        });
JS;

        Yii::$app->view->registerJs($js);

        return $content;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $data = "<div class='file-input-block'>";
        $data .= $this->fileManagerModal();
        if (!$this->editor) {
            $data .= $this->activeSortableInput();
            $data .= $this->fileManagerModalBtn();
        }
        $data .= "</div>";
        return $data;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function activeSortableInput()
    {
        return SortableInput::widget([
            'id' => $this->id,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'name' => $this->name,
            'value' => $this->value,
            'items' => $this->items,
            'hideInput' => $this->hideInput,
            'sortableOptions' => ['type' => Sortable::TYPE_GRID, 'id' => $this->id],
            'options' => ['class' => 'form-control', 'id' => $this->id]
        ]);
    }

    public function fileManagerModalBtn()
    {
        return "
<div class='btn btn-default file-input-btn-modal-top' id='" . $this->getAddBtnId() . "'><i class='fa fa-plus'></i></div>
";
    }

    public function getModalId()
    {
        return $this->id . "_modal";
    }

    public function getFileManagerModalId()
    {
        if($this->editor){
            return "fileManagerEditor";
        }

        return "fileManager_" . $this->getModalId();
    }

    public function getCheckBtn()
    {
        return $this->id . "_check_btn";
    }

    public function getAddBtnId()
    {
        return $this->id . "_add_btn";
    }

    public function fileManagerModal()
    {

        $js = <<<JS
        document.{$this->getFileManagerModalId()} = new FileManager("#{$this->id}-sortable","#{$this->id}","{$this->delimtr}","{$this->getFileItemCloseBtn()}"); 
        var func = function () {
        $('#{$this->getModalId()}') . modal('show');
    };
        $('#{$this->getAddBtnId()}').click(func);
        
        
JS;
        if ($this->editor) {
            $js = <<<JS
        document.fileManagerEditor = new FileManager(null,null,null,null,true,"#{$this->getModalId()}");
JS;
        }

        Yii::$app->view->registerJs($js);
        return ModalWidget::widget([
            'modal_options' => [
                'id' => $this->id . "_modal",
            ],
            'btn_check' => $this->getCheckBtn(),
            'btn_check_js_func' => $this->getCheckJsFunc()
        ]);
    }

    public function getCheckJsFunc()
    {

        $js = <<<JS
            function(e){
                var file_id = $(this).data("file-id");
                var file_link = $(this).data("file-link");
                var file_ext = $(this).data("file-ext");
                document.{$this->getFileManagerModalId()}.addItem(file_id,file_link,file_ext);

            }
JS;

        return $js;
    }
}