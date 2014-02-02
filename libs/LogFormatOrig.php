<?php

/**
 * Log Format class for Original Sample Log Files
 */
class LogFormatOrig extends LogFormat
{
    public function getLogFileNameFromDate($date)
    {
        $filename = $this->logPrefix . '.log.' . $date;

        return $filename;
    }

    public function isValidLogFilename($filename)
    {
        if (strpos($filename, $this->logPrefix.'.log') > -1) {
            return true;
        } else {
            return false;
        }
    }

    public function logFileNameToDate($filename)
    {
        $filedate = substr($filename, strlen($this->logPrefix)+5);

        return $filedate;
    }

    public function lineAsHtml($line, $i, $channel)
    {
        $line = trim($line, "\r\n");
        $line_classes = array();

        $line = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');

        if (preg_match("/^[^\s]+ Action: ([^\s]+)/", $line, $m)) {
            $line = substr($line, 0, 8) . substr($line, 16);
            $line_classes[] = 'action';
            $line_classes[] = 'user-' . $m[1];
        }
        if (preg_match("/^[^\s]+ Nick change: ([^\s]+) -&gt; ([^\s]+)$/", $line, $m)) {
            $line = substr($line, 0, 8) . substr($line, 21);
            $line_classes[] = 'nickchange';
            $line_classes[] = 'user-' . $m[2];
        } elseif (preg_match("/ ([^\s]+) ([^\s]+) joined #{$channel}\.$/", $line, $m)) {
            $line_classes[] = 'join';
            $line_classes[] = 'user-' . $m[1];
        } elseif (preg_match("/ mode change /", $line)) {
            $line_classes[] = 'mode';
        } elseif (preg_match("/ ([^\s]+) ([^\s]+) left #{$channel}\.$/", $line, $m)) {
            $line_classes[] = 'left';
            $line_classes[] = 'user-' . $m[1];
        } elseif (preg_match("/ ([^\s]+) ([^\s]+) left irc: /", $line, $m)) {
            $line_classes[] = 'left';
            $line_classes[] = 'user-' . $m[1];
        } else {
            $line = $this->linkifyText($line);
            if (preg_match("/^([^\s]+) &lt;([^\s]+)&gt; (.*)$/", $line, $m)) {
                $line_classes[] = 'user-' . $m[2];
                $line = "{$m[1]} <span class='name'>&lt;{$m[2]}&gt;</span> {$m[3]}";
            }
        }

        $line = preg_replace("/^\[([\d]{2}):([\d]{2})\](.*)/", "<span class='ts'>[\\1:\\2]</span><span class='t'>\\3</span>", $line);

        $classes = implode(' ', $line_classes);
        $classes = $classes ? ' class="' . $classes . '"' : '';

        $html = "<li id='{$i}'{$classes}>{$line}</li>";

        return $html;
    }
}
