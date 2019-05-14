<?php

namespace jakharbek\filemanager\forms;

use jakharbek\filemanager\interfaces\FileRepositoryInterface;
use jakharbek\filemanager\models\Files;
use Yii;
use yii\base\Model;

class UpdateFileForm extends Model
{
    public $file_id;
    public $title;
    public $description;
    public $slug;
    public $status;

    /**
     * @var Files
     */
    public $file;

    public function init()
    {
        /**
         * @var $repository FileRepositoryInterface
         */
        $repository = Yii::$container->get(FileRepositoryInterface::class);
        $this->file = $repository->getById($this->file_id);
        parent::init();
    }

    public function rules()
    {
        return [
            [
                ['title', 'description', 'slug', 'status'], 'safe'
            ],
        ];
    }

    /**
     * @return bool
     */
    public function update()
    {
        if(!$this->validate()){
            return false;
        }

        if (strlen($this->title) > 0) {
            $this->file->updateAttributes(['title' => $this->title]);
        }

        if (strlen($this->description) > 0) {
            $this->file->updateAttributes(['description' => $this->description]);
        }

        if (strlen($this->slug) > 0) {
            $this->file->updateAttributes(['slug' => $this->slug]);
        }

        if (strlen($this->status) > 0) {
            $this->file->updateAttributes(['status' => $this->status]);
        }

        return true;
    }
}