<?php

class ShortcodeWidgetHandlerAdmin
{

	public static function handle($arguments, $content = null, $parser = null, $tag = null) {
		$id = $arguments['id'];
		$widget = ShortcodeWidget::get()->filter(array('ID' => $id))->first();
		if ($widget) {
			return '[widget id="'.Convert::raw2xml($id).'" type="'.Convert::raw2xml($widget->cmsTitle()).'" title="'.Convert::raw2xml($widget->Title).'"][/widget]';
		}
	}

}