<?php

class ShortcodeWidget extends DataObject
{

    public static function getWidgetTypes($titleonly = false)
    {
        $output = array();
        $classes = ClassInfo::subclassesFor('ShortcodeWidget');
        array_shift($classes);
        foreach ($classes as $class) {
            if(!singleton($class)->stat('exclude_from_list')){
                if($titleonly){
                    $output[$class] = singleton($class)->cmsTitle();
                }else{
                    $output[$class] = singleton($class)->cmsTitle() . ' - ' . singleton($class)->description();
                }
            }
        }
        return $output;
    }

    public static $singular_name = 'Widget';
    public static $plural_name = 'Widgets';
    public static $cmsTitle = '';
    public static $description = '';

    public function cmsTitle() {
        return _t($this->class.'.CMSTITLE', $this->stat('cmsTitle'));
    }

    public function description() {
        return _t($this->class.'.DESCRIPTION', $this->stat('description'));
    }

    public static $db = array(
        'Title' => 'Varchar'
    );

    public static $summary_fields = array(
        'ID',
        'ClassName',
        'Title',
        'Summary'
    );

    public static $searchable_fields = array(
        'Title',
        'ClassName'
    );

    public function fieldLabels($includerelations = true)
    {
        $fields = parent::fieldLabels($includerelations);
        $fields['ClassName'] = _t('ShortcodeWidget.TYPE', 'Type');
        $fields['Title'] = _t('ShortcodeWidget.TITLE', 'Title');
        $fields['Summary'] = _t('ShortcodeWidget.SUMMARY', 'Summary');
        return $fields;
    }

    public function getType()
    {
        return $this->cmsTitle();
    }

    public function getSummary()
    {
        return '';
    }

    public function forTemplate()
    {
        return $this->renderWith(array_reverse(ClassInfo::ancestry($this->class)));
    }

    public function getCMSFields() {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $titleField = new TextField('Title', _t('ShortcodeWidget.TITLE', 'Title')));
        $titleField->setDescription(_t('ShortcodeWidget.TITLE_DESCRIPTION', 'Use a descriptive title so you can recognize the widget.'));
        if($this->class == 'ShortcodeWidget'){
            $fields->addFieldToTab('Root.Main', new DropdownField('Type', _t('ShortcodeWidget.TYPE', 'Type'), ShortcodeWidget::getWidgetTypes()));
        }

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

    public function onBeforeWrite()
    {
        if($this->class == 'ShortcodeWidget'){
            $this->setClassName($this->record['Type']);
        }
        parent::onBeforeWrite();
    }

}
