<?php
namespace jakharbek\filemanager\widgets;

use jakharbek\filemanager\assets\FilemanagerAsset;
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
 *  if editor
 *    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
'options' => ['rows' => 6],
'clientOptions'=>[
'extraPlugins' => 'filemanager-jakhar',
'justifyClasses'=>[ 'AlignLeft', 'AlignCenter', 'AlignRight', 'AlignJustify' ],
'height'=>200,
'toolbarGroups' => [
['name' => 'filemanager-jakhar']
],
],
'preset' => 'full'
]) ?>
 */
class ModalWidget extends Widget{
    /**
     * @var string произволная строка для идентификации
     */
    public $id = "editor";
    /**
     * @var ActiveForm ваша форма
     */
    public $form;

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

    private $file_id_css = "file-";

    public function init(){
        FilemanagerAsset::register(Yii::$app->view);
        $this->file_id_css = $this->file_id_css.$this->id;
        $listurl = Url::to($this->list);
        $deleteurl = Url::to($this->deleteurl);
        $script =  <<<JS
            var filemanager = function(editor){      
                    var self = this;
                    this.editor = editor;
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
                                      data: "q=" + q + "&page=" + self.currentPage,
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
                                              data: "q=" + q + "&page=" + self.currentPage,
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
                              var src_url = $(this).attr('data-file-url');
                              var file_type = $(this).attr('data-file-type');
                              if(file_type == "jpg" || file_type == "jpeg" || file_type == "svg" || file_type == "bmp" || file_type == "png" || file_type == "gif")
                              {
                                self.editor.insertHtml( '<img src="'+src_url+'" />' );
                              }
                              if(file_type == "mp4" || file_type == "flv"){
                                  self.editor.insertHtml( '<video width="320" height="240" controls src="'+src_url+'"></video>');
                              }
                               if(file_type == "mp3"){
                                  self.editor.insertHtml( '<img src="'+src_url+'" />' );
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
                    }
                    this.init = function(){ 
                        $(self.loadMoreBtn).on('click',function(){
                            self.loadMore();
                        });
                        $(self.searchButton).click(function(){
                            self.q = $(self.searchInput).val();
                           
                            self.query();
                        }); 
                    }
                     
                    this.init();
                    this.query();
                    this.run = function(editor){
                        self.editor = editor;
                        this.render();
                        $('#filemanagermodel{$this->id}').modal('show');
                    }
            }
            
                document.filemanager{$this->id} = new filemanager(); 
           
        
JS;
        Yii::$app->view->registerJS($script);


    }
    public function run(){


        $form = $this->form;
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

        echo <<<HTML
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