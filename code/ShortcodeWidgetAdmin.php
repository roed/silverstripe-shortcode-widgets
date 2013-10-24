<?php

class ShortcodeWidgetAdmin extends ModelAdmin {

    public static $menu_icon = 'shortcodewidgets/images/icons/admin-icon.png';
    public static $managed_models = array('ShortcodeWidget');
    public static $url_segment = 'widgets';
    public static $menu_title = 'Widgets';
    public static $model_importers = array();

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        $gridfield = $form->fields->fieldByName('ShortcodeWidget');
        $gridfield->getConfig()->removeComponentsByType('GridFieldExportButton');
        $gridfield->getConfig()->removeComponentsByType('GridFieldPrintButton');
        $datacolumn = $gridfield->getConfig()->getComponentByType('GridFieldDataColumns');
        $datacolumn->setFieldFormatting(array('ClassName' => '$Type'));
        return $form;
    }

    public function getSearchContext() {
        $context = parent::getSearchContext();
        $types = ShortcodeWidget::getWidgetTypes(true);
        $context->getFields()->push(new DropdownField('q[ClassName]', 'Type', $types));
        return $context;
    }

}