<?php

namespace jakharbek\filemanager\dto;

/**
 * Class FileCreateDTO
 * @package jakharbek\filemanager\dto
 */
class FileCreateDTO
{
    public $title;
    public $description;
    public $slug = null;
    public $name;
    public $ext;
    public $file;
    public $folder;
    public $domain = null;
    public $created_at = null;
    public $upload_data = [];
    public $params = [];
    public $path;
    public $size;
}