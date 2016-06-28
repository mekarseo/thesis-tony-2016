<?php 
/**
 * @Final File
 */
use yii\widgets\DetailView;

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-university"></i> ' . Yii::t('app', 'Education Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'education.old_school',
        'education.school_provice',
        'education.branch',
        'education.graduate',
        'education.gpa_graduation',
        [
            'attribute' => 'education.level',
            'value'     => $model->education->levelName->name,
        ],
        'education.faculty',
        'education.major',
    ],
]);
echo '</div></div>';