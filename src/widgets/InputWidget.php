<?php
namespace jakharbek\filemanager\widgets;

use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use \kartik\select2\Select2;
use yii\web\View;
use \kartik\sortinput\SortableInput;
use \kartik\file\FileInput;
use \jakharbek\posts\models\Posts;
use \jakharbek\filemanager\models\Files;
/**
 * Class InputWidget
 * @package jakharbek\filemanager\widgets
 */
class InputWidget extends Widget{
    /**
     * @var string произволная строка для идентификации
     */
    public $id = "filemanager1";
    /**
     * @var ActiveForm ваша форма
     */
    public $form;
    /**
     * @var ActiveRecord ваша модель базы данных.
     */
    public $model_db;

    /**
     * @var equal model_db
     */
    public $model;

    /**
     * @var string атрибут формы интупа который будет возврашать запрос по посту
     *  <input name="атрибут" />
     */
    public $attribute;
    /**
     * @var string имя действие контроллера если вы его изменили
     */
    public $controller = "/files/uploads/";
    /**
     * @var string имя действие контроллера если вы его изменили
     */
    public $list = "/files/list/";
    /**
     * @var string имя действие контроллера если вы его изменили
     */
    public $deleteurl = "/files/remove/";
    /**
     * @var string разделитель данных;
     */
    public $delimitr = ",";
    /**
     * @var string имя связи связуешей таблице
     */
    public $relation_name = "imagesposters";
    /**
     * @var stringи имя связи к таблице связи
     */
    public $via_relation_name = "postsimagesposters";
    /**
     * @var array имя расшерение для фоток
     */
    public $pictures_ext = ['png','jpg','jpeg','gif','bmp'];
    /**
     * @var array имя расшерение для аудио
     */
    public $music_ext = ['mp3'];
    /**
     * @var array имя расшерение для видео
     */
    public $video_ext = ['mp4','flv'];

    private $selected = [];
    private $file_id_css = "file-";
    private $model_id = "";
    private $model_class = "";
    private $selected_items_sortable = [];

