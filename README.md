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


Migrations
-----
You need to do the migration

```
yii migrate --migrationPath=@vendor/jakharbek/yii2-filemanager/src/migrations
```

Module
-----
You need to connect the module of the backend part app.`\jakharbek\filemanager\backend\Module`


```php
'modules' => [
    'files' => \jakharbek\filemanager\backend\Module::class
],
```
Params
-----

 You need to add parameters to the application in the file.
 example
 ```php
 [
     'thumbs' => [
         'icon' => [
             'w' => 50,
             'h' => 50,
             'q' => 50,
             'slug' => 'icon'
         ],
         'small' => [
             'w' => 320,
             'h' => 320,
             'q' => 50,
             'slug' => 'small'
         ],
         'low' => [
             'w' => 640,
             'h' => 640,
             'q' => 50,
             'slug' => 'low'
         ],
         'normal' => [
             'w' => 1024,
             'h' => 1024,
             'q' => 50,
             'slug' => 'normal'
         ]
     ],
     'images_ext' => [
         'jpg',
         'png',
         'bmp',
         'gif'
     ],
     'use_file_name' => true,
     'use_queue' => false,
     'file_not_founded' => '14',
     //'file_not_founded' => 'http://img.domain.loc/files/1.jpg'
 
 ```
 `thumbs` - thumbnails images
 <br />
 `images_ext`  - images ext
 <br />
 `use_file_name` - When uploading a file, whether to use the file name in the file download or create a hash
 <br />
 `use_queue` - When uploading a file, is it necessary to use a queue to load some photos in the background mode?
 
 Apply DI (dependency injection)
 -----
There is a class `\jakharbek\filemanager\bootstrap\setUp` you need to apply it to the initial download of the application as an example.
```php
'bootstrap' => [
    \jakharbek\filemanager\bootstrap\setUp::class
],
```
 
 Ways to use
 -----
 There are two ways to use you can use using a relation in a database or a column in a table:
 
  
 Method use via the relation
 -----------
 Suppose you have a junction table for example
 ```
 post_image
 ------------------
 post_id
 file_id
 sort
 -----------------
 ```
 
 And let's say you have the appropriate relational methods in Active Record
 
 ```php
public function getPostImages()
{
    return $this->hasMany(PostImage::className(), ['post_id' => 'post_id']);
}
 
public function getImages()
{
    return $this->hasMany(Files::className(), ['file_id' => 'file_id'])->viaTable('postImages', ['post_id' => 'post_id']);
}
 ```
 And now you need to apply special behavior `jakharbek\filemanager\behaviors\FileRelationBehavior` for such cases.
 
 For example:
 
 ```php
 'file_relation_image' => [
  'class' => FileRelationBehavior::class,
  'delimtr' => ',',
  'attribute' => 'file_image'
 ],
 ```
 
 You will need to create a property for exchanging data between the form and the model in case it found `file_image`
 
 ```php
 public $file_image
 ```
 
 or
 
 ```php
 private $_file_image;
  
 public function getFileImage(){
    return $this->$_file_image;
 }
 
 public function setFileImage($value){
    return $this->$_file_image = $value;
 }
 ```
 Next, you need to add this property `file_image` to the rules of the model as `safe`.
 For example:
 ```php
 public function rules()
 {
    return [ 
            [['file_image'], 'safe']],
     ];
 }
 ```
 Now you can apply a file load/upload widget `jakharbek\filemanager\widgets\FileInput` for this field.
 
 For example
 
 ```php
<?php use \jakharbek\filemanager\widgets\FileInput;?>
<?= $form->field($model, 'file_image')->widget(FileInput::class,[
            'id' => 'file_image_id'
    ]) ?>
 ```
 
 Use without a junction table
-----------
I assume that you already have fields in the table for storing file identifiers there. The type of this column should be a string, since file identifiers will be stored there through a separator for example we have images column for posts table

In this case, you can immediately use the widget without any model settings.

For example:
```php
use \jakharbek\filemanager\widgets\FileInput;

echo FileInput::widget(['id' => 'post_images_id']);
```


Use in CKEditor
-----------
To use the plug-in file manager in the editor, you need to specify this plug-in in the settings as shown in the example.
For example:
```php
<?php
    echo $form->field($model, 'description')->widget(CKEditor::className(), [
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
    ]);
?>
```

After you need to run once this code to launch a modal window when you click a button in the editor

```php
<?=\jakharbek\filemanager\widgets\FileInput::widget(['id' => 'fileManagerEditor','editor' => true]);?>
```

Helpers
-----------
You have to help the class jakharbek\filemanager\helpers\FileManagerHelper
There is one method that you will often use in my opinion:`FileManagerHelper::getFilesById`


API
-----------
The extension has an API, you can connect it and get access to the file manager via the API 
`jakharbek\filemanager\api\FilesController` <br />
If you need an action of this controller, you can use them by looking at the action method of this class.