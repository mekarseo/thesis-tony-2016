<?php
/**
 * @Final File
 */
use yii\widgets\DetailView;

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-university"></i> ' . Yii::t('app', 'Teacher Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
    	'username',
    	'firstname',
    	'lastname',
    	'position',
    	'talent_type',
    	'talent_sub',
    	'email',
    ],
]);
echo '</div></div>';