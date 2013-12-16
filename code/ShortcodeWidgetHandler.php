<?php

class ShortcodeWidgetHandler
{

    public static function handle($arguments, $content = null, $parser = null, $tag = null) {
        $topLevel = SSViewer::topLevel();
        $id = $arguments['id'];
        $widget = ShortcodeWidget::get()->filter(array('ID' => $id))->first();
	    $widget->Controller = $topLevel;
        if(!$widget){
            return '';
        }
        if($topLevel->ClassName == $widget->ClassName && $topLevel->ID == $widget->ID){
            return '';
        }
        return $widget->forTemplate();
    }

}
