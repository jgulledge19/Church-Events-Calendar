<?php
/**
 * Represents a chunk of static HTML content.
 *
 * @package modx
 */
//class modChunk extends modElement {
class customModChunk extends modScript {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->setToken('$');
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'chunk' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'chunk' => &$this,
                'cacheFlag' => $cacheFlag,
            ));

        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('chunk_err_create') : $this->xpdo->lexicon('chunk_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }

        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkBeforeRemove',array(
                'chunk' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkRemove',array(
                'chunk' => &$this,
                'ancestors' => $ancestors,
            ));

        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('chunk_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Overrides modElement::process to initialize the Chunk into the element cache,
     * as well as set placeholders and filter the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        // the org modElement process()
        $this->xpdo->getParser();
        $this->getProperties($properties);
        $this->getTag();
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties, true));
        if ($this->isCacheable() && isset ($this->xpdo->elementCache[$this->_tag])) {
            $this->_output = $this->xpdo->elementCache[$this->_tag];
            $this->_processed = true;
        } else {
            $this->filterInput();
            $this->getContent(is_string($content) ? array('content' => $content) : array());
        }
        // replacing this:
        // parent :: process($properties, $content);
        
        // modScript :: process() but altered
        if (!$this->_processed) {
            $scriptName= $this->getScriptName();
            $this->_result= function_exists($scriptName);
            if (!$this->_result) {
                $this->_result= $this->loadScript();
            }
            if ($this->_result) {
                $this->xpdo->event->params= $this->_properties; /* store params inside event object */
                ob_start();
                $this->_output= $scriptName($this->_properties);
                $this->_output= ob_get_contents() . $this->_output;
                ob_end_clean();
                if ($this->_output && is_string($this->_output)) {
                    
                    // turn the processed properties into placeholders 
                    $scope = $this->xpdo->toPlaceholders($this->_properties, '', '.', true);
                    /* collect element tags in the evaluated content and process them */
                    $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                    $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
                    
                    //* remove the placeholders set from the properties of this element and restore global values 
                    if (isset($scope['keys'])) $this->xpdo->unsetPlaceholders($scope['keys']);
                    if (isset($scope['restore'])) $this->xpdo->toPlaceholders($scope['restore']);
                }
                $this->filterOutput();
                unset ($this->xpdo->event->params);
                $this->cache();
            }
        }
        $this->_processed= true;
        
        
        
        /*
        if (!$this->_processed) {
            // copy the content into the output buffer 
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                // turn the processed properties into placeholders 
                $scope = $this->xpdo->toPlaceholders($this->_properties, '', '.', true);

                //* collect element tags in the output and process them 
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);

                //* remove the placeholders set from the properties of this element and restore global values 
                if (isset($scope['keys'])) $this->xpdo->unsetPlaceholders($scope['keys']);
                if (isset($scope['restore'])) $this->xpdo->toPlaceholders($scope['restore']);
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed= true;
        }*/

        //* finally, return the processed element content 
        return $this->_output;
    }
    /**
     * Overrides modScript::loadScript 
     * Loads and evaluates the script, returning the result.
     *
     * @return boolean True if the result of the script is not false.
     */
    public function loadScript() {
        $includeFilename = $this->xpdo->getCachePath() . 'includes/' . $this->getScriptCacheKey() . '.include.cache.php';
        $result = file_exists($includeFilename);
        if (!$result) {
            // set properties
            $tmp = $this->getContent();//need to make the string safe
            $tmp = 'return \''.$tmp."';";
            foreach( $this->_properties as $key=>$value ) {
                $tmp = str_replace(array('[[+'.$key.']]', '[[!+'.$key.']]'), "'".'.$'.$key.".'", $tmp);
            }
            $this->setContent($tmp);
            $this->_content = $tmp;
            echo 'C: '. $tmp;
            $script= $this->xpdo->cacheManager->get($this->getScriptCacheKey(), array(
                xPDO::OPT_CACHE_KEY => $this->xpdo->getOption('cache_scripts_key', null, 'scripts'),
                xPDO::OPT_CACHE_HANDLER => $this->xpdo->getOption('cache_scripts_handler', null, $this->xpdo->getOption(xPDO::OPT_CACHE_HANDLER)),
                xPDO::OPT_CACHE_FORMAT => (integer) $this->xpdo->getOption('cache_scripts_format', null, $this->xpdo->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
            ));
            if (!$script) {
                $script= $this->xpdo->cacheManager->generateScript($this);
            }
            if (!empty($script)) {
                $result = $this->xpdo->cacheManager->writeFile($includeFilename, "<?php\n" . $script);
            }
        }
        if ($result) {
            $result = include($includeFilename);
            if ($result) {
                $result = function_exists($this->getScriptName());
            }
        }
        return ($result !== false);
    }

    /**
     * Get the source content of this chunk.
     *
     * @access public
     * @param array $options
     * @return string The source content.
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('snippet');
            }
        }
        return $this->_content;
    }

    /**
     * Set the source content of this chunk.
     *
     * @access public
     * @param string $content
     * @param array $options
     * @return string True if successfully set
     */
    public function setContent($content, array $options = array()) {
        return $this->set('snippet', $content);
    }
}