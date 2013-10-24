<?php

class FlashWidget extends ShortcodeWidget
{

    public static $cmsTitle = "Flash widget";
    public static $description = "Display a flash element";

    public static $db = array(
        'Width' => 'Int',
        'Height' => 'Int',
        'AlternativeContent' => 'HTMLText'
    );

    public static $has_one = array(
        'FlashFile' => 'File'
    );

    public function getSummary()
    {
        return _t('FlashWidget.WIDTH', 'Width') . ': ' . $this->Width . ' ' . _t('FlashWidget.HEIGHT', 'Height') . ': ' . $this->Height;
    }

    public function getCMSFields()
    {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $widthField = new SliderField('Width', _t('FlashWidget.WIDTH', 'Width'), 1, 1000));
        $widthField->setDescription(_t('FlashWidget.WIDTH_DESCRIPTION', 'Width of the flash element.'));

        $fields->addFieldToTab('Root.Main', $heightField = new SliderField('Height', _t('FlashWidget.HEIGHT', 'Height'), 1, 1000));
        $heightField->setDescription(_t('FlashWidget.HEIGHT_DESCRIPTION', 'Height of the flash element.'));

        $fields->addFieldToTab('Root.Main', $flashFileField = new UploadField('FlashFile', _t('FlashWidget.FLASHFILE', 'Flash file')));
        $flashFileField->getValidator()->setAllowedExtensions(array('swf'));
        $flashFileField->setDescription(_t('FlashWidget.FLASHFILE_DESCRIPTION', 'Choose a swf file.'));

        $fields->addFieldToTab('Root.Main', $alternativeContentField = new HtmlEditorField('AlternativeContent', _t('FlashWidget.ALTERNATIVECONTENT', 'Alternative content')));
        $alternativeContentField->setDescription(_t('FlashWidget.ALTERNATIVECONTENT_DESCRIPTION', 'Content to display when flash is not supported.'));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

}

?>