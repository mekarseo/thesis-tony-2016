<?php
/**
 * @Final File
 */
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

if ($success) {
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

Pjax::begin();
echo GridView::widget([
        'dataProvider'  => $dataProvider,
        'layout'        => $dataLayout,
        'options'       => ['class' => 'grid-view panel-crud'],
        'pager'         => ['options' => ['class' => 'pagination pagination-sm']],
        'columns'       => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'section',
            'talent',
            [
                'attribute' => 'activity',
                'value'     => function ($model) {
                    return nl2br($model->activity);
                },
            ],
            //'create_at:datetime',

            [
                'class'     => 'yii\grid\ActionColumn',
                'template'  => '{view} {update}',
            ],
        ],
    ]);
Pjax::end();

Modal::begin([
    'id' => 'modal-serial',
    'header' => '<h4>' . Yii::t('app', 'Serial Passcode') . '</h4>',
]);

echo '<div class="form-group">'.
     Html::textInput('serial', '', [
        'class' => 'form-control',
        'id' => 'input-serial',
        'placeholder' => Yii::t('app', 'Serial Code..')
    ]).'<label class="control-label"></label></div> ';
echo '<div class="form-group">';
echo Html::submitButton(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'confirm-button']).' ';
echo Html::resetButton(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'id' => 'cancel-button']);
echo '</div>';

Modal::end();

$ajax_link = Url::to(['/account/activity/serial']);
$t_empty = Yii::t('app', 'Please insert serial code.');
$t_duplicate = Yii::t('app', 'This serial is not available or incorrect!');
$js = <<<JS
$(".panel-heading").find(".btn-success").click(function(){
    $("#modal-serial").modal('show');
    return false;
});
$("#cancel-button").click(function(){
    $("#modal-serial").modal('hide');
});
$("#confirm-button").click(function(){
    if ($("#input-serial").val() != '') {
        $.ajax({
            dataType: 'html',
            method: 'GET',
            url: '$ajax_link',
            data: {serial: $("#input-serial").val()},
            success: function(data) {
                if(data == 'true') {
                    window.location = $(".panel-heading").find(".btn-success").attr('href');
                } else {
                    $("#input-serial").closest('.form-group').addClass('has-error');
                    $("#input-serial").closest('.form-group').find(".control-label").html('$t_duplicate');
                }
            },
        });
    } else {
        $("#input-serial").closest('.form-group').addClass('has-error');
        $("#input-serial").closest('.form-group').find(".control-label").html('$t_empty');
    }
});
JS;
$this->registerJs($js, View::POS_END);