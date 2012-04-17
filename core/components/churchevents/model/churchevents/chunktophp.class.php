<?php

class chunkToPhp {
    
    /**
     * instance of modx
     */
    public $modx = NULL;
    /**
     * 
     */
    public $xpdo = NULL;
    /**
     * @var boolean clearCache
     * @access protected
     */
    protected $clearCache = true;
    /**
     * 
     */
    public function __construct($modx, $clearCache=false) {
        $this->modx = &$modx;
        // $this->xpdo = $this->modx->xpdo; - not working in 2.2.1
        $this->clearCache = $clearCache;
    }
    /**
     * 1. does Chunk exists as PHP funtion?
     * 2. if not get Chunk and make it a PHP funtion
     * 
     */
    
    /**
     * 
     */
    public function getPhpChunk($chunk, $properties, $content) {
        /*echo $content.' ------------------
        ';*/
        $scriptName= $this->getScriptName($chunk);
        $result = function_exists($scriptName);
        if (!$result) {
            $result= $this->loadScript($chunk, $properties, $content);
        }
        $output = $scriptName($properties);
        /*echo ' ------------------
        '.$output;
        exit();*/
        return $output;
    }
    /**
     * Get the name of the script source file, written to the cache file system
     *
     * @return string The filename containing the function generated from the
     * script element.
     */
    public function getScriptCacheKey($chunk) {
        return str_replace('_', '/', $this->getScriptName($chunk));
    }

    /**
     * Get the name of the function the script has been given.
     *
     * @return string The function name representing this script element.
     */
    public function getScriptName($chunk) {
        return 'elements_modchunk_' . str_replace(' ', '',strtolower($chunk));
    }
    /**
     * Base code Taken from core/model/modx/modscript.class.php 
     * Loads and evaluates the script, returning the result.
     *
     * @return boolean True if the result of the script is not false.
     */
    public function loadScript($chunk, $properties, $scriptContent) {
        $cacheKey = $this->getScriptCacheKey($chunk);
        $includeFilename = $this->modx->getCachePath() . 'includes/' . $cacheKey . '.include.cache.php';
        $result = file_exists($includeFilename);
        if (!$result || $this->clearCache ) {
            
            $script= $this->modx->cacheManager->get($cacheKey, array(
                xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_scripts_key', null, 'scripts'),
                xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_scripts_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
                xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption('cache_scripts_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
            ));
            if (!$script) {
                $script = $this->generateScript($chunk, $properties, $scriptContent);
            }
            if (!empty($script)) {
                $result = $this->modx->cacheManager->writeFile($includeFilename, "<?php\n" . $script);
            }
        }
        if ($result) {
            $result = include($includeFilename);
            if ($result) {
                $result = function_exists($this->getScriptName($chunk));
            }
        }
        return ($result !== false);
    }
    /**
     * Base code Taken from core/model/modx/modcachemanager.class.php
     * 
     * Generates a file representing an executable modScript function.
     *
     * @param modScript $objElement A {@link modScript} instance to generate the
     * script file for.
     * @param string $objContent Optional script content to override the
     * persistent instance.
     * @param array $options An array of additional options for the operation.
     * @return boolean|string The actual generated source content representing the modScript or
     * false if the source content could not be generated.
     */
    public function generateScript($chunk, $properties, $scriptContent, array $options= array()) {
        $results= false;
        if ( 1 == 1) {
            $scriptName= $this->getScriptName($chunk);
            // set properties
            $tmp = 'return \''.str_replace("'", "\'", $scriptContent).'\';';
            foreach( $properties as $key=>$value ) {
                $tmp = str_replace(array('[[+'.$key.']]', '[[!+'.$key.']]'), "'".'.$'.$key.".'", $tmp);
            }
            $scriptContent = $tmp;
            //echo 'C: '. $tmp;
            
            $content = "function {$scriptName}(\$scriptProperties= array()) {\n";
            $content .= "global \$modx;\n";
            $content .= "if (is_array(\$scriptProperties)) {\n";
            $content .= "extract(\$scriptProperties, EXTR_SKIP);\n";
            $content .= "}\n";
            $content .= $scriptContent . "\n";
            $content .= "}\n";
            /*if ($this->modx->getOption('returnFunction', $options, false)) {
                return $content;
            }*/
            $results = $content;
            /*
            if ($this->modx->getOption('cache_scripts', $options, true)) {
                $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_scripts_key', $options, 'scripts');
                $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_scripts_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
                $options[xPDO::OPT_CACHE_FORMAT] = (integer) $this->getOption('cache_scripts_format', $options, $this->getOption(xPDO::OPT_CACHE_FORMAT, $options, xPDOCacheManager::CACHE_PHP));
                $options[xPDO::OPT_CACHE_ATTEMPTS] = (integer) $this->getOption('cache_scripts_attempts', $options, $this->getOption(xPDO::OPT_CACHE_ATTEMPTS, $options, 1));
                $options[xPDO::OPT_CACHE_ATTEMPT_DELAY] = (integer) $this->getOption('cache_scripts_attempt_delay', $options, $this->getOption(xPDO::OPT_CACHE_ATTEMPT_DELAY, $options, 1000));
                $lifetime = (integer) $this->modx->getOption('cache_scripts_expires', $options, $this->getOption(xPDO::OPT_CACHE_EXPIRES, $options, 0));
                if (empty($results) || !$this->set($objElement->getScriptCacheKey(), $results, $lifetime, $options)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Error caching script " . $objElement->getScriptCacheKey());
                }
            }
            */
        }
        return $results;
    }
}
