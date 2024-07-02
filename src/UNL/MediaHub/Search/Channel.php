<?php

class UNL_MediaHub_Search_Channel implements UNL_MediaHub_Search_ListInterface
{
    public $options = array();

    public $feedList;

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;

        if (isset($this->options['format']) && $this->options['format'] === 'html') {
            UNL_MediaHub::redirect(UNL_MediaHub_Search::getSearchURLWithParams());
        }

        $feedOptions = array();
        $feedOptions['filter'] = new UNL_MediaHub_FeedList_Filter_WithTerm($this->options['q']);
        $feedOptions['limit'] = 0;

        $this->feedList = new UNL_MediaHub_FeedList($feedOptions);
        $this->feedList->run();
    }

    public function getItems()
    {
        return $this->feedList;
    }

    public function getQuery()
    {
        return $this->options['q'];
    }
}
