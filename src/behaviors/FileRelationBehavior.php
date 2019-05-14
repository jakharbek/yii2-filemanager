<?php

namespace jakharbek\filemanager\behaviors;

use jakharbek\filemanager\models\Files;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class FileBehavior
 * @package jakharbek\filemanager\behaviors
 * File relation method
 */
class FileRelationBehavior extends AttributeBehavior
{
    /**
     * @var string
     * Delimtr
     */
    public $delimtr = ",";
    /**
     * @var string
     * Relation name
     */
    public $relation = "files";
    /**
     * @var string
     * Model attribute for io data by id
     */
    public $attribute = "files_data";

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateRelationData',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'updateRelationData'
        ];
    }

    /**
     * @return bool
     */
    public function updateRelationData()
    {
        $files_ids = [];
        $files = $this->owner->{$this->relation};

        if (count($files) !== 0) {
            $files_ids = ArrayHelper::map($files, "id", "id");
        }

        $files_attr_ids = $this->owner->{$this->attribute};
        $diff_remove = array_diff($files_ids, $files_attr_ids);
        $diff_add = array_diff($files_attr_ids, $files_ids);

        if (count($diff_remove) > 0) {
            foreach ($diff_remove as $item) {
                $model = Files::findOne($item);
                $this->owner->unlink($this->relation, $model, true);
            }
        }
        if (count($diff_add) > 0) {
            foreach ($diff_add as $item) {
                $model = Files::findOne($item);
                $this->owner->link($this->relation, $model);
            }
        }

        return [
            'add' => $diff_add,
            'remove' => $diff_remove
        ];
    }
}