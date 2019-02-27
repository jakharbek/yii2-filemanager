<?php

namespace jakharbek\filemanager\controllers;

use Yii;
use jakharbek\filemanager\models\Files;
use jakharbek\filemanager\models\FilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ListView;

/**
 * FilesController implements the CRUD actions for Files model.
 * 'controllerMap' => [
 * ...
 * 'files' => 'jakharbek\filemanager\controllers\FilesController'
 * ...
 * ],
 * Вам нужно подключить это к вашей карте контроллеров приложение
 */
class FilesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Files models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Files model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Files model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Files();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->file_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Files model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->file_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Files model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Files model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Files the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Files::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function getViewPath()
    {
        return Yii::getAlias('@vendor/jakharbek/yii2-filemanager/src/views/files');
    }

    public function actionUploads()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $keys = array_keys($_FILES);
        if (!count($keys)) {
            return false;
        }
        $response = [];
        foreach ($keys as $key):
            $files = UploadedFile::getInstancesByName($key);
            if (count($files)) {
                foreach ($files as $file):
                    $model = new Files();
                    $model->file_data = $file;
                    $model->save();
                    if ($model->hasErrors()):
                        $response[] = $model->getErrors();
                    endif;
                endforeach;
            }
        endforeach;
        return $response;
    }

    public function actionList($page = 1, $q = null, $id_model = null, $class_model = "", $relation_name = "")
    {

        $searchModel = new FilesSearch();
        if (Yii::$app->request->isPost) {
            $q = Yii::$app->request->post('q');
        }
        $searchModel->title = $q;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 9;
        $dataProvider->pagination->page = $page - 1;
        $dataProvider->sort = [
            'defaultOrder' => [
                'date_create' => SORT_DESC,
            ]
        ];
        $dataProvider->refresh();

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['pageTotal' => ceil($dataProvider->getTotalCount() / $dataProvider->pagination->pageSize)];
        }
        if (strlen($class_model) > 0):
            $class_model = base64_decode($class_model);
        endif;
        if (!$id_model) {
            $data = [];
        } else {
            $data = $class_model::findOne($id_model);
        }
        $selected = [];
        if (is_array($data->{$relation_name})):
            if (count($data->{$relation_name})) {
                foreach ($data->{$relation_name} as $itemdata) {
                    $selected[] = $itemdata->file_id;
                }
            }
        endif;
        if (Yii::$app->request->isAjax):
            return $this->renderAjax('_list', ['dataProvider' => $dataProvider, 'data' => $data, 'selected' => $selected, 'relation_name' => $relation_name]);
        else:
            return $this->render('_list', ['dataProvider' => $dataProvider, 'data' => $data, 'selected' => $selected, 'relation_name' => $relation_name]);
        endif;
    }

    public function actionRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $data = $this->findModel($id)->delete();
        return $data;
    }

}

