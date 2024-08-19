<?php
abstract class UNL_MediaHub_Models_BasePlaylist extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('feeds');
        $this->hasColumn('id',          'integer',   4,    array('unsigned' => 0, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('feed_id',     'integer',   4,    array('unsigned' => 0, 'primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('title',       'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('description', 'string',    null, array('primary' => false, 'notnull' => false, 'autoincrement' => false));
        $this->hasColumn('uidcreated',  'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('uidupdated',  'string',    null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('datecreated', 'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
        $this->hasColumn('dateupdated', 'timestamp', null, array('primary' => false, 'notnull' => true, 'autoincrement' => false));
    }

    public function setUp()
    {
        $this->hasMany('UNL_MediaHub_Media', array('local' => 'playlist_id',
                                                    'foreign' => 'media_id',
                                                    'refClass' => 'UNL_MediaHub_Playlist_Media'));
        $this->hasOne('UNL_MediaHub_Feed', array('local' => 'feed_id',
                                                    'foreign' => 'id'));
        parent::setUp();
    }
  
}