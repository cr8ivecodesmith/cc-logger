<?php
/**
 * CCLogger Class
 * A simple logging class for PHP.
 * 
 * @author         Matt Lebrun
 * @email          cr8ivecodesmith@gmail.com
 * @version        0.1
 * @date           June 25, 2012
 * @modified       June 25, 2012
 * 
 * USAGE:
 * include_once '../path/CCLogger.php';
 * $logger = new CCLogger($logPath);
 * $logger->log('DUMP', $foo);
 * 
 * CHANGELOG:
 * 0.1 - Initial version.
 * 
 * TODO(cr8ivecodesmith@gmail.com):
 * - Document properly.
 * - Better handling for invalid path/file.
 * - Better identifiers.
 * - Refactor and add more features.
 */
class CCLogger {

private $_logFile;
private $_logPath;
private $_fileHandle;

public function __construct($logPath, $logFile)
{
    $tmpFile = '';
    
    if ( ! isset($logPath))
    {
        print "Error: No file path specified.\n";
        exit;
    }
    elseif ( ! is_dir($logPath))
    {
        print "Error: Specified file path is not a valid directory.\n";
        exit;
    }
    elseif ( ! is_writable($logPath))
    {
        print "Error: Specified file path is not a writable.\n";
        exit;
    }
    else
    {
        $this->setLogPath($logPath);
    }
    
    if ( ! isset($logFile))
    {
        $tmpFile = 
            $this->getLogPath() .  
            'log_' . 
            $this->getLogTime(true) . 
            '.txt';
    }
    else
    {
        $tmpFile = 
            $this->getLogPath() . 
            $logFile . 
            '_' . 
            $this->getLogTime(true) . 
            '.txt';
    }
    
    $this->setLogFile($tmpFile);
    $this->setFileHandle(fopen($this->getLogFile(), 'a'));
}

public function __destruct()
{
    fclose($this->getFileHandle());
}

public function getLogTime($isFileTime = false)
{
    $date = new DateTime();
    
    if ($isFileTime)
    {
        return $date->format('Ymd');
    }
    else
    {
        return $date->format('Y-m-d H:i:s');
    }
}

public function log($identifier, $msg)
{
    $logMsg = '';
    
    if(is_array($msg))
    {
        $logMsg =  "[" . $this->getLogTime() . "] " . $identifier . "\n";
        $this->writeMessage($logMsg);        
        $this->writeMessage(print_r($msg, true));
        $this->writeMessage("\n");
    }
    else
    {
        $logMsg = 
            "[" . 
            $this->getLogTime() . 
            "] " . 
            strtoupper($identifier) . 
            " - " . 
            $msg .
            "\n";
            
        $this->writeMessage($logMsg);
    }    
}

private function writeMessage($msg)
{
    fwrite($this->getFileHandle(), $msg);
}

private function setLogFile($file) { $this->_logFile = $file; }
public function getLogFile() { return $this->_logFile; }

private function setLogPath($path) { $this->_logPath = $path; }
public function getLogPath() { return $this->_logPath; }

private function setFileHandle($fh) { $this->_fileHandle = $fh; }
private function getFileHandle() { return $this->_fileHandle; }

}

?>