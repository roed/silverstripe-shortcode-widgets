<?php

$moduleName = 'shortcodewidgets';
$folderName = basename(dirname(__FILE__));

if ($folderName != $moduleName) {
    user_error('Naam fout!', E_USER_ERROR);
}

ShortcodeParser::get()->register('widget', array('ShortcodeWidgetHandler', 'handle'));

HtmlEditorConfig::get('cms')->enablePlugins(array('widgets' => '../../../'.$moduleName.'/plugins/widgets/editor_plugin_src.js'));
HtmlEditorConfig::get('cms')->enablePlugins('noneditable');
HtmlEditorConfig::get('cms')->addButtonsToLine(2, 'widgets');

LeftAndMain::require_css($moduleName . '/plugins/widgets/css/lightbox.css');