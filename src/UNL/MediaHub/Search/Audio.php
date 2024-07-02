<?php

class UNL_MediaHub_Search_Audio implements UNL_MediaHub_Search_ListInterface
{
    public $options = array();

    public $mediaList;

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;

        if (isset($this->options['format']) && $this->options['format'] === 'html') {
            UNL_MediaHub::redirect(UNL_MediaHub_Search::getSearchURLWithParams());
        }

        $audioOptions = array();
        $audioOptions['filter'] = new UNL_MediaHub_MediaList_Filter_TextSearch($this->options['q']);
        $audioOptions['additional_filters'][] = new UNL_MediaHub_MediaList_Filter_Audio();
        $audioOptions['filter_preset'] = true;
        $audioOptions['limit'] = 0;

        $this->mediaList = new UNL_MediaHub_MediaList($audioOptions);
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
