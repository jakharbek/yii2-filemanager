<?php

namespace jakharbek\filemanager\models;

/**
 * This is the ActiveQuery class for [[Files]].
 *
 * @see Files
 */
class FilesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Files[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Files|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
