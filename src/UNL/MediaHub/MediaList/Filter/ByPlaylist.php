<?php

class UNL_MediaHub_MediaList_Filter_ByPlaylist implements UNL_MediaHub_Filter
{
    protected $playlist;
    protected $privacy = 'PUBLIC';

    /**
     * @param UNL_MediaHub_Playlist $playlist
     */
    public function __construct(UNL_MediaHub_Playlist $playlist)
    {
        $this->playlist = $playlist;
    }
    
    public function apply(Doctrine_Query_Abstract $query)
    {
        $query->addFrom('LEFT JOIN playlist_has_media pm2 ON (pm2.media_id = m.id)');
        $sql = 'pm2.playlist_id = ?';
        $params = array($this->playlist->id);
        
        $query->andWhere($sql, $params);
    }
    
    public function getLabel()
    {
        return '';
    }
    
    public function getType()
    {
        return 'playlist';
    }
    
    public function getValue()
    {
        return $this->playlist;
    }
    
    public function __toString()
    {
        return '';
    }

    public static function getDescription()
    {
        return 'Find media added to a specific playlist';
    }
}
