<?php

ini_set('default_charset', 'UTF-8');
mb_language('ja');
mb_internal_encoding('UTF-8');
//setlocale(LC_ALL, 'ja_JP.UTF-8');

require_once 'functions.php';

// SetEnv APP_CHANNEL rest
// SetEnv APP_NETWORK irc.freenode.net
// SetEnv APP_LOGDIR /var/log/eggdrop/rest
// SetEnv APP_GACODE UA-13006615-1
$host    = getenv('APP_HOST')    ? getenv('APP_HOST')    : $_SERVER['HTTP_HOST'];
$channel = getenv('APP_CHANNEL') ? getenv('APP_CHANNEL') : "rest";
$network = getenv('APP_NETWORK') ? getenv('APP_NETWORK') : "irc.freenode.net";
//$logdir  = getenv('APP_LOGDIR')  ? getenv('APP_LOGDIR')  : dirname(__FILE__)."/sample-logs";
$logdir  = dirname(__FILE__).'/logs';
$gacoe   = getenv('APP_GACODE');

$title = "#{$channel} on {$network}";
$subtitle = "";

$baseurl = "http://".$host."/";

// Parse URL
$uri = $_SERVER['REQUEST_URI'];
$valid_formats = array("html","txt");
$uri_match_date = preg_match("/^.*\/([\d]{4}(-|\.)[\d]{2}(-|\.)[\d]{2})\.([a-zA-Z0-9_-]+)$/", $uri, $m);
$uri_match_index = preg_match("/.*^\/index\.([a-zA-Z0-9_-]+)$/", $uri);
//$date = $uri_match_date ? $m[1] : date('Y-m-d');
$date = $uri_match_date ? $m[1] : date('Y.m.d');
$format = $uri_match_date && in_array($m[2], $valid_formats) ? $m[2] : "html";
//var_dump($uri, $uri_match_date, $uri_match_index, $format);

// Load log file for given date
//$logprefix = $channel;
$logprefix = '';
$lines = array();
if ($date && preg_match("/^[\d]{4}(-|\.)[\d]{2}(-|\.)[\d]{2}$/", $date)) {
    $filename = date_to_log_filename($logdir, $logprefix, $date);
    //var_dump($filename);
    $lines = file_exists($filename) ? file($filename) : null;
    $subtitle = " ".$date;
}

if ($format == "txt") {
    header("Content-Type: text/plain");
    if (count($lines)) {
        header("HTTP/1.0 200 Okay");
        include 'views/date.txt.php';
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Log file for {$date} not found.";
    }
} elseif ($format == "html") {
    header("Content-Type: text/html");
    if ($uri_match_date && count($lines)) {
        header("HTTP/1.0 200 Okay");
        require 'views/date.html.php';
    } elseif ($uri_match_index) {
        header("HTTP/1.0 200 Okay");
        require 'views/index.html.php';
    } else {
        header("HTTP/1.0 404 Not Found");
        require 'views/index.html.php';
    }
}
