<?php

class VimeoWidget extends ShortcodeWidget
{

    public static $cmsTitle = "Vimeo widget";
    public static $description = "Display a vimeo movie";

    public static $db = array(
        'VimeoURL' => 'Varchar',
        'Autoplay' => 'Boolean',
        'ShowInfo' => 'Boolean'
    );

    public function getSummary()
    {
        return _t('VimeoWidget.VIMEOURL', 'Vimeo url') . ': ' . $this->VimeoURL;
    }

    public function getCMSFields()
    {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $vimeoURLField = new TextField('VimeoURL', _t('VimeoWidget.VIMEOURL', 'Vimeo url')));
        $vimeoURLField->setDescription(_t('VimeoWidget.VIMEOURL_DESCRIPTION', 'URL of the vimeo video page.'));

        $fields->addFieldToTab('Root.Main', $autoplayField = new CheckboxField('Autoplay', _t('VimeoWidget.AUTOPLAY', 'Autoplay')));
        $autoplayField->setDescription(_t('VimeoWidget.AUTOPLAY_DESCRIPTION', 'Use this option if you want to start the video automatically.'));

        $fields->addFieldToTab('Root.Main', $showInfoField = new CheckboxField('ShowInfo', _t('VimeoWidget.SHOWINFO', 'Show info')));
        $showInfoField->setDescription(_t('VimeoWidget.SHOWINFO_DESCRIPTION', 'Display additional information about this video in the video?'));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

    public function VimeoVideoID()
    {
        return substr(parse_url($this->VimeoURL, PHP_URL_PATH), 1);
    }

    public function EmbedURL()
    {
        $id = $this->VimeoVideoID();
        $autoplay = $this->Autoplay ? '1' : '0';
        $info = $this->ShowInfo ? '1' : '0';
        return 'http://player.vimeo.com/video/'.$id.'?autoplay='.$autoplay.'&byline='.$info.'&title='.$info;
    }

}

?>