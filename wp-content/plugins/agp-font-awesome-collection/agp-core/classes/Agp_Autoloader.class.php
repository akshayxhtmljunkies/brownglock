<?php
namespace Agp\FontAwesomeCollection\Core;

class Agp_Autoloader {
    
    private $_included = array ();
    
    /**
     * Class map
     * 
     * @var array 
     */
    private $classMap = array();
    
    /**
     * Classes
     * 
     * @var array
     */
    private $classes;
    
    /**
     * The single instance of the class 
     * 
     * @var object 
     */
    protected static $_instance = null;  
    
    /**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}    
    
	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
    }        
    
    /**
     * Constructor
     */
    public function __construct() {
        spl_autoload_register(array( $this, 'autoload' ));
    }
    
    /**
     * Class autoload
     * 
     * @param string $class
     */
    private function autoload($class) {
        if (!empty($this->classMap)) {
            
            if (array_key_exists('classmaps', $this->classMap)) {
                
                foreach ($this->classMap['classmaps'] as $path => $file) {
                    if (!in_array($path, $this->_included)) {
                        
                        $filename = $path . '/' . $file;
                        
                        if (file_exists($filename) && is_file($filename)) {
                            $fp = fopen($filename, 'r');
                            $data = fread($fp, filesize($filename));
                            fclose($fp);                                        
                            $classes = json_decode($data, TRUE);

                            if (!empty($classes) && is_array($classes)) {
                                foreach ($classes as $class_key => $class_file) {
                                    $this->classes[$class_key] = $path . $class_file;     
                                }
                            }
                        }

                        $this->_included[] = $path;                        
                    }
                }
                
            }

//            print_r('<pre>');
//            print_r($this->classes);
//            print_r('</pre>');                
                
            if (empty($this->classes)) {
                $this->classes = $this->generateClassMap(NULL, FALSE);
            }            
            
            if (!empty($this->classes)) {
                if ( array_key_exists($class, $this->classes) ) {
                    $file = $this->classes[$class];
                    if ( file_exists($file) && is_file($file) ) {
                        require_once $file;
                        return;    
                    }                    
                }
            }
        }
    }
    
    /**
     * Recursive file search
     * 
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    private function rglob($pattern, $flags = 0) {
        $files = glob($pattern, $flags); 
        $glob_files = glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT);
        if ($files === FALSE) {
            $files = array();
        }
        if ($glob_files === FALSE) {
            $glob_files = array();
        }        

        foreach ($glob_files as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }      
    
    public function generateClassMap($baseDir = NULL, $save = TRUE) {
        $result = array();
                    
        if (array_key_exists('namespaces', $this->classMap)) {
            foreach ($this->classMap['namespaces'] as $namespace => $data) {
                foreach ($data as $path => $value) {
                    $maps = array();
                    if (is_array($value)) {
                        $maps = $value;
                    } else {
                        $maps[] = $value;
                    }

                    foreach ($maps as $map) {
                        if (!is_array($map) && ($path == $baseDir || empty($baseDir)) ) {
                            $file = $path . '/' . $map .'/*.class.php';
                            $file = str_replace('//', '/', $file);
                            $files = $this->rglob($file) ;
                            if (!empty($files) && is_array($files)) {
                                foreach ($files as $file) {
                                    $value = str_replace($path, '', $file);
                                    $path_arr = explode('/', $value);
                                    if (is_array($path_arr) && count($path_arr) > 0) {
                                        $class_file = array_pop($path_arr);
                                        $class_name = str_replace('.class.php', '', $class_file);
                                        
                                        if (!$save) {
                                            $value = $path . $value;
                                        }
                                        
                                        $result["$namespace\\$class_name"] = $value;
                                    }
                                }
                            }                                                            
                        }
                    }
                }                                    
            }
        }
        
        if (array_key_exists('paths', $this->classMap)) {
            foreach ($this->classMap['paths'] as $path => $value) {
                $maps = array();
                if (is_array($value)) {
                    $maps = $value;
                } else {
                    $maps[] = $value;
                }
                foreach ($maps as $map) {
                    if (!is_array($map) && ($path == $baseDir || empty($baseDir))) {
                        $file = $path . '/' . $map .'/*.class.php';
                        $file = str_replace('//', '/', $file);
                        $files = $this->rglob($file) ;
                        if (!empty($files) && is_array($files)) {
                            foreach ($files as $file) {
                                $value = str_replace($path, '', $file);
                                $path_arr = explode('/', $value);
                                if (is_array($path_arr) && count($path_arr) > 0) {
                                    $class_file = array_pop($path_arr);
                                    $class_name = str_replace('.class.php', '', $class_file);
                                    if (!$save) {
                                        $value = $path . $value;
                                    }
                                    $result["$class_name"] = $value;
                                }
                            }
                        }                                    
                    }
                }
            }                    
        }
        
        if (!empty($result) && $save) {
            $filename = $baseDir . '/classmap.json';
            if(!file_exists($filename)){
                touch($filename);
            }
            $fp = fopen($filename, 'w+');
            fwrite($fp, json_encode($result));
            fclose($fp);                    
        }
        
        return $result;
    }


    public function getClassMap() {
        return $this->classMap;
    }

    public function setClassMap($classMap) {
        if (is_array($classMap)) {
            $this->classMap = array_merge_recursive($this->classMap, $classMap);
        }
        
        return $this;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function setClasses($classes) {
        $this->classes = $classes;
        return $this;
    }
}

Agp_Autoloader::instance();