    public function init(){
        if(is_object($this->model)){
            $this->model_db = $this->model;
        }
        if(is_object($this->model_db))
        {
            $model_temp = $this->model_db;
            $this->model_class = base64_encode(get_class($this->model_db));
            $this->model_id = $this->model_db->{$model_temp::primaryKey()[0]};
        }
        $this->file_id_css = $this->file_id_css.$this->id;
        $listurl = Url::to($this->list);
        $deleteurl = Url::to($this->deleteurl);
        $script =  <<<JS
            var filemanager = function(){      
                    var self = this;
                    this.currentPage = 1; 
                    this.resultPaste = ".fileManagerList{$this->id}";
                    this.searchButton = '.fileManagerSearchButton{$this->id}';
                    this.searchInput = '.fileManagerSearchInput{$this->id}';
                    this.loadMoreBtn = '.fileManagerLoadMore{$this->id}';
                    this.deleteBtn = ".filemanagerDeleteBtn";
                    this.selectBtn = ".filemanagerSelectBtn";
                    this.unselectBtn = ".filemanagerunSelectBtn";
                    this.filemanager_render_item_parent = ".filemanager-data";
                    this.params = [];
                    this.more = true;
                    this.q = "";
                    this.selected = [];
                    this.query = function(){
                                q = encodeURI(self.q);
                                self.currentPage = 1;
                                $.ajax({
                                      type: "POST",
                                      url: "{$listurl}", 
                                      data: "q=" + q,
                                      success: function(msg){
                                           self.params = msg;
                                           if(self.params.pageTotal > self.currentPage){ 
                                                self.more = true;
                                                $(self.loadMoreBtn).show();
                                           }else{
                                                $(self.loadMoreBtn).hide();
                                               self.more = false;
                                           }
                                      }
                                    });
                               $.ajax({
                                      type: "GET",
                                      url: "{$listurl}",
                                      data: "q=" + q + "&page=" + self.currentPage + "&id_model={$this->model_id}&class_model={$this->model_class}&relation_name={$this->relation_name}",
                                      success: function(msg){
                                          $(self.resultPaste).html(msg);
                                          self.render();
                                      }
                                    });
                    }
                    this.loadMore = function(){
                         q = encodeURI(self.q);
                         self.currentPage = self.currentPage + 1;
                                $.ajax({
                                      type: "POST",
                                      url: "{$listurl}", 
                                      data: "q=" + q,
                                      success: function(msg){
                                           self.params = msg;
                                           if(self.params.pageTotal > self.currentPage){ 
                                                $(self.loadMoreBtn).show();
                                                self.more = true;
                                           }else{
                                               self.more = false;
                                               $(self.loadMoreBtn).hide();
                                           }
                                      }
                                    });
                                if(self.more)
                                {
                                               
                                       $.ajax({
                                              type: "GET",
                                              url: "{$listurl}",
                                              data: "q=" + q + "&page=" + self.currentPage + "&id_model={$this->model_id}&class_model={$this->model_class}&relation_name={$this->relation_name}",
                                              success: function(msg){
                                                  $(self.resultPaste).append(msg);
                                                  self.render();
                                              }
                                            });
                                       
                                }else{
                                    $(self.loadMoreBtn).hide();
                                }
                                
                    }
                    this.deleteAction = function(){
                         $(self.deleteBtn).off('click');
                        $(self.deleteBtn).on('click',function(){
                            console.log(this);
                            var id = $(this).attr('data-file-id');
                            var this_data = this;
                            if(confirm('are you sure?')){
                                
                            $.ajax({
                                      type: "POST",
                                      url: "{$deleteurl}", 
                                      data: "id=" + id,
                                      success: function(msg){
                                          $(this_data).closest(self.filemanager_render_item_parent).hide(); 
                                      }
                                    });
                            
                            self.query();
                            }
                        });
                    }
                    this.selectAction = function(){
                        $(self.resultPaste).find(self.selectBtn).off('click');
                        $(self.resultPaste).find(self.selectBtn).on('click',function(){
                              var id = $(this).attr('data-file-id');
                              var title = $(this).attr('data-file-title');
                              var this_data = this; 
                              var selected = $('#{$this->file_id_css}').val();
                              self.selected = selected.split('{$this->delimitr}');
                              if(self.selected.indexOf(id) == -1){
                                  self.selected.push(id);
                                  $(this_data).hide();
                                  $(this_data).closest(self.filemanager_render_item_parent).find(self.unselectBtn).show();
                                  $('#{$this->file_id_css}-sortable').append('<li data-key="'+id+'" class="ui-sortable-handle"><i class="glyphicon glyphicon-trash delete_item" onclick="document.filemanager{$this->id}.deleteItem(this);"></i> '+title+'</li>');
                                  jQuery("#{$this->file_id_css}-sortable").sortable();
                                  $('#{$this->file_id_css}').val(self.selected);
                                  //
                                  console.log(self.selected);
                                  console.log('#{$this->file_id_css}-sortable');
                              }
                        });
                    }
                    this.unselectAction = function(){
                        $(self.resultPaste).find(self.unselectBtn).off('click');
                        $(self.resultPaste).find(self.unselectBtn).on('click',function(){
                              var id = $(this).attr('data-file-id');
                              var this_data = this; 
                              var selected = $('#{$this->file_id_css}').val();
                              self.selected = selected.split('{$this->delimitr}');
                              if(self.selected.indexOf(id) != -1){
                                  self.selected.splice(self.selected.indexOf(id),1);
                                  $(this_data).hide();
                                  $(this_data).closest(self.filemanager_render_item_parent).find(self.selectBtn).show();
                                  $('#{$this->file_id_css}-sortable li[data-key="'+id+'"]').remove();
                                  jQuery("#{$this->file_id_css}-sortable").sortable();
                                   $('#{$this->file_id_css}').val(self.selected);
                                  console.log(self.selected);
                              }
                        });
                    }
                    this.deleteItem = function(this_data){
                        if(!confirm("are you sure")){
                        return false;
                        }
                        var id = $(this_data).closest('li').attr('data-key');
                        console.log(this_data);
                        console.log("asdasd");
                              var selected = $('#{$this->file_id_css}').val();
                              self.selected = selected.split('{$this->delimitr}');
                              if(self.selected.indexOf(id) != -1){
                                  self.selected.splice(self.selected.indexOf(id),1);
                                   $(this_data).closest('li').remove();
                                  jQuery("#{$this->file_id_css}-sortable").sortable();
                                   $('#{$this->file_id_css}').val(self.selected); 
                              }
                    }
                    this.render = function(){
                       self.deleteAction();
                       self.selectAction();
                       self.unselectAction();
                    }
                    this.init = function(){ 
                        $(self.loadMoreBtn).on('click',function(){
                            self.loadMore();
                        });
                        $(self.searchButton).click(function(){
                            self.q = $(self.searchInput).val();
                           
                            self.query();
                        });
                        self.selected = $('#{$this->file_id_css}').val().split('{$this->delimitr}');
                    }
                     
                    this.init();
                    this.query();
            }
            
                document.filemanager{$this->id} = new filemanager(); 
           
        
JS;
        Yii::$app->view->registerJS($script);

        if(count($this->model_db->{$this->relation_name})){
            $via_table_elements = $this->model_db->{$this->via_relation_name};
            foreach ($via_table_elements as $via_element){
                $file = Files::findOne($via_element->file_id);
                $this->selected[] = $file->file_id;
                $this->selected_items_sortable[$via_element->file_id]['content'] = "";
                if(in_array($file->type,$this->pictures_ext))
                {
                    $this->selected_items_sortable[$via_element->file_id]['content'] .= "<img class='col-md-2' style='height:52px;' src='".$file->getImageSrc()."' />";
                }
                if(in_array($file->type,$this->video_ext))
                {
                    $this->selected_items_sortable[$via_element->file_id]['content'] .= "<video class='col-md-2' style='height:52px; display:inline;' src='".$file->getImageSrc()."'> </video>";
                }
                $this->selected_items_sortable[$via_element->file_id]['content'] .= "<h5 style=''><i class='glyphicon glyphicon-trash delete_item' onclick='document.filemanager{$this->id}.deleteItem(this);'></i> ".$file->title."</h5>";

            }
            $this->selected = implode($this->delimitr,$this->selected);
        }

    }
    public function run(){


        $form = $this->form;
        $model = $this->model_db;
        $list = Url::to([$this->list]);

        $upload_widget = FileInput::widget([
            'name' => 'filemanagerfile',
            'language' => 'ru',
            'options' => ['multiple' => true],
            'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to([$this->controller]),'filesorted' => true],
            'pluginEvents' => [
                'filebatchuploadcomplete' => new JsExpression('function(event, files, extra) {
    document.filemanager'.$this->id.'.query();
}'),
            ],
        ]);
        $sort_widget = SortableInput::widget([
            'name'=> $this->attribute,
            'value'=> $this->selected,
            'items' => $this->selected_items_sortable,
            'hideInput' => true,
            'options' => ['class'=>'form-control', 'readonly'=>true,'id' => $this->file_id_css]
        ]);

        echo <<<HTML
        <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#filemanagermodel{$this->id}" style="margin-bottom:5px; width:100%;">Выбрать</a>
        {$sort_widget}
<div class="modal fade" id="filemanagermodel{$this->id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel{$this->id}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel{$this->id}">Медиа</h4>
      </div>
      <div class="modal-body">
    <ul id="myTab" class="nav nav-tabs">
      <li class="active"><a href="#filemanager_files{$this->id}" data-toggle="tab">Файлы </a></li>
      <li><a href="#filemanager_upload_files{$this->id}" data-toggle="tab">Загрузка файлов</a></li>
    </ul>
    <div id="myTabContent{$this->id}" class="tab-content">
      <div class="tab-pane fade in active" id="filemanager_files{$this->id}">
         <div class="row col-md-12 col-xs-12 col-lg-12">
            <div class="input-group">
              <input type="text" class="form-control fileManagerSearchInput{$this->id}">
              <span class="input-group-btn">
                <button class="btn btn-default fileManagerSearchButton{$this->id}" type="button">Поиск</button>
              </span>
            </div><!-- /input-group -->
        </div>
        <div class="row col-md-12 col-xs-12 col-lg-12">
            <div class="fileManagerList{$this->id}">
                    Loading....
            </div>  
            <div class="row col-md-12 col-xs-12 col-lg-12 col-sm-12">
                <div class="fileManagerLoadMore{$this->id}">
                    <a href="#loadmoredata{$this->id}" class="btn btn-primary">Load More</a> 
                </div>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="filemanager_upload_files{$this->id}">{$upload_widget}
      </div>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button> 
      </div>
    </div>
  </div>
</div>
HTML;

    }

}