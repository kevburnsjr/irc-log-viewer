<?php

class LogFile
{
    protected $logDir;
    protected $fileName;

    public function __construct($logDir, $fileName)
    {
        $this->logDir = $logDir;
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getFileSize()
    {
        return filesize($this->logDir.'/'.$this->fileName);
    }
}
