<?php
/**
 * Abstract class for holding a playlist and all related media
 * 
 * @author Thomas Neumann
 */
class UNL_MediaHub_FeedAndMedia
{
    /**
     * The Feed object
     * 
     * @var UNL_MediaHub_Playlist
     */
    public $playlist;
    
    /**
     * List of associated media.
     * 
     * @var UNL_MediaHub_MediaList
     */
    public $media_list;
    
    /**
     * Construct a Playlist and Media object.
     * 
     * @param array $options Associative array of options
     * 
     * @see UNL_MediaHub_MediaList
     * 
     * @return void
     */
    function __construct($options = array())
    {

        if (!empty($options['playlist'])) {
            $this->playlist = $options['playlist'];
        } elseif (!empty($options['playlist_id'])) {
            $this->playlist = UNL_MediaHub_Playlist::getById($options['playlist_id']);
        } elseif (!empty($options['title'])) {
            $this->playlist = UNL_MediaHub_Playlist::getByTitle($options['title']);
        }

        if (false === $this->feed) {
            throw new Exception('That feed could not be found.', 404);
        }

        $this->media_list = new UNL_MediaHub_MediaList(array('filter'=>new UNL_MediaHub_MediaList_Filter_ByPlaylist($this->playlist))+$options);
    }
    
    /**
     * Set the media list
     * 
     * @param UNL_MediaHub_MediaList $media_list The list of media
     * 
     * @return void
     */
    public function setMediaList(UNL_MediaHub_MediaList $media_list)
    {
        $this->media_list = $media_list;
    }
}

?>