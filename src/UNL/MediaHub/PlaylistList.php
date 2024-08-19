<?php

class UNL_MediaHub_PlaylistList extends UNL_MediaHub_List
{
    
    public $options = array(
        'orderby'            => 'datecreated',
        'order'              => 'DESC',
        'page'               => 0,
        'limit'              => 12,
        'filter'             => null,
        'additional_filters' => array(),
        'f'                  => '',
    );
   
    public $tables = 'playlists p';
    protected $select = '{p.id}';

    public function __construct($options = array())
    {
        //Dont paginate if we are not viewing html.
        if (isset($options['format']) && $options['format'] !== 'html' && !isset($options['limit'])) {
            $options['limit'] = 0;
        }
        
        $this->options = $options + $this->options;
        $this->filterInputOptions();
        $this->setUpFilter();
        $this->run();
    }

    public function setUpFilter()
    {
        // Default filter
        if (!isset($this->options['filter'])) {
            $this->options['filter'] = new UNL_MediaHub_MediaList_Filter_ShowRecent();
        }
    }

    public function filterInputOptions()
    {
        switch ($this->options['order']) {
            case 'ASC':
            case 'DESC':
                break;
            default:
                $this->options['order'] = 'DESC';
                break;
        }
        
        switch ($this->options['orderby']) {
            case 'datecreated':
            case 'title_a_z':
            case 'title_z_a':
                break;
            default:
                $this->options['orderby'] = 'datecreated';
                break;
        }

        $this->options['additional_filters'] = array();
        $this->options['page'] = (int)$this->options['page'];
    }
    
    public function setOrderBy(Doctrine_Query_Abstract $query)
    {
        $order_by = $this->options['orderby'];
        if (in_array($order_by, array('title_a_z', 'title_z_a'))) {
            $order_by = 'title';
        }
        
        $query->orderby('p.'.$order_by.' '.$this->options['order']);
    }
    
    public function getURL($params = array())
    {
        return '';
    }

    /**
     * @return Doctrine_Query_Abstract
     */
    protected function createQuery()
    {
        $query = new Doctrine_RawSql();
        $query->addComponent('p', 'UNL_MediaHub_Playlist p');
        
        return $query;
    }
}
