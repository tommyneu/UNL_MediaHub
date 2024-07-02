<?php

class UNL_MediaHub_Search_Video implements UNL_MediaHub_Search_ListInterface
{
    public $options = array();

    public $mediaList;

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;

        if (isset($this->options['format']) && $this->options['format'] === 'html') {
            UNL_MediaHub::redirect(UNL_MediaHub_Search::getSearchURLWithParams());
        }

        $videoOptions = array();
        $videoOptions['filter'] = new UNL_MediaHub_MediaList_Filter_TextSearch($this->options['q']);
        $videoOptions['additional_filters'][] = new UNL_MediaHub_MediaList_Filter_Video();
        $videoOptions['filter_preset'] = true;
        $videoOptions['limit'] = 0;

        $this->mediaList = new UNL_MediaHub_MediaList($videoOptions);
    }

    public function getItems()
    {
        return $this->mediaList;
    }

    public function getQuery()
    {
        return $this->options['q'];
    }
}
