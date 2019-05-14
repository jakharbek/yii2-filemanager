<?php

namespace jakharbek\filemanager\services;

use jakharbek\filemanager\dto\FileCreateDTO;
use jakharbek\filemanager\dto\FileSaveDTO;
use jakharbek\filemanager\dto\FileUploadDTO;
use jakharbek\filemanager\dto\FileUploadedDTO;
use jakharbek\filemanager\dto\GeneratedPathFileDTO;
use jakharbek\filemanager\dto\GeneratePathFileDTO;
use jakharbek\filemanager\exceptions\FileManagerExceptions;
use jakharbek\filemanager\helpers\FileManagerHelper;
use jakharbek\filemanager\interfaces\iFileManagerFactory;
use jakharbek\filemanager\interfaces\iFileManagerServices;
use jakharbek\filemanager\jobs\createThumbsImageJob;
use jakharbek\filemanager\models\Files;
use Yii;
use yii\helpers\Inflector;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class FileManagerServices
 * @package jakharbek\filemanager\services
 */
class FileManagerServices implements iFileManagerServices
{
    /**
     * @param FileUploadDTO $fileUploadDTO
     * @return FileUploadedDTO|null
     */
    public function upload(FileUploadDTO $fileUploadDTO): ?FileUploadedDTO
    {
        /**
         * @var $files UploadedFile[]
         */
        $files = $fileUploadDTO->files;
        if (count($files) == 0) {
            throw new FileManagerExceptions("Files is empty for uploading");
        }
        $fileUploadedDTO = new FileUploadedDTO();

        foreach ($files as $file) {
            if (!($file instanceof UploadedFile)) {
                throw new FileManagerExceptions("File object is not instanceof UploadedFile class");
            }

            $generatePathDTO = new GeneratePathFileDTO();
            $generatePathDTO->file = $file;
            $generatePathDTO->useFileName = $fileUploadDTO->useFileName;
            $generatePath = $this->generatePath($generatePathDTO);
            $path = $generatePath->file_path;

            if (!$file->saveAs($path)) {
                $fileUploadedDTO->errorsFiles[] = [
                    'UploadedFile' => $file,
                    'GeneratedPathFileDTO' => $generatePath
                ];
            }
            $fileUploadedDTO->uploadedFiles[] = [
                'UploadedFile' => $file,
                'GeneratedPathFileDTO' => $generatePath
            ];
        }

        return $fileUploadedDTO;
    }

    /**
     * @param GeneratePathFileDTO $generatePathFileDTO
     * @return mixed
     */
    public function generatePath(GeneratePathFileDTO $generatePathFileDTO): GeneratedPathFileDTO
    {
        $generatedPathFileDTO = new GeneratedPathFileDTO();
        $created_at = time();

        $file = $generatePathFileDTO->file;
        $y = date("Y", $created_at);
        $m = date("m", $created_at);
        $d = date("d", $created_at);
        $h = date("H", $created_at);
        $i = date("i", $created_at);


        $folders = [
            $y,
            $m,
            $d,
            $h,
            $i
        ];

        $file_hash = Yii::$app->security->generateRandomString(64);
        $file_name = Inflector::transliterate($file->baseName)."_".Yii::$app->security->generateRandomString(10);
        $basePath = Yii::getAlias('@static/' . getenv("UPLOAD_DIR"));
        $folderPath = getenv("UPLOAD_DIR");
        foreach ($folders as $folder) {
            $basePath .= $folder . "/";
            $folderPath .= $folder . "/";
            if (!is_dir($basePath)) {
                mkdir($basePath);
            }
        }
        $generatedPathFileDTO->file_folder = $basePath;

        $path = $basePath . $file_hash . "." . $file->extension;
        $generatedPathFileDTO->file_name = $file_hash;

        if ($generatePathFileDTO->useFileName) {
            $generatedPathFileDTO->file_name = $file_name;
            $path = $basePath . $file_name . "." . $file->extension;
        }

        $generatedPathFileDTO->file_ext = $file->extension;
        $generatedPathFileDTO->file_path = $path;
        $generatedPathFileDTO->created_at = $created_at;
        $generatedPathFileDTO->folder_path = $folderPath;

        return $generatedPathFileDTO;
    }

