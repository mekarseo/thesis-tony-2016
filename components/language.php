<?php
/**
 * @Final File
 */
namespace app\components;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Url;

class Language extends Widget {
	protected static $template = <<< HTML
	<a class="nav-language" href="{{link}}" title="{{title}}">
	    <span class="badge{{active}}">{{label}}</span>
	</a>
HTML;

	public static function widget($data = []) {
		$html  = '';

		foreach ($data as $tmp) {
			$tmpHTML = self::$template;
			$tmpHTML = str_replace('{{link}}', Url::current(['language' => $tmp['language']]), $tmpHTML);
			$tmpHTML = str_replace('{{label}}', $tmp['label'], $tmpHTML);
			$tmpHTML = str_replace('{{title}}', $tmp['label'], $tmpHTML);
			$tmpHTML = str_replace('{{active}}', Yii::$app->language == $tmp['language'] ? ' active' : '', $tmpHTML);
			$html .= $tmpHTML;
		}

		return $html;
	}
}