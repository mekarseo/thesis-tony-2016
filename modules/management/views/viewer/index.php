<?php
/**
 * @Final File
 */
use yii\grid\GridView;
use yii\widgets\Pjax;

if ($success) {
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['class' => 'grid-view panel-crud'],
    'layout' => $dataLayout,
    'pager' => ['options' => ['class' => 'pagination pagination-sm']],
    'columns' => [
        [
            'headerOptions' => ['width' => '1%'],
            'header' => "<input type=\"checkbox\" onclick=\"$('input[name*=\'selected\']').prop('checked', this.checked);\" />",
            'value' => function ($model) {
                return '<input type="checkbox" name="selected[]" value="' . $model->id . '" />';
            },
            'format' => 'raw',
        ],

        'username',
        'email:email',
        'created_at:datetime',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
        ],
    ],
]);
Pjax::end();