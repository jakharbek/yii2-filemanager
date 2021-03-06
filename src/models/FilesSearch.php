<?php

namespace jakharbek\filemanager\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * FilesSearch represents the model behind the search form of `jakharbek\filemanager\models\Files`.
 */
class FilesSearch extends Files
{
    public $s;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'user_id', 'status', 'size'], 'integer'],
            [['title', 'description', 'slug', 'name', 'ext', 'file', 'folder', 'domain', 'upload_data', 'params', 'path', 's'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Files::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'size' => $this->size,
        ]);

        $query->andFilterWhere(['or', ['like', 'title', $this->s], ['like', 'description', $this->s]])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['ext' => $this->ext])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'folder', $this->folder])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'upload_data', $this->upload_data])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'path', $this->path]);

        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            's' => 'Search'
        ]); // TODO: Change the autogenerated stub
    }
}
