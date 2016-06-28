<?php
/**
 * @Final File
 */
use yii\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'       => $dataLayout,
    'options'      => ['class' => 'grid-view panel-crud'],
    'pager'        => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'      => [
    	[
    		'headerOptions' => ['width' => '1%'],
    		'header' => "<input type=\"checkbox\" onclick=\"$('input[name*=\'selected\']').prop('checked', this.checked);\" />",
    		'value' => function ($model) {
    			return '<input type="checkbox" name="selected[]" value="' . $model->id . '" />';
    		},
    		'format' => 'raw',
    	],

        'name',
        'division',
        'faculty',
        'campus',

        [
        	'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
            	'update' => function ($url, $model, $key) {
       				return Html::a('<i class="fa fa-pencil"></i>', ['/apn/edu/update', 'code' => Yii::$app->request->get('code'), 'id' => $model->id], ['title' => Yii::t('app', 'update')]);
    			},
    		]
        ],
    ],
]);