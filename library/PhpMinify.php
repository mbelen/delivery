<?php
/**
 * PHP Minify class
 *
 * $Id: PhpMinify.php 568 2011-11-13 18:58:31Z indy $
 *
 * $LastChangedBy: indy $
 *
 * $LastChangedDate: 2011-11-13 20:58:31 +0200 (Sun, 13 Nov 2011) $
 *
 * $Rev: 568 $
 *
 * @author Indiana Jones <indy2kro@yahoo.com>
 * @package PhpMinify
 * @version 1.0
 * @copyright 2011 PhpMinify
 */

/**
 * PHP Minify class
 */
class PhpMinify
{
    /**
     * Options
     */
    const OPT_INPUT_PATH = 'input_path';
    const OPT_OUTPUT_PATH = 'output_path';
    const OPT_INPUT_PATTERNS = 'input_patterns';
    const OPT_SKIP_PATTERNS = 'skip_patterns';
    const OPT_TRACK_MINIFIED = 'track_minified';
    const OPT_STRIP_TABULATION = 'strip_tabulation';
    
    /**
     * Options
     * 
     * @var array
     */
    protected $_options = array(
        self::OPT_INPUT_PATH => 'input/',
        self::OPT_OUTPUT_PATH => 'output/',
        self::OPT_INPUT_PATTERNS => array('/\.php$/'),
        self::OPT_SKIP_PATTERNS => array('/^\./'),
        self::OPT_TRACK_MINIFIED => true,
        self::OPT_STRIP_TABULATION => false
    );
    
    /**
     * Minified files
     * 
     * @var array 
     */
    protected $_minifiedFiles = array();
    
    /**
     * Constructor
     * 
     * @param array $options Options to configure
     */
    public function __construct(Array $options)
    {
        // set options
        $this->setOptions($options);
    }
    
    /**
     * Run minifier
     */
    public function run()
    {
        // check if input path exists
        $this->_checkInputPath();
        
        // check if output path exists and is writable
        $this->_checkOutputPath();
        
        // minify path
        $this->_minifyPath($this->_options[self::OPT_INPUT_PATH]);
    }
    
    /**
     * Set options
     * 
     * @param array $options Options to configure
     */
    public function setOptions(Array $options)
    {
        // add slash at the end of the the input path
        if (isset($options[self::OPT_INPUT_PATH])) {
            $options[self::OPT_INPUT_PATH] = rtrim($options[self::OPT_INPUT_PATH], '/') . '/';
        }
        
        // add slash at the end of the the output path
        if (isset($options[self::OPT_OUTPUT_PATH])) {
            $options[self::OPT_OUTPUT_PATH] = rtrim($options[self::OPT_OUTPUT_PATH], '/') . '/';
        }
        
        // merge options with incoming parameters
        $this->_options = array_merge($this->_options, $options);
    }
    
    /**
     * Get minified files
     * 
     * @return array
     */
    public function getMinifiedFiles()
    {
        return $this->_minifiedFiles;
    }
    
    /**
     * Minify given path
     *
     * @param string $path Current path to minify
     * @throws PhpMinify_Exception
     * @return void
     */
    protected function _minifyPath($path)
    {
        // path is not a directory, nothing to do
        if (!is_dir($path)) {
            return null;
        }
        
        $path = rtrim($path, '/') . '/';
        
        $handle = opendir($path);
        
        if (!$handle) {
            throw new PhpMinify_Exception('Failed to open path: ' . $path);
        }
        
        while (false !== ($file = readdir($handle))) {
            // skip current and previous dirs
            if ('.' == $file || '..' == $file) {
                continue;
            }
            
            // check if file needs to be ignored
            foreach ($this->_options[self::OPT_SKIP_PATTERNS] as $pattern) {
                if (preg_match($pattern, $file)) {
                    // skip file/directory
                    continue(2);
                }
            }
            
            $filePath = $path . $file;
            
            if (is_dir($filePath)) {
                $filePath .= '/';
                
                // run recursively
                $this->_minifyPath($filePath);
            } else {
                // minify file
                $this->_runMinify($filePath);
            }
        }
        
        // close directory handle
        closedir($handle);
    }
    
    /**
     * Run minify on an input file
     * 
     * @param string $filePath File path to minify
     * @return void
     */
    protected function _runMinify($filePath)
    {   
        $matched = false;
        
        // check if file is in input patterns
        foreach ($this->_options[self::OPT_INPUT_PATTERNS] as $pattern) {
            if (preg_match($pattern, $filePath)) {
                $matched = true;
            }
        }
        
        // no pattern matched, nothing to do
        if (!$matched) {
            return null;
        }
        
        // keep track of minified files if flag is set
        if ($this->_options[self::OPT_TRACK_MINIFIED]) {
            $this->_minifiedFiles[] = $filePath;
        }
        
        // get file content
        $fileContent = file_get_contents($filePath);
        
        // strip comments
        $fileContent = $this->_stripComments($fileContent);
        
        // strip empty lines
        $fileContent = $this->_stripEmptyLines($fileContent);
        
        // replace new lines
        $fileContent = $this->_replaceNewLines($fileContent);
        
        // strip tabulation
        $fileContent = $this->_stripTabulation($fileContent);
        
        // replace brackets
        $fileContent = $this->_replaceBrackets($fileContent);
        
        // write output file
        $this->_writeOutputFile($filePath, $fileContent);
    }
    
