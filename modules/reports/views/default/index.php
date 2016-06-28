<?php
/**
 * @Final File
 */
use app\components\Gridmenu;

echo Yii::t('app', 'Reports');
echo Gridmenu::widget([
  isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Teacher']) ? ['label' => $t_nofication, 'url' => $l_nofication, 'class' => $c_nofication] : [],
  !isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Teacher']) ? ['label' => $t_teacher, 'url' => $l_teacher, 'class' => $c_teacher] : [],
  ['label' => $t_student, 'url' => $l_student, 'class' => $c_student],
  ['label' => $t_activity, 'url' => $l_activity, 'class' => $c_activity],
]);

echo Yii::t('app', 'Stats');
echo Gridmenu::widget([
  ['label' => $t_stat_talent_type, 'url' => $l_stat_talent_type, 'class' => $c_stat_talent_type],
  ['label' => $t_stat_talent_sub, 'url' => $l_stat_talent_sub, 'class' => $c_stat_talent_sub],
]);