<?php

class UNL_MediaHub_Search
{
    public $options = array();

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;

        //TODO: if no query then redirect to browse
    }

    public function getQuery()
    {
        return $this->options['q'];
    }

    public function getSearchVideo()
    {
        $subSearchOptions = array();
        $subSearchOptions['q'] = $this->options['q'];
        return new UNL_MediaHub_Search_Video($subSearchOptions);
    }

    public function getSearchAudio()
    {
        $subSearchOptions = array();
        $subSearchOptions['q'] = $this->options['q'];
        return new UNL_MediaHub_Search_Audio($subSearchOptions);
    }

    public function getSearchChannel() {
        $subSearchOptions = array();
        $subSearchOptions['q'] = $this->options['q'];
        return new UNL_MediaHub_Search_Channel($subSearchOptions);
    }

    public static function getSearchURL() {
        return UNL_MediaHub_Controller::getURL() . 'search/';
    }

    public static function getSearchURLWithParams() {
        $parts = parse_url($_SERVER['REQUEST_URI']);
        return self::getSearchURL() . '?' . $parts['query'];
    }
}
