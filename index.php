<?php

require_once('functions.php');

$pagetitle = "#redis on irc.freenode.net";

if(getenv('APP_ENV') == 'local') {
    $logdir = dirname(__FILE__)."/sample-logs";
    $baseurl = "http://redis.local";
    $logprefix = "redis";
    $channel_name = "#redis";
} else {
    $logdir = "/home/kevburns/eggdrop/logs/redis";
    $baseurl = "http://redis.hackyhack.net";
    $logprefix = "redis";
    $channel_name = "#redis";
}
$lines = array();
if(isset($_GET['date']) && preg_match("/^[\d]{4}-[\d]{2}-[\d]{2}$/", $_GET['date'])) {
    $logdate = $_REQUEST['date'];
    $filename = $logdir.'/'.$logprefix.'.log.'.$logdate;
    $lines = file_exists($filename) ? file($filename) : null;
    $title = "#redis";
    $subtitle = " : ".$logdate;
} else {
    $title = "Chat logs for #redis on irc.freenode.net";
    $subtitle = "";
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title><?=$title.$subtitle?></title>
        
        <meta name="robots" content="index, follow" />
        <meta name='title' content="<?=$title.$subtitle?>"/>
        <meta name='description' content="Redis 2.0.0 RC1 is near, ETA: end of May."/>
        <meta name='keywords' content="redis, key-value, irc, chat, logs, database"/>

        <link rel="stylesheet" type="text/css" href="/css/default.css" media="screen" />
        <script type="text/javascript" src="/js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="/js/jquery.plugins.js"></script>
        <script type="text/javascript" src="/js/global.js"></script>
    </head>
    <body><div class="wrapper">
<?
	if(isset($_REQUEST['date']) && count($lines)) { 
		$files = array_slice(scandir($logdir),2);
		foreach($files as $i => $file) {
            if($file == $logprefix.'.log.'.$logdate) {
                $prev = $i > 0 ? substr($files[$i-1], strlen($logprefix)+5) : '';
                $next = $i < count($files)-1 ? substr($files[$i+1], strlen($logprefix)+5) : '';
                break;
            }
        }
    ?>
        <div class="hdr">
            <h1><?=$pagetitle?> <span class="date"><?=$logdate?></span></h1>
            <ul class="nav">
                <li class="index"><a href='/'>index</a></li>
            <? if($prev) { ?>
                <li class="prev"><a href='<?=$baseurl?>/<?=$prev?>.html'>prev</a></li>
            <? } else { ?>
                <li class="prev"><span>prev</span></li>
            <? } ?>
            <? if($next) { ?>
                <li class="next"><a href='<?=$baseurl?>/<?=$next?>.html'>next</a></li>
            <? } else { ?>
                <li class="next"><span>next</span></li>
            <? } ?>
            </ul>
        </div>
        <ul class="lines">
        <? 
        $times = array();
        $i = 0;
        foreach ($lines as $line_num => $line) { 
            $line = trim($line,"\r\n");
            $line_classes = array();
            
            $line = htmlspecialchars($line);
            
            if(preg_match("/^Action: /",$line)) {
                $line = preg_replace("/^Action: /","",$line);
                $line_classes[] = 'action';
            }
            if(preg_match("/^Nick change: /",$line)) {
                $line_classes[] = 'nickchange';
            } else if(preg_match("/ joined $channel_name\.$/",$line)) {
                $line_classes[] = 'join';
            } else if(preg_match("/ mode change /",$line)) {
                $line_classes[] = 'mode';
            } else if(preg_match("/ left $channel_name\.$/",$line)) {
                $line_classes[] = 'left';
            } else if(preg_match("/ left irc: /",$line)) {
                $line_classes[] = 'left';
            } else if(preg_match("/left irc: Quit: /",$line)) {
                $line_classes[] = 'left';
                $line_classes[] = 'quit';
            } else if(preg_match("/left irc: Read error: /",$line)) {
                $line_classes[] = 'left';
                $line_classes[] = 'error';
            } else {
                $line = LinkifyText($line);
            }
            
            $line = preg_replace("/^\[([\d]{2}):([\d]{2})\](.*)/", "<span class='ts'>[\\1:\\2]</span><span class='t'>\\3</span>", $line);
            
            $classes = implode(' ', $line_classes);
            $classes = $classes ? ' class="'.$classes.'"' : '';
            $line_item = "";
        ?>
            <li id='<?=$i?>'<?=$classes?>><?=$line?></li>
        <? 
            $i++;
        } 
        ?>
        </ul>
        <ul class="nav" id="urlnav">
            <li class="top"><a href='#0' title="Top">Top</a></li>
            <li class="bottom"><a href='#<?=$i-1?>' title="Bottom">Bottom</a></li>
            <li class="clear"><a href='#none' title="Clear Selection">Clear Selection</a></li>
            <li class="permalink"><a href='#' title="Permalink">Permalink</a></li>
            <li class="webchat"><a href="http://webchat.freenode.net/?randomnick=1&channels=redis">Freenode WebChat</a>
            <li class="github"><a href="http://github.com/KevBurnsJr/rest-irc-log-viewer">Fork me on GitHub</a>
        </ul>
<?
	} else {
?>
        <div style="padding: 12px 20px 18px;">
            <h1><?=$pagetitle?></h1>
        </div>
        <div style="padding: 0 40px;">
<?
		$files = scandir($logdir);
		foreach($files as $file) {
			if(strpos($file, $logprefix.'.log') > -1) {
				$filedate = substr($file, strlen($logprefix)+5);
				echo "<a href='".$baseurl."/".$filedate.".html'>".$filedate."</a><br />";
			}
		}
?>
        </div>
<?
	}
?>
    </div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15088015-3");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>