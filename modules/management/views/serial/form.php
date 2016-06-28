<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

echo '<div align="center">'.
	 Html::beginForm(Url::to(['/management/serial/create', 'version' => md5(rand(100001,999999))]), 'get', [
	 	'class' => 'form-inline',
	 	'id' => 'form-data',
	]).
	 '<div class="form-group">'.
	 Html::textInput('row', '', [
	 	'class' => 'form-control',
	 	'id' => 'input-row',
	 	'placeholder' => Yii::t('app', 'Required number?')
	]).'</div> '.
	 Html::button('<i class="fa fa-play-circle"></i> ' . Yii::t('app', 'Generate'), [
		'class' => 'btn btn-danger',
		'id' => 'button-submit',
		'data-toggle' => 'tooltip', 
    	'title' => Yii::t('app', 'Serial Generator'),
	]).
	 Html::endForm().
	 '</div>';
$js = <<<JS
$("#button-submit").click(function(){
	if ($("#input-row").val() == '') {
		$("#input-row").closest('.form-group').removeClass('has-success').addClass('has-error');
	} else {
		$("#input-row").closest('.form-group').removeClass('has-error').addClass('has-success');
		$("#form-data").trigger("submit");
	}
});
JS;
$this->registerJs($js, View::POS_END);