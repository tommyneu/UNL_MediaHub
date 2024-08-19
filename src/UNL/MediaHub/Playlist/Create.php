<?php

class UNL_MediaHub_Playlist_Create
{
    public $options = array();

    public $feed;

    public function __construct($options = array())
    {
        $this->options = $options;

        if (!isset($options['feed_id'])) {
            throw new \Exception('You must pass a feed ID', 400);
        }

        $this->feed = UNL_MediaHub_Feed::getById($this->options['feed_id']);
        if ($this->feed === false) {
            throw new \Exception('Could not find that feed', 404);
        }

        $authUser = UNL_MediaHub_AuthService::getInstance()->getUser();
        if (!$this->feed->userCanEdit($authUser)) {
            throw new UNL_MediaHub_RuntimeException('You do not have permission to manage this channel.', 403);
        }
    }
}
