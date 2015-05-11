<?php
/**
 * Controller for the public frontend to the MediaHub system.
 */
class UNL_MediaHub_Controller
    implements UNL_MediaHub_CacheableInterface, UNL_MediaHub_PostRunReplacements
{
    /**
     * Array of options
     *
     * @var array
     */
    public $options = array(
        'model'  => false,
        'format' => 'html',
    );

    /**
     * UNL template style to use.
     *
     * @var string
     */
    public $template = 'Fixed';

    /**
     * Any output prepared by this controller will go here. This could be
     * any type of data, string, array or object.
     *
     * @var mixed
     */
    public $output;

    /**
     * URL to this controller.
     *
     * @var string
     */
    public static $url;

    /**
     * URL to a thumbnail generator for media files.
     *
     * @var string
     */
    public static $thumbnail_generator;

    static protected $replacements;

    /**
     * The current embed version to serve
     * 
     * @var int
     */
    public static $current_embed_version = 2;

    protected $view_map = array(
        'search'  => 'UNL_MediaHub_MediaList',
        'tags'    => 'UNL_MediaHub_MediaList',
        'default' => 'UNL_MediaHub_DefaultHomepage',
        'feeds'   => 'UNL_MediaHub_FeedList',
        'feed'    => 'UNL_MediaHub_FeedAndMedia',
        'dev'     => 'UNL_MediaHub_Developers',
        'live'    => 'UNL_MediaHub_Feed_LiveStream',
        );

    /**
     * Backend media system.
     *
     * @var UNL_MediaHub
     */
    protected $mediahub;

    public static $usedMediaNameSpaces = array(
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunesu',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunes',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_media',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_boxee',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_geo',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_mediahub');
    /**
     * Construct a new controller.
     *
     * @param string $dsn Database connection string
     */
    function __construct($options)
    {
        // Set up database
        $this->mediahub = new UNL_MediaHub();

        // Initialize default options
        $this->options = $options + $this->options;
        
        if ($this->options['model'] == 'media_embed') {
            $this->options['format'] = 'js';
        }

        UNL_MediaHub_AuthService::getInstance()->autoLogin($this->options['model']);

        UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::$uri = UNL_MediaHub_Controller::$url . "schema/mediahub.xsd";
    }
    
    
    public static function getNamespaceDefinationString()
    {
        $namespaces = "";
        foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $class) {
            $class = new ReflectionClass($class);
            $namespaces .= "xmlns:" . $class->getStaticPropertyValue('xmlns') . "='" . $class->getStaticPropertyValue('uri') . "' ";
        }

        return $namespaces;
    }

    /**
     * Get the cache key for the data prepared by the controller
     *
     * @return string|false
     */
    function getCacheKey()
    {
        return false;
    }

    /**
     * function called before output, cached or otherwise is sent.
     *
     * @return bool
     */
    function preRun($cached)
    {
        if ($this->options['model'] == 'feed_image'
            || $this->options['model'] == 'media_image'
            || $this->options['model'] == 'media_embed'
            || $this->options['model'] == 'media_srt'
            || $this->options['model'] == 'media_vtt') {
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Controller', 'ControllerPartial');
        }
        
        // Send headers for CORS support so calendar bits can be pulled remotely
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With');
        if (isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // short circuit execution for CORS OPTIONS reqeusts
            exit();
        }
        
        switch ($this->options['format']) {
        case 'xml':
        case 'rss':
            // Send XML content-type headers, and assign XML output template.
            header('Content-type: text/xml');
            break;
        case 'partial':
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Controller', 'ControllerPartial');
            break;
        case 'json':
            header('Content-type: application/json');
            break;
        case 'srt':
            header('Content-type: text/srt');
            break;
        case 'vtt':
            header('Content-type: text/vtt');
            break;
        case 'js':
            header('Content-type: text/javascript');
            break;
        default:
            break;
        }
        
        return true;
    }

    /**
     * Main hub for the controller.
     *
     * This will be called when cached output cannot be found.
     *
     * @return void
     */
    function run()
    {
        try {
            if (!empty($_POST)) {
                $this->handlePost($_POST);
            }
            
            if (!isset($this->options['model'])
                || false === $this->options['model']) {
                throw new Exception('Un-registered view', 404);
            }
            switch ($this->options['model']) {
            case 'media':
                $media = $this->findRequestedMedia($this->options);

                if (!$media->canView()) {
                    throw new Exception('You do not have permission to do this.', 403);
                }
                
                $this->output[] = $media;
                break;
            case 'feed_image':
                if (isset($this->options['feed_id'])) {
                    $this->output[] = UNL_MediaHub_Feed_Image::getById($this->options['feed_id']);
                } else {
                    $this->output[] = UNL_MediaHub_Feed_Image::getByTitle($this->options['title']);
                }
                break;
            case 'media_srt':
                $this->output[] = new UNL_MediaHub_Media_VideoTextTrack($this->options['id'], 'srt');
                break;
            case 'media_vtt':
                $this->output[] = new UNL_MediaHub_Media_VideoTextTrack($this->options['id'], 'vtt');
                break;
            case 'media_json':
                $this->output[] = new UNL_MediaHub_Media_VideoTextTrack($this->options['id'], 'json');
                break;
            case 'media_image':
                $this->output[] = UNL_MediaHub_Media_Image::getById($this->options['id']);
                break;
            case 'media_embed':
                $id = null;
                if (isset($this->options['id'])) {
                    $id = $this->options['id'];
                }
                $version = 1;
                if (isset($this->options['version'])) {
                    $version = $this->options['version'];
                }
                $this->output[] = UNL_MediaHub_Media_Embed::getById($id, $version, $this->options);
                break;
            case 'media_file':
                $this->output[] = UNL_MediaHub_Media_File::getById($this->options['id']);
                break;
            default:
                $this->output[] = new $this->options['model']($this->options);
            }
        } catch(Exception $e) {
            $this->output[] = $e;
        }
    }

    /**
     * Find a specific piece of media.
     *
     * @param array $options Associative array of options $options['id']
     *
     * @throws Exception
     * @return UNL_MediaHub_Media
     */
    function findRequestedMedia($options)
    {
        $media = false;
        if (isset($options['id'])) {
            $media = Doctrine::getTable('UNL_MediaHub_Media')->find($options['id']);
        }
        
        if ($media) {
            return $media;
        }

        throw new Exception('Cannot determine the media to display.');
    }

    /**
     * @param $post
     * @throws Exception
     */
    protected function handlePost($post)
    {
        $auth = UNL_MediaHub_AuthService::getInstance();
        
        if (!$media = $this->findRequestedMedia($this->options)) {
            throw new Exception('Media ID must be passed.');
        }
        
        if (isset($post['action'])) {
            switch ($post['action']) {
                case 'playcount':
                    //Log the view.
                    $ip_address = NULL;
                    if (isset($_SERVER['REMOTE_ADDR'])) {
                        $ip_address = $_SERVER['REMOTE_ADDR'];
                    }
                    
                    $view = UNL_MediaHub_MediaView::logView($media, $ip_address);
                    //Don't need to send a response, so stop here.
                    exit();
                    break;
            }
        }

        if (!empty($post['comment'])) {
            if (!$user = $auth->getUser()) {
                throw new Exception('You must be logged in to make a comment.', 403);
            }
            
            $data = array(
                'uid'      => $user->uid,
                'media_id' => $media->id,
                'comment'  => $post['comment']
            );

            $comment = new UNL_MediaHub_Media_Comment();
            $comment->fromArray($data);
            $comment->save();
            
            UNL_MediaHub::redirect(self::getURL($media));
        }

        if (!empty($post['tags'])) {
            if (!$user = $auth->getUser()) {
                throw new Exception('You must be logged in to add a tag.', 403);
            }
            
            if (!$media->userCanEdit($user)) {
                throw new Exception('You do not have permission to add a tag.', 403);
            }
            
            foreach (explode(',', $post['tags']) as $tag) {
                $media->addTag(trim($tag));
            }
        }
    }

    /**
     * Called after run - with all output contents.
     *
     * @param string $me The content from the outputcontroller
     *
     * @return string
     */
    function postRun($me)
    {
        $scanned = new UNL_Templates_Scanner($me);

        if (isset(self::$replacements['title'], $scanned->doctitle)) {
            $me = str_replace($scanned->doctitle,
                              '<title>'.self::$replacements['title'].'</title>',
                              $me);
            unset(self::$replacements['title']);
        }

        if (isset(self::$replacements['head'])) {
            $me = str_replace('</head>', self::$replacements['head'].'</head>', $me);
        }

        if (isset(self::$replacements['breadcrumbs'], $scanned->breadcrumbs)) {
            $me = str_replace($scanned->breadcrumbs,
                              self::$replacements['breadcrumbs'],
                              $me);
            unset(self::$replacements['breadcrumbs']);
        }

        return $me;
    }

    /**
     * Allows you to set dynamic data when cached output is sent.
     *
     * @param string $field Area to be replaced
     * @param string $data  Data to replace the field with.
     *
     * @return void
     */
    public function setReplacementData($field, $data)
    {
        self::$replacements[$field] = $data;
    }

    /**
     * Gets replacement data
     *
     * @param string $field Key for the data to get
     *
     * @return string|boolean False on failure
     */
    public static function getReplacementData($field)
    {
        if (isset(self::$replacements[$field])) {
            return self::$replacements[$field];
        }

        return false;
    }

    /**
     * Get the URL for the system or a specific object this controller can display.
     *
     * @param mixed $mixed             Optional object to get a URL for.
     * @param array $additional_params Extra parameters to adjust the URL.
     *
     * @return string
     */
    public static function getURL($mixed = null, $additional_params = array())
    {
        $params = array();

        $url = UNL_MediaHub_Controller::$url;

        if (is_object($mixed)) {
            switch (get_class($mixed)) {
            case 'UNL_MediaHub_Media':
                $url .= 'media/'.$mixed->id;
                break;
            case 'UNL_MediaHub_MediaList':
                $url = $mixed->getURL();
                break;
            case 'UNL_MediaHub_Feed':
                $url .= 'channels/'.$mixed->id;
                break;
            case 'UNL_MediaHub_FeedList':
                $url .= 'channels/';
                break;
            default:

            }
        }

        $params = array_merge($params, $additional_params);

        return self::addURLParams($url, $params);
    }

    /**
     * Add unique querystring parameters to a URL
     *
     * @param string $url               The URL
     * @param array  $additional_params Additional querystring parameters to add
     *
     * @return string
     */
    public static function addURLParams($url, $additional_params = array())
    {
        $params = array();
        if (strpos($url, '?') !== false) {
            list($url, $existing_params) = explode('?', $url);
            $existing_params = explode('&', $existing_params);
            foreach ($existing_params as $val) {
                list($var, $val) = explode('=', $val);
                $params[$var] = $val;
            }
        }

        $params = array_merge($params, $additional_params);

        $url .= '?';

        foreach ($params as $option=>$value) {
            if (in_array($option, array('driver', 'model', 'filter'))) {
                continue;
            }
            if ($option == 'format'
                && $value == 'html') {
                continue;
            }
            if (!empty($value)) {
                $url .= "&$option=$value";
            }
        }
        $url = str_replace('?&', '?', $url);
        return trim($url, '?;=');
    }
}

