<?php

use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $model,
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => Yii::t('app', 'No.'),
        ],
        [
        	'label' => Yii::t('app', 'Serial'),
        	'attribute' => 'serial',
        ],
        [
        	'label' => Yii::t('app', 'Create Time'),
        	'attribute' => 'create_time',
        	'format' => 'datetime',
        ],
        [
        	'label' => Yii::t('app', 'Expire Time'),
        	'attribute' => 'expire_time',
        	'format' => 'datetime',
        ],
    ],
]);