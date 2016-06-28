<?php
/**
 * @Final File
 */
namespace app\components;

use Yii;
use yii\bootstrap\Widget;

class Formmod extends Widget {
	protected static $startTag = <<< HTML
	<div class="panel panel-form">
HTML;
	protected static $endTag = <<< HTML
	</div>
HTML;
	protected static $template = <<< HTML
	<div class="panel-heading">
        <div class="pull-left"><i class="fa fa-edit"></i> {{t_title}} {{t_form}}</div>
        <div class="pull-right">
            <button type="submit" form="form-data" data-toggle="tooltip" title="{{t_save}}" class="btn btn-primary btn-xs">
                <i class="fa fa-save"></i>
            </button>
            <a href="{{l_cancel}}" data-toggle="tooltip" title="{{t_cancel}}" class="btn btn-default btn-xs">
                <i class="fa fa-reply"></i>
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
HTML;

	public static function begin($config = []) {
		$tmpHtml = str_replace('{{t_title}}', $config['t_title'], self::$template);
		$tmpHtml = str_replace('{{l_cancel}}', $config['l_cancel'], $tmpHtml);
		$tmpHtml = str_replace('{{t_form}}', Yii::t('app', 'Form'), $tmpHtml);
		$tmpHtml = str_replace('{{t_save}}', Yii::t('app', 'Save'), $tmpHtml);
		$tmpHtml = str_replace('{{t_cancel}}', Yii::t('app', 'Cancel'), $tmpHtml);
		
		$html  = self::$startTag;
		$html .= $tmpHtml;

		echo $html;
	}

	public static function end() {
		echo self::$endTag;
	}

	public static function formBegin() {
		echo '<div class="formmod">';
	}

	public static function formEnd() {
		echo '</div>';
	}
}