    /**
     * @param FileUploadedDTO $fileUploadedDTO
     * @param FileSaveDTO $fileSaveDTO
     * @return bool
     *
     * jakharbek\filemanager\dto\FileUploadedDTO Object
     * (
     * [uploadedFiles] => Array
     * (
     * [0] => Array
     * (
     * [UploadedFile] => yii\web\UploadedFile Object
     * (
     * [name] => photo_2019-05-07_13-31-01_filter.jpg
     * [tempName] => /tmp/phpR7BhsZ
     * [type] => image/jpeg
     * [size] => 168861
     * [error] => 0
     * )
     *
     * [GeneratedPathFileDTO] => jakharbek\filemanager\dto\GeneratedPathFileDTO Object
     * (
     * [file_path] => /app/application/static/2019/05/10/08/53/photo_2019-05-07_13-31-01_filter.jpg
     * [file_name] => jLy3ur6AsvbLvXbSpf963zETdvn4REOOj4-8tocC_nIyrq9ATlArxJzQ6Yqis_2n
     * [file_folder] => /app/application/static/2019/05/10/08/53/
     * [file_ext] => jpg
     * [created_at] => 1557478394
     * )
     *
     * )
     *
     * [1] => Array
     * (
     */
    public function save(FileUploadedDTO $fileUploadedDTO, FileSaveDTO $fileSaveDTO): ?array
    {
        /**
         * @var $factory iFileManagerFactory
         */
        $factory = Yii::$container->get(iFileManagerFactory::class);

        $files = $fileUploadedDTO->uploadedFiles;

        $createdFiles = [];

        foreach ($files as $file) {

            /**
             * @var UploadedFile $UploadedFile
             */
            $UploadedFile = $file['UploadedFile'];

            /**
             * @var GeneratedPathFileDTO $GeneratedPathFileDTO
             */
            $GeneratedPathFileDTO = $file['GeneratedPathFileDTO'];

            $fileCreateDTO = new FileCreateDTO();

            $fileCreateDTO->title = $fileSaveDTO->title;
            $fileCreateDTO->description = $fileSaveDTO->description;
            $fileCreateDTO->slug = $fileSaveDTO->slug;
            $fileCreateDTO->domain = $fileSaveDTO->domain;
            $fileCreateDTO->name = $UploadedFile->name;
            $fileCreateDTO->folder = $GeneratedPathFileDTO->folder_path;
            $fileCreateDTO->path = $GeneratedPathFileDTO->file_folder;
            $fileCreateDTO->file = $GeneratedPathFileDTO->file_name;
            $fileCreateDTO->ext = $GeneratedPathFileDTO->file_ext;
            $fileCreateDTO->created_at = $GeneratedPathFileDTO->created_at;
            $fileCreateDTO->upload_data = $file;
            $fileCreateDTO->size = $UploadedFile->size;
            if (!(($fileModel = $factory::create($fileCreateDTO)) instanceof Files)) {
                throw new \DomainException("File is not saved");
            }

            $createdFiles[$fileModel->id] = $fileModel;

            if(FileManagerHelper::useQueue()){
                Yii::$app->queue->push(new createThumbsImageJob([
                    'file_id' => $fileModel->id
                ]));
            }else{
                $this->createThumbsImage($fileModel);
            }
        }

        return $createdFiles;
    }

    /**
     * @param Files $file
     * @return bool
     */
    public function createThumbsImage(Files $file): ?bool
    {
        if (!$file->getIsImage()) {
            return null;
        }

        $thumbsImages = FileManagerHelper::getThumbsImage();
        $origin = $file->getDist();
        foreach ($thumbsImages as $thumbsImage) {
            $width = $thumbsImage['w'];
            $height = $thumbsImage['h'];
            $qualty = $thumbsImage['q'];
            $slug = $thumbsImage['slug'];
            $newFileDist = $file->path . $file->file . "_".$slug . "." . $file->ext;
            $img = Image::getImagine()->open(Yii::getAlias($origin));
            $size = $img->getSize();
            $ratio = $size->getWidth() / $size->getHeight();
            $height = round($width / $ratio);
            Image::thumbnail($origin, $width, $height)->save(Yii::getAlias($newFileDist), ['quality' => $qualty]);
        }

        return true;
    }
}