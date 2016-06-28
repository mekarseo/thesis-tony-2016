<?php
/**
 * @Final File
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\Nofication;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web/image/favicon.png') ?>" type="image/png">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::t('app', $this->title) ?> - <?= Yii::t('app', 'KMUTNB Database System for Students Talent in Sports and Culture.') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php if (Yii::$app->user->isGuest)
        echo '<div style="max-width:800px; margin-bottom:-70px;"><img style="width:100%;" src="' . Yii::getAlias('@web/image/login-title.gif') . '?t=' . time() . '" /></div>';
    ?>
    <?php if (!Yii::$app->user->isGuest)
        echo $this->render('menu');
    ?>

    <div class="container">
        <?= Yii::$app->user->isGuest? '' : @Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>

        <?php Yii::$app->user->isGuest? '' : @Nofication::run(); ?>
    </div>
</div>
<?php if(!Yii::$app->user->isGuest) : ?>
<footer class="panel-footer">
    <div class="text-center">KMUT'NB © 2016 All Rights Reserved.</div>
</footer>
<?php endif; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
