<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.03.2018
 * Time: 12:47
 * @var $selected;
 * @var $data;
 * @var $relation_name;
 */
use yii\widgets\ListView;

echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_listAjax',
    'layout' => "{items}",
    'viewParams' => ['data' => $data,'selected' => $selected,'relation_name' => $relation_name],
]);