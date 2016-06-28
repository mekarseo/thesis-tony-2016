<?php
/**
 * @Final File
 */
namespace app\components;

use Yii;
use yii\bootstrap\Widget;

class Gridmenu extends Widget {
	protected static $startTag = <<< HTML
	<div class="app-module-menu">
HTML;
	protected static $endTag = <<< HTML
	</div>
HTML;
	protected static $template = <<< HTML
	<a class="shortcuts" href="{{link}}">
	    <i class="shortcut-icon {{class}}"></i>
	    <span class="shortcut-label">{{text}}</span>
	</a>
HTML;

	public static function widget($data = []) {
		$html  = self::$startTag;

		foreach ($data as $tmp) {
			if(empty($tmp))
				continue;
			$tmpHTML = self::$template;
			$tmpHTML = str_replace('{{link}}', $tmp['url'], $tmpHTML);
			$tmpHTML = str_replace('{{class}}', $tmp['class'], $tmpHTML);
			$tmpHTML = str_replace('{{text}}', $tmp['label'], $tmpHTML);
			$html .= $tmpHTML;
		}

		$html .= self::$endTag;
		
		return $html;
	}
}