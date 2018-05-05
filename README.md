File Manager
============
File Manager

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jakharbek/yii2-filemanager "*"
```

or add

```
"jakharbek/yii2-filemanager": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

Пример будет на примере постов (Posts)

ещё вам нужно выполнить миграцию

```
yii migrate --migrationPath=@vendor/jakharbek/yii2-filemanager/src/migrations
```

Параметры
----
Вам нужно создать два параметра в Yii приложение
Первый относительный путь к папке загрузки файлов
```
upload_dir_file
```
Пример:
```php
@frontend/web/uploads/
```
Второй обсолютный путь к папке загрузки файлов
Пример:
```
upload_dir_file_src
```

Пример:
```php
http://yoursite.domain/frontend/web/uploads/
```


Контроллер
----
Вам нужно подключить контроллер:
```php
'controllerMap' => [
        ...
        'files' => 'jakharbek\filemanager\controllers\FilesController'
        ...
    
    ],

```


Подключение
----

Через связаную таблицу
----

 
 Для начало вам нужно связуешая таблица для viaTable и настроить свзяь 
 допустим вам нужно подключить фотки постов и создаете таблицу
 ```php
 postsimagespasters
 ------------------
 post_id*
 file_id
 sort
 -----------------
 * это ваша таблца
 sort - для сортировка
 ```
 
 
Создаёте связь для этой таблицу у вас в главный моделе таблице (Posts)

```php
    public function getPostsimagesposters()
    {
        return $this->hasMany(Postsimagesposter::className(), ['post_id' => 'post_id']);
    }

    public function getimagesposters()
    {
        return $this->hasMany(Files::className(), ['file_id' => 'file_id'])->viaTable('postsimagesposter', ['post_id' => 'post_id']);
    }
```

Нужно создать свойство для инпута форму куда будут собираться данные из формы.

```php

class Posts{
    
    ...
    
    private $_postsimagespostersdata;
    
    ...
    
    
    
    public function getpostsimagespostersdata(){
        return $this->_postsimagespostersdata;
    }
    public function setpostsimagespostersdata($value){
        return $this->_postsimagespostersdata = $value;
    }

}
 

```


Поведение
----
```php
use jakharbek\filemanager\behaviors\FileModelBehavior;
```
```php
 'file_manager_model' => [
                'class' => FileModelBehavior::className(),
                'attribute' => 'postsimagespostersdata',
                'relation_name' => 'imagesposters',
                'delimitr' => ',',
                'via_table_name' => 'postsimagesposter',
                'via_table_relation' => 'postsimagesposters',
                'one_table_column' => 'post_id',
                'two_table_column' => 'file_id'
            ],
```

информация

```php
  /**
     * @var string имя атрибута откуда брать информацию из формы
     */
    public $attribute = "postsimagespostersdata";
    /**
     * @var string имя свзяи
     */
    public $relation_name = "imagesposters";
    /**
     * @var string разделителсь информация
     */
    public $delimitr = ",";
    /**
     * @var string имя связуюший таблице (как в базе данных)
     */
    public $via_table_name = "postsimagesposter";
    /**
     * @var string имя связи связуюший таблице
     */
    public $via_table_relation = "postsimagesposters";
    /**
     * @var string имя поля первечного ключа первый таблице
     */
    public $one_table_column = "post_id";
    /**
     * @var string имя поля первечного ключа первый таблице
     */
    public $two_table_column = "file_id";

```

Виджет
----
Теперь вы можете подключить виджет для выбера данных.

```php
echo jakharbek\filemanager\widgets\InputWidget::widget([
    'model_db' => $model,
    'form' => $form,
    'attribute' => 'Posts[postsimagespostersdata]',
    'id' => 'imagesposters',
    'relation_name' => 'imagesposters',
    'via_relation_name' => 'postsimagesposters',
    'delimitr' => ','
]);
```

инфорация

```php
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

```

Использованеи без связуюешей таблице
----
Через InputWidget

```php
\jakharbek\filemanager\widgets\InputModalWidget::widget(['form' => $form,
                'attribute' => 'files',
                'id' => 'files_id_asdsdasdsad',
                'values' => '',
                'value_encode' => true
            ]);
```


Использование в CKEditor
----

```php
     <?php  echo \jakharbek\filemanager\widgets\ModalWidget::widget(); ?>
            <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'clientOptions'=>[
                    'extraPlugins' => 'filemanager-jakhar',
                    'justifyClasses'=>[ 'AlignLeft', 'AlignCenter', 'AlignRight', 'AlignJustify' ],
                    'height'=>200,
                    'toolbarGroups' => [
                        ['name' => 'filemanager-jakhar']
                    ],
                ],
                'preset' => 'short'
            ]) ?>
            <?= $form->field($model, 'content')->widget(CKEditor::className(), [
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

```