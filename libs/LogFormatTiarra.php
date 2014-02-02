<?php

/**
 * Log Format class for Tiarra Log Files
 */
class LogFormatTiarra extends LogFormat
{
    public function getLogFileNameFromDate($date)
    {
        $date = str_replace('-', '.', $date);
        $filename = $this->logPrefix . $date . '.txt';

        return $filename;
    }

    public function isValidLogFilename($filename)
    {
        if (preg_match('/\d\d\d\d\.\d\d\.\d\d\.txt\z/', $filename)) {
            return true;
        } else {
            return false;
        }
    }

    public function logFileNameToDate($filename)
    {
        $filedate = substr($filename, 0, 10);
        $filedate = str_replace('.', '-', $filedate);

        return $filedate;
    }

    public function lineAsHtml($line, $i, $channel)
    {
        $line = trim($line, "\r\n");
        $line_classes = array();

        $line = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');

        if (preg_match("/^[^\s]+ \+ ([^\s]+) /", $line, $m)) {
            $line_classes[] = 'join';
            $line_classes[] = 'user-' . $m[1];
        } elseif (preg_match("/^[^\s]+ (\-|!) ([^\s]+) /", $line, $m)) {
            $line_classes[] = 'left';
            $line_classes[] = 'user-' . $m[2];
        } else {
            $line = $this->linkifyText($line);
            if (preg_match("/^([^\s]+) &lt;([^\s]+)&gt; (.*)$/", $line, $m)) {
                $line_classes[] = 'user-' . $m[2];
                $line = "{$m[1]} <span class='name'>&lt;{$m[2]}&gt;</span> {$m[3]}";
            }
        }

        $line = preg_replace("/^([\d]{2}):([\d]{2}):([\d]{2})(.*)/", "<span class='ts'>[\\1:\\2]</span> <span class='t'>\\4</span>", $line);

        $classes = implode(' ', $line_classes);
        $classes = $classes ? ' class="' . $classes . '"' : '';

        $html = "<li id='{$i}'{$classes}>{$line}</li>\n";

        return $html;
    }
}
