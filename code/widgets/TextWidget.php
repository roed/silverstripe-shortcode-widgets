<?php

class TextWidget extends ShortcodeWidget
{

    public static $cmsTitle = "Text widget";
    public static $description = "Display a block of text";

    public static $db = array(
        'Content' => 'HTMLText'
    );

    public function getSummary()
    {
        $content = DBField::create_field('HTMLText', $this->Content);
        return _t('TextWidget.CONTENT', 'Content') . ': ' . $content->LimitWordCountXML(5);
    }

    public function getCMSFields()
    {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $contentField = new HtmlEditorField('Content', _t('TextWidget.CONTENT', 'Content')));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

}

?>