<?php
/**
 * @Final File
 */
use yii\grid\GridView;
use yii\widgets\Pjax;

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'       => $dataLayout,
    'filterModel'  => $model,
    'options'      => ['class' => 'grid-view panel-crud'],
    'pager'        => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'      => [
    	[
    		'headerOptions' => ['width' => '1%'],
    		'class' => 'yii\grid\SerialColumn',
    	],

        'name',
        'username',
        [
            'attribute' => 'position',
            'value' => function ($model) {
                $value = json_decode($model->position, true);
                return $value['position'];
            },
        ],
        [
            'attribute' => 'talent_type',
            'value' => function ($model) {
                $value = json_decode($model->position, true);
                return $value['talent_type'];
            },
        ],
        [
            'attribute' => 'talent_sub',
            'value' => function ($model) {
                $value = json_decode($model->position, true);
                return $value['talent_sub'];
            },
        ],
        'email:email',
        'created_at:datetime',
        
        [
        	'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
        ],
    ],
]);
Pjax::end();