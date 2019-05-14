<?php

namespace jakharbek\filemanager\models;

/**
 * This is the ActiveQuery class for [[Files]].
 *
 * @see Files
 */
class FilesQuery extends \yii\db\ActiveQuery
{

    /**
     * @return mixed
     */
    public function active()
    {
        return $this->andWhere('[[status]]='.Files::STATUS_ACTIVE);
    }

    /**
     * @return mixed
     */
    public function inactive()
    {
        return $this->andWhere('[[status]]='.Files::STATUS_INACTIVE);
    }

    /**
     * @return mixed
     */
    public function deleted()
    {
        return $this->andWhere('[[status]]='.Files::STATUS_DELETED);
    }

    /**
     * {@inheritdoc}
     * @return Files[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Files|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
