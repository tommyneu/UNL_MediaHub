<?php
/**
 * A Playlist within the mediahub system.
 * 
 * @author Thomas Neumann
 */
class UNL_MediaHub_Playlist extends UNL_MediaHub_Models_BasePlaylist
{
    protected $namespaces = array();
    
    /**
     * Get by ID
     *
     * @param int $id The id of the feed to get
     *
     * @return UNL_MediaHub_Feed
     */
    static function getById($id)
    {
        return Doctrine::getTable(__CLASS__)->find($id);
    }
    
    /**
     * get a feed by the title
     *
     * @param string $title Title of the feed/channel
     *
     * @return UNL_MediaHub_Feed
     */
    static function getByTitle($title)
    {
        return Doctrine::getTable(__CLASS__)->findOneByTitle($title);
    }
    
    /**
     * Add Media to the feed
     *
     * @param UNL_MediaHub_Media $media The media to add
     *
     * @return unknown
     */
    function addMedia(UNL_MediaHub_Media $media)
    {
        $this->UNL_MediaHub_Media[] = $media;
        $this->save();
        return true;
    }

    function removeMedia(UNL_MediaHub_Media $media)
    {
        $q = Doctrine_Query::create()
            ->delete('UNL_MediaHub_Feed_Media')
            ->addWhere('feed_id = ?', $this->id)
            ->addWhere('media_id = ?', $media->id);

        return $q->execute();
    }

    /**
     * @param Doctrine_Connection $conn
     * @return bool|void
     */
    public function delete(Doctrine_Connection $conn = null)
    {
        //Delete feed_has_media records
        $media_list = $this->getMediaList();
        $media_list->run();

        if (count($media_list->items)) {
            foreach ($media_list->items as $media) {
                $this->removeMedia($media);
            }
        }

        parent::delete($conn);
    }
    
    public function getMediaList($options = array())
    {
         return new UNL_MediaHub_MediaList(array('filter'=>new UNL_MediaHub_MediaList_Filter_ByPlaylist($this))+$options); 
    }

    /**
     * Add a playlist to the system
     *
     * @param array             $data Associative array of details.
     * @param UNL_MediaHub_User $user User creating this playlist
     *
     * @return UNL_MediaHub_Playlist
     */
    public static function addPlaylist($data, UNL_MediaHub_User $user, UNL_MediaHub_Feed $feed)
    {
        $data = array_merge(
            $data,
            array(
                'feed_id' => $feed->id,
                'datecreated' => date('Y-m-d H:i:s'),
                'uidcreated'  => $user->uid,
                'uidupdated'  => $user->uid
            )
        );
        $playlist = new self();
        $playlist->fromArray($data);
        $playlist->save();
        return $playlist;
    }

    /**
     * Check if this playlist is linked to the related media.
     *
     * @param UNL_MediaHub_Media $media The media file we're checking
     *
     * @return bool
     */
    public function hasMedia(UNL_MediaHub_Media $media)
    {
        $query = new Doctrine_Query();
        $query->from('UNL_MediaHub_Playlist_Media')
              ->where('playlist_id = ? AND media_id = ?', array($this->id, $media->id));
        return $query->count();
    }
}
