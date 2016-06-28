<?php
/**
 * @Final File
 */
use yii\widgets\Pjax;

if ($success) {
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

if ($error) {
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

Pjax::begin();

include('_list_'.strtolower($code).'.php');

Pjax::end();