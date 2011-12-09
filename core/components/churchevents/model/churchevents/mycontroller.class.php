<?php
/**
 * @description This is a generic controller for a CMP so you don't have to rename it each time 
 * you create an extra.  Note you don't need to change things here unless you want more config 
 * options or something
 */
class myController {
    
    /**
     * True if the class has been initialized or not.
     * @var boolean $_initialized
     */
    private $_initialized = false;
    
    /**
     * @param $modx
     * @param $config - array() of config options like array('corePath'=>'Path.../core/)
     */
    function __construct(modX &$modx,array $config = array()) {
    	$this->modx =& $modx;
        $package_name = ( isset($config['packageName']) ? $config['packageName'] : 'package');
        
        $corePath = $modx->getOption($package_name.'.core_path',null,$modx->getOption('core_path').'components/'.$package_name.'/');
        $assetsPath = $modx->getOption($package_name.'.assets_path',null,$modx->getOption('assets_path').'components/'.$package_name.'/');
        $assetsUrl = $modx->getOption($package_name.'.assets_url',null,$modx->getOption('assets_url').'components/'.$package_name.'/');

        $this->config = array_merge(array(
            'basePath' => $corePath,
            'corePath' => $corePath,
            'chunksPath' => $corePath.'elements/chunks/',
            'modelPath' => $corePath.'model/',
            'calendarPath' => $corePath.'model/'.$package_name.'/calendar/',
            'icalPath' => $corePath.'model/'.$package_name.'/icalcreator/',
            'processorsPath' => $corePath.'processors/',
            'uploadPath' => $assetsPath.'uploads/',
            'templatesPath' => $corePath.'templates/',
            
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',
            'uploadUrl' => $assetsUrl.'uploads/',
            'packageName' => $package_name
        ),$config);
        
    }
    
    /**
     * Initialize the component into a context and provide context-specific
     * handling actions.
     *
     * @access public
     * @param string $context The context to initialize into
     * @return mixed
     */
    public function initialize($context = 'mgr') {
        switch ($context) {
            case 'mgr': 
            case 'web':
            default:
                $this->modx->addPackage($this->config['packageName'],$this->config['modelPath']);
                $language = isset($this->config['language']) ? $this->config['language'] . ':' : '';
                $this->modx->lexicon->load($language.$this->config['packageName'].':default');
                
                $this->_initialized = true;
                break;
        }
        return $this->_initialized;
    }

    /**
     * Sees if class has been initialized already
     * @return boolean
     */
    public function isInitialized() {
        return $this->_initialized;
    }
    /**
     * Load the ControllerRequest class
     * @return ControllerRequest
     */
    public function loadRequest() {
        $className = $this->modx->getOption('request_class',$this->config,'myControllerRequest');
        $classPath = $this->modx->getOption('request_class_path',$this->config,'');
        if (empty($classPath)) $classPath = $this->config['modelPath'].$this->config['packageName'].'/request/';
        if ($this->modx->loadClass($className,$classPath,true,true)) {
            $this->request = new $className($this);//,$this->config);
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'['.$this->config['packageName'].'] Could not load myControllerRequest class.');
        }
        
        return $this->request;

    }
    
    
    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            // use objects first:
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if ($chunk == false) {
                $chunk = $this->_getTplChunk($name);
                if (empty($chunk)) {
                     return false;
                }
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name) {
        $chunk = false;
        $f = $this->config['chunksPath'].strtolower($name).'.chunk.tpl';
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }
}
