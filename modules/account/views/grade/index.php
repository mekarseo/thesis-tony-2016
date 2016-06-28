<?php
/**
 * @Final File
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

if ($success) {
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'       => $dataLayout,
    'options'      => ['class' => 'grid-view panel-crud'],
    'pager'        => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
        ],

        'term',
        'grade',
        'create_at:datetime',
    ],
]);
Pjax::end();