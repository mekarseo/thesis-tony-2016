<?php

use miloschuman\highcharts\Highcharts;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\widgets\LinkPager;

$form = ActiveForm::begin([
    'method' => 'get',
    'layout' => 'horizontal',
    'id' => 'form-data',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-5',
            'wrapper' => 'col-sm-3',
        ],
    ],
]);

echo $form->field($model, 'term')->dropDownList($model->getTerms(), [
        'onchange' => "submit()",
    ]);

ActiveForm::end();
/*
$xAxis  = (array)$model->getHeadColumn($dataProvider);
$series = array();
foreach($model->getRow($dataProvider) as $title => $rows) {
  $tmp['name'] = $title;
  $tmp['data'] = array();
  foreach($rows as $row => $value) {
    array_push($tmp['data'], (int)$value);
  }
  $series[] = $tmp; 
}
echo "<pre>"; print_r($series); echo "</pre>";
echo Highcharts::widget([
   'options' => [
      'title' => ['text' => Yii::t('app', 'Talent Stat')],
      'xAxis' => [
        'categories' => $xAxis,
        'labels' => [
          'rotation' => '-45',
          'style' => [
            'fontSize' => '13px',
          ]
        ]
      ],
      'yAxis' => [
         'title' => ['text' => Yii::t('app', 'Sum')]
      ],
      'series' => $series,
   ]
]);*/
?>
<div class="stat">
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<td rowspan="2" class="headColumn"><?php echo Yii::t('app', 'Talent Sub'); ?></td>
			<td colspan="<?php echo count($model->getHeadColumn($dataProvider));?>" class="headColumn"><?php echo Yii::t('app', 'Faculty'); ?> (<?php echo Yii::t('app', 'Person'); ?>)</td>
      <td rowspan="2" class="headColumn"><?php echo Yii::t('app', 'Summary'); ?></td>
		</tr>
    <tr class="vertical">
    <?php foreach($model->getHeadColumn($dataProvider) as $head) {;?>
      <td><div><span class="headVertical"><?php echo $head;?></span></div></td>
    <?php }?>
    </tr>
	</thead>
	<tbody>
  <?php $summary = 0; foreach($model->getRow($dataProvider) as $title => $rows) { $counter = 0; ?>
    <tr>
		  <td><?php echo $title;?></td>
      <?php foreach($rows as $row => $value) { $counter += $value; ?>
      <td align="center"><?php echo $value == 0 ? '-':$value;?></td>
      <?php } $summary += $counter;?>
      <td align="center"><?php echo $counter; ?></td>
		</tr>
  <?php }?>
	</tbody>
  <tfoot>
    <tr>
      <td colspan="<?php echo count($model->getHeadColumn($dataProvider))+1;?>" align="center"><b>รวม</b></td>
      <td align="center"><?php echo $summary;?></td>
    <tr>
  </tfoot>
</table>
<div align="center">
  <?php //echo LinkPager::widget(['pagination' => $model->getPagers($dataProvider)]); ?>
</div>
</div>
<?php
$js = <<<JS
$(document).ready(function(){
  var maxTd = 0
  $(".headVertical").each(function(element){
    if ($(this).width() > maxTd) {
      maxTd = $(this).width();
    }
  });
  $(".headVertical").closest('tr').height(maxTd+20);
});
JS;
$this->registerJs($js, View::POS_END);