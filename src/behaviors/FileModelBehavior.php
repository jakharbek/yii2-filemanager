<?php
namespace jakharbek\filemanager\behaviors;

/**
 *
 * @author Jakhar <javhar_work@mail.ru>
 *
 */

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use \jakharbek\filemanager\models\Files;


class FileModelBehavior extends AttributeBehavior
{
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

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT  => 'saveData',
            ActiveRecord::EVENT_BEFORE_UPDATE  => 'saveData'
        ];
    }

    public function saveData(){
        if(!$this->owner->isNewRecord):
            $this->unlinkData();
        endif;
        $this->linkData();
    }

    private function unlinkData(){
        $relation_data = $this->owner->{$this->relation_name};
        if(count($relation_data) == 0){return false;}
        foreach ($relation_data as $data):
            $this->owner->unlink($this->relation_name,$data,true);
        endforeach;
    }

    private function linkData(){
        $data = $this->owner->{$this->attribute};
        if($data{0} == ",")
        {
            $data = substr($data,1,strlen($data) + 10);
        }
        if(strlen($data) == 0){return false;}
        $data = explode($this->delimitr,$data);
        if(!is_array($data)){return false;}
        if(!count($data)){return false;}

        $elements = Files::find()->where(['in', Files::primaryKey()[0], $data])->indexBy('file_id')->all();
        $data_sort = [];

        if($data):
            $i = 0;
            foreach ($data as $d)
            {
                $element = $elements[$d];
                $i++;
                $data_sort[$element->file_id] = $i;
                $this->owner->link($this->relation_name,$element);

                $sql =  "".$this->one_table_column."=".$this->owner->{$this->owner->primaryKey()[0]}." AND ".$this->two_table_column."=".$element->file_id;
                Yii::$app->db->createCommand()->update($this->via_table_name,['sort' => $i],$sql)->execute();
            }
        endif;
    }

}