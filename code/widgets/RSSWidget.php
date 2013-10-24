<?php

class RSSWidget extends ShortcodeWidget
{

    public static $cmsTitle = "RSS widget";
    public static $description = "Display a RSS feed";

    public static $db = array(
        'FeedURL' => 'Varchar',
        'ItemsToShow' => 'Int'
    );

    public function getSummary()
    {
        return _t('RSSWidget.FEED_URL', 'Feed URL') . ': ' . $this->FeedURL;
    }

    public function getCMSFields()
    {
        SiteTree::disableCMSFieldsExtensions();
        $fields = parent::getCMSFields();
        SiteTree::enableCMSFieldsExtensions();

        $fields->addFieldToTab('Root.Main', $feedURLField = new TextField('FeedURL', _t('RSSWidget.FEED_URL', 'Feed URL')));
        $feedURLField->setDescription(_t('RSSWidget.FEED_URL_DESCRIPTION', 'URL to the RSS feed you want to display.'));

        $fields->addFieldToTab('Root.Main', $itemsToShowField = new SliderField('ItemsToShow', _t('RSSWidget.ITEMS_TO_SHOW', 'Number of items to show'), 1, 20));
        $itemsToShowField->setDescription(_t('RSSWidget.ITEMS_TO_SHOW_DESCRIPTION', 'How many items do you want to show?'));

        $this->extend('updateCMSFields', $fields);
        return $fields;
    }

    public function FeedItems()
    {
        if(!$this->FeedURL){
            return false;
        }

        include_once( Director::getAbsFile('shortcodewidgets/thirdparty/simplepie/autoloader.php') );
        $feed = new SimplePie();
        $feed->set_feed_url($this->FeedURL);
        $feed->init();
        $items = $feed->get_items(0, $this->ItemsToShow ? $this->ItemsToShow : 10);
        if(!$items){
            return false;
        }

        $output = new ArrayList();
        foreach($items as $item) {
            $date = new Date('Date');
            $date->setValue($item->get_date());
            $title = new Text('Title');
            $title->setValue($item->get_title());
            $output->push(new ArrayData(array(
                'Title' => $title,
                'Date' => $date,
                'Link' => $item->get_link()
            )));
        }
        return $output;
    }

}

?>