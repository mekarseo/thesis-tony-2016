<?php
/**
 * @Final File
 */
use app\components\Gridmenu;

echo Gridmenu::widget([
  isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Student']) ? ['label' => $t_grade, 'url' => $l_grade, 'class' => $c_grade] : [],
  isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Student']) ? ['label' => $t_activity, 'url' => $l_activity, 'class' => $c_activity] : [],
  isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Student']) ? ['label' => $t_profile, 'url' => $l_profile, 'class' => $c_profile] : [],
  isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Student']) ? ['label' => $t_education, 'url' => $l_education, 'class' => $c_education] : [],
  ['label' => $t_account, 'url' => $l_account, 'class' => $c_account],
]);