    /**
     * Write output file
     * 
     * @param string $filePath    Output file path (absolute)
     * @param string $fileContent File content
     * @throws PhpMinify_Exception
     * @return void
     */
    protected function _writeOutputFile($filePath, $fileContent)
    {
        $relativeFilePath = str_replace($this->_options[self::OPT_INPUT_PATH], '', $filePath);
        
        // get the parent directory path
        $parentDir = dirname($relativeFilePath);
        
        // compute ourput directory path
        $dirPath = $this->_options[self::OPT_OUTPUT_PATH] . $parentDir . '/';
        
        // create directory
        $this->_createDirectory($dirPath);
        
        // compute output file path
        $filePath = $dirPath . basename($relativeFilePath);
        
        // write output file
        $writeResult = file_put_contents($filePath, $fileContent);
        
        // check write result
        if (false === $writeResult) {
            throw new PhpMinify_Exception('Failed to write output file: ' . $filePath);
        }
    }
    
    /**
     * Replace new lines
     * 
     * @param string $fileContent File content
     * @return string
     */
    protected function _replaceNewLines($fileContent)
    {
        // replace windows end line
        $fileContent = str_replace("\r\n", "\n", $fileContent);
        // replace mac end line
        $fileContent = str_replace("\r", "\n", $fileContent);
        
        return $fileContent;
    }
    
    /**
     * Replace brackets
     * 
     * @param string $fileContent File content
     * @return string
     */
    protected function _replaceBrackets($fileContent)
    {
        // replace brackets that are on a single line
        $fileContent = str_replace("\n{\n", " { ", $fileContent);
        $fileContent = str_replace("\n}\n", " } ", $fileContent);
        
        return $fileContent;
    }
    
    /**
     * Create directory
     * 
     * @param string $dirPath Directory path
     * @throws PhpMinify_Exception
     * @return void
     */
    protected function _createDirectory($dirPath)
    {
        // directory exists, return
        if (is_dir($dirPath)) {
            return null;
        }
        
        // create parent directory
        $this->_createDirectory(dirname($dirPath));
        
        // create directory
        $mkdirResult = mkdir($dirPath);
        
        // check mkdir result
        if (!$mkdirResult) {
            throw new PhpMinify_Exception('Failed to create directory: ' . $dirPath);
        }
    }
    
    /**
     * Strip comments
     * 
     * @param string $fileContent File content
     * @return string
     */
    protected function _stripComments($fileContent)
    {
        $commentTokens = array(T_COMMENT);

        if (defined('T_DOC_COMMENT')) {
            $commentTokens[] = T_DOC_COMMENT; // PHP 5
        }
        
        if (defined('T_ML_COMMENT')) {
            $commentTokens[] = T_ML_COMMENT;  // PHP 4
        }

        $tokens = token_get_all($fileContent);
        
        $newContent = '';

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens)) {
                    continue;
                }

                $token = $token[1];
            }

            $newContent .= $token;
        }

        return $newContent;
    }
    
    /**
     * Strip empty lines
     * 
     * @param string $fileContent File content
     * @return string
     */
    protected function _stripEmptyLines($fileContent)
    {
        $fileContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $fileContent);
        
        return $fileContent;
    }
    
    /**
     * Strip tabulation
     * 
     * @param string $fileContent File content
     * @return string
     */
    protected function _stripTabulation($fileContent)
    {
        // check if enabled in options
        if (!$this->_options[self::OPT_STRIP_TABULATION]) {
            return $fileContent;
        }
        
        $newFileContent = '';
        
        $lines = explode("\n", $fileContent);

        foreach ($lines as $line) {
            $line = ltrim($line, " \t");
            $newFileContent .= $line . "\n";
        }

        return $newFileContent;
    }
    
    /**
     * Check input path
     * 
     * @throws PhpMinify_Exception
     */
    protected function _checkInputPath()
    {
        if (!is_dir($this->_options[self::OPT_INPUT_PATH])) {
            throw new PhpMinify_Exception('Input path does not exist.');
        }
    }
    
    /**
     * Check output path
     * 
     * @throws PhpMinify_Exception
     */
    protected function _checkOutputPath()
    {
        if (!is_dir($this->_options[self::OPT_OUTPUT_PATH])) {
            throw new PhpMinify_Exception('Output path does not exist.');
        }
        
        if (!is_writable($this->_options[self::OPT_OUTPUT_PATH])) {
            throw new PhpMinify_Exception('Output path is not writable.');
        }
    }
}

class PhpMinify_Exception extends Exception {}

/* EOF */