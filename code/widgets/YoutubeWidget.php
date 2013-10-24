<?php

class YoutubeWidget extends ShortcodeWidget
{

    public static $cmsTitle = "Youtube widget";
    public static $description = "Display a youtube movie";

    public static $db = array(
        'YoutubeURL' => 'Varchar',
        'Autoplay' => 'Boolean',
        'ShowControls' => 'Boolean',
        'ShowInfo' => 'Boolean',
        'Theme' => 'Varchar'
    );

    public function getSummary()
    {
        return _t('YoutubeWidget.YOUTUBEURL', 'Youtube url') . ': ' . $this->YoutubeURL;
    }

    public function getCMSFields()
    {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $youtubeurlField = new TextField('YoutubeURL', _t('YoutubeWidget.YOUTUBEURL', 'Youtube url')));
        $youtubeurlField->setDescription(_t('YoutubeWidget.YOUTUBEURL_DESCRIPTION', 'URL of the youtube video page.'));

        $fields->addFieldToTab('Root.Main', $autoplayField = new CheckboxField('Autoplay', _t('YoutubeWidget.AUTOPLAY', 'Autoplay')));
        $autoplayField->setDescription(_t('YoutubeWidget.AUTOPLAY_DESCRIPTION', 'Use this option if you want to start the video automatically.'));

        $fields->addFieldToTab('Root.Main', $showControlsField = new CheckboxField('ShowControls', _t('YoutubeWidget.SHOWCONTROLS', 'Show controls')));
        $showControlsField->setDescription(_t('YoutubeWidget.SHOWCONTROLS_DESCRIPTION', 'Use this option if you want a video without controls.'));

        $fields->addFieldToTab('Root.Main', $showInfoField = new CheckboxField('ShowInfo', _t('YoutubeWidget.SHOWINFO', 'Show info')));
        $showInfoField->setDescription(_t('YoutubeWidget.SHOWINFO_DESCRIPTION', 'Display additional information about this video in the video?'));

        $themes = array(
            'dark' => _t('YoutubeWidget.DARK', 'Dark'),
            'light' => _t('YoutubeWidget.LIGHT', 'Light')
        );
        $fields->addFieldToTab('Root.Main', $themeField = new DropdownField('Theme', _t('YoutubeWidget.THEME', 'Theme'), $themes));
        $themeField->setDescription(_t('YoutubeWidget.THEME_DESCRIPTION', 'Layout theme of the video.'));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

    public function YoutubeVideoID()
    {
        $matches = array();
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $this->YoutubeURL, $matches);
        return $matches[0];
    }

    public function EmbedURL()
    {
        $id = $this->YoutubeVideoID();
        $autoplay = $this->Autoplay ? '1' : '0';
        $controls = $this->ShowControls ? '1' : '0';
        $info = $this->ShowInfo ? '1' : '0';
        $theme = $this->Theme;
        return 'https://www.youtube.com/embed/'.$id.'?autoplay='.$autoplay.'&controls='.$controls.'&rel=0&showinfo='.$info.'&theme='.$theme;
    }

}

?>