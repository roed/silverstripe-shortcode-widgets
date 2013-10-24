<?php

class HTMLWidget extends ShortcodeWidget
{

    public static $cmsTitle = "HTML widget";
    public static $description = "Add custom html code";

    public static $db = array(
        'Content' => 'Text'
    );

    public function getSummary()
    {
        $content = DBField::create_field('Text', $this->Content);
        return _t('HTMLWidget.CONTENT', 'Content') . ': ' . $content->LimitWordCountXML(5);
    }

    public function getCMSFields()
    {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $contentField = new TextareaField('Content', _t('HTMLWidget.CONTENT', 'Content')));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

}

?>