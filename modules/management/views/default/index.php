<?php
/**
 * @Final File
 */
use app\components\Gridmenu;

echo Yii::t('app', 'Upload CSV to Database');
echo Gridmenu::widget([
  ['label' => $t_csv_upload, 'url' => $l_csv_upload, 'class' => $c_csv_upload],
]);

echo Yii::t('app', 'User Management');
echo Gridmenu::widget([
  ['label' => $t_teacher, 'url' => $l_teacher, 'class' => $c_teacher],
  ['label' => $t_viewer, 'url' => $l_viewer, 'class' => $c_viewer],
  ['label' => $t_student, 'url' => $l_student, 'class' => $c_student],  
]);

echo Yii::t('app', 'Serial Generator');
echo Gridmenu::widget([
  ['label' => $t_serial, 'url' => $l_serial, 'class' => $c_serial],
]);