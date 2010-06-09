<?php

require_once('functions.php');

// SetEnv APP_CHANNEL rest
// SetEnv APP_NETWORK irc.freenode.net
// SetEnv APP_LOGDIR /var/log/eggdrop/rest
$host    = getenv('APP_HOST')    ? getenv('APP_HOST')    : $_SERVER['HTTP_HOST'];
$channel = getenv('APP_CHANNEL') ? getenv('APP_CHANNEL') : "rest";
$network = getenv('APP_NETWORK') ? getenv('APP_NETWORK') : "irc.freenode.net";
$logdir  = getenv('APP_LOGDIR')  ? getenv('APP_LOGDIR')  : dirname(__FILE__)."/sample-logs";

$title = "#{$channel} on {$network}";
$subtitle = "";

$baseurl = "http://".$host;
$baserel = "/";

// Parse URL
$uri = $_SERVER['REQUEST_URI'];
$valid_formats = array("html","txt");
$uri_match_date = preg_match("/^\/([\d]{4}-[\d]{2}-[\d]{2})\.([a-zA-Z0-9_-]+)$/", $uri, $m);
$uri_match_index = preg_match("/^\/index\.([a-zA-Z0-9_-]+)$/", $uri);
$date = $uri_match_date ? $m[1] : date('Y-m-d');
$format = $uri_match_date && in_array($m[2], $valid_formats) ? $m[2] : "html";

// Load log file for given date
$logprefix = $channel;
$lines = array();
if($date && preg_match("/^[\d]{4}-[\d]{2}-[\d]{2}$/", $date)) {
    $filename = $logdir.'/'.$logprefix.'.log.'.$date;
    $lines = file_exists($filename) ? file($filename) : null;
    $subtitle = " ".$date;
}

if($format == "txt") {
    header("Content-Type: text/plain");
    if(count($lines)) {
        header("HTTP/1.0 200 Okay");
        include("views/date.txt.php");
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Log file for {$date} not found.";
    }
} else if($format == "html") {
    header("Content-Type: text/html");
    if($uri_match_date && count($lines)) {
        header("HTTP/1.0 200 Okay");
        include("views/date.html.php");
    } else if($uri_match_index) {
        header("HTTP/1.0 200 Okay");
        include("views/index.html.php");
    } else {
        header("HTTP/1.0 404 Not Found");
        include("views/index.html.php");
    }
}
