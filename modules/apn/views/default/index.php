<?php
/**
 * @Final File
 */
use app\components\Gridmenu;

echo Yii::t('app', 'Banner');
echo Gridmenu::widget([
  ['label' => $t_banner_login, 'url' => $l_banner_login, 'class' => $c_banner_login],
  ['label' => $t_banner_home, 'url' => $l_banner_home, 'class' => $c_banner_home],
]);

echo Yii::t('app', 'Talent');
echo Gridmenu::widget([
  ['label' => $t_user_talent, 'url' => $l_user_talent, 'class' => $c_user_talent],
]);

echo Yii::t('app', 'Staff');
echo Gridmenu::widget([
  ['label' => $t_staff, 'url' => $l_staff, 'class' => $c_staff],
]);

echo Yii::t('app', 'Education');
echo Gridmenu::widget([
  ['label' => $t_edu_campus, 'url' => $l_edu_campus, 'class' => $c_edu_campus],
  ['label' => $t_edu_faculty, 'url' => $l_edu_faculty, 'class' => $c_edu_faculty],
  ['label' => $t_edu_division, 'url' => $l_edu_division, 'class' => $c_edu_division],
  ['label' => $t_edu_major, 'url' => $l_edu_major, 'class' => $c_edu_major],
]);

/*echo Yii::t('app', 'Super Admin');
echo Gridmenu::widget([
  ['label' => $t_rbac, 'url' => $l_rbac, 'class' => $c_rbac],
  ['label' => $t_user, 'url' => $l_user, 'class' => $c_user],
]);*/