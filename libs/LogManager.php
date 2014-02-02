<?php

class LogManager
{
    /**
     * @var string
     */
    protected $logDir;

    /**
     * @var LogFormat
     */
    protected $logFormat;

    /**
     * @var array
     */
    protected $logFileList = array();

    /**
     * @var int
     */
    protected $maxFileSize;

    public function __construct($logDir, $logFormat)
    {
        $this->logDir = $logDir;
        $this->logFormat = $logFormat;

        $this->createLogFileList();
    }

    protected function createLogFileList()
    {
        $maxsize = 0;
        $files = array();
        $allFiles = array_reverse(array_slice(scandir($this->logDir), 2));
        foreach ($allFiles as $file) {
            if ($this->logFormat->isValidLogFilename($file)) {
                $log = new LogFile($this->logDir, $file);
                $maxsize = max($maxsize, $log->getFileSize());
                $files[] = $log;
            }
        }
        $this->logFileList = $files;
        $this->maxFileSize = $maxsize;
    }

    /**
     * 
     * @param string $date Date (YYYY-MM-DD)
     * @return string Log filename full path
     */
    public function getLogFileNameFromDate($date)
    {
        return $this->logDir . '/' . $this->logFormat->getLogFileNameFromDate($date);
    }

    /**
     * 
     * @param string $filename
     * @return string Date (YYYY-MM-DD)
     */
    public function getDateFromLogFileName($filename)
    {
        $date = $this->logFormat->logFileNameToDate($filename);

        return $date;
    }

    public function getLogFileList()
    {
        return $this->logFileList;
    }

    public function getMaxSize()
    {
        return $this->maxFileSize;
    }

    public function getPrevNextDate($date)
    {
        $files = $this->getLogFileList();
        foreach ($files as $i => $file) {
            $filename = $file->getFileName();
            if ($this->getDateFromLogFileName($filename) == $date) {
                $prev = $i < count($files)-1 ? $this->getDateFromLogFileName($files[$i+1]->getFileName()) : '';
                $next = $i > 0 ? $this->getDateFromLogFileName($files[$i-1]->getFileName()) : '';
                break;
            }
        }

        return array($prev, $next);
    }

    public function lineAsHtml($line, $i, $channel)
    {
        return $this->logFormat->lineAsHtml($line, $i, $channel);
    }

    /**
     * 
     * @param string $date Date (YYYY-MM-DD)
     * @return array Lines of a log file
     */
    public function getLogFileLinesFromDate($date)
    {
        $filename = $this->getLogFileNameFromDate($date);
        $lines = file_exists($filename) ? file($filename) : null;
        
        return $lines;
    }
}
