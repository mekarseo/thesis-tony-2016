<?php 
/**
 * @Final File
 */
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;

echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-star"></i> ' . Yii::t('app', 'Grade Info') . '</h3></div><div class="panel-body">';
Pjax::begin();
echo GridView::widget([
    'dataProvider' => $model->grade,
    'summary'      => false,
    'pager'        => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
        ],
        
        'term',
        'grade',
        'create_at:datetime',
    ],
]);
Pjax::end();
echo '</div></div>';

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-star"></i> ' . Yii::t('app', 'Activity Info') . '</h3></div><div class="panel-body">';
Pjax::begin();
echo GridView::widget([
    'dataProvider' => $model->activity,
    'summary'      => false,
    'pager'        => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
        ],
        
        'section',
        'talent',
        'activity',
        'create_at:datetime',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return Yii::t('app', $model->lastHistory);
            },
        ],
    ],
]);
Pjax::end();
echo '</div></div>';

echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-user-secret"></i> ' . Yii::t('app', 'Personal Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
    	'std_id',
        [
            'attribute' => 'personal_id',
            'value' => substr($model->personal_id, 0, 1) . '-' . substr($model->personal_id, 1, 4) . '-' . substr($model->personal_id, 5, 5) . '-' . substr($model->personal_id, 10, 2) . '-' . substr($model->personal_id, 12, 1),
        ],
        'first_name',
        'last_name',
        'birth_date:date',
        [
        	'attribute' => 'mobile',
        	'value' => substr($model->mobile, 0, 2) . '-' . substr($model->mobile, 2, 4) . '-' . substr($model->mobile, 6, 4),
        ],
        'email:email',
        'father',
        [
        	'attribute' => 'father_mobile',
        	'value' => substr($model->father_mobile, 0, 2) . '-' . substr($model->father_mobile, 2, 4) . '-' . substr($model->father_mobile, 6, 4),
        ],
        'mother',
        [
        	'attribute' => 'mother_mobile',
        	'value' => substr($model->mother_mobile, 0, 2) . '-' . substr($model->mother_mobile, 2, 4) . '-' . substr($model->mother_mobile, 6, 4),
        ],
        'parent',
        [
        	'attribute' => 'parent_mobile',
        	'value' => substr($model->parent_mobile, 0, 2) . '-' . substr($model->parent_mobile, 2, 4) . '-' . substr($model->parent_mobile, 6, 4),
        ],
        'parent_address',
        'term',
    ],
]);
echo '</div></div>';

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-university"></i> ' . Yii::t('app', 'Education Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model->education,
    'attributes' => [
        'old_school',
        'school_provice',
        'branch',
        'graduate',
        'gpa_graduation',
        [
            'attribute' => 'level',
            'value'     => $model->education->levelName->name,
        ],
        'faculty',
        'major',
    ],
]);
echo '</div></div>';

echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-star"></i> ' . Yii::t('app', 'Talent Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model->talent1,
    'attributes' => [
        'talent_type',               
        'talent_sub',
        [
            'attribute' => 'talent_sub1',
            'value'     => ArrayHelper::getValue(json_decode($model->talent1->talent_detail, true), 'sub1'),
        ],
        [
            'attribute' => 'talent_sub2',
            'value'     => ArrayHelper::getValue(json_decode($model->talent1->talent_detail, true), 'sub2'),
        ],
        [
            'attribute' => 'talent_honor',
            'value'     => ArrayHelper::getValue(json_decode($model->talent1->talent_detail, true), 'honor'),
        ],
    ],
]);
echo '</div></div>';