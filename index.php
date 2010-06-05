<?php

require_once('functions.php');

$channel = getenv('APP_CHANNEL') ? getenv('APP_CHANNEL') : "rest";
$network = getenv('APP_NETWORK') ? getenv('APP_NETWORK') : "irc.freenode.net";

/* /home/kevburns/eggdrop/logs/rest/rest.log.2010-02-12 */
$eggdrop_log_dir = "/home/kevburns/eggdrop/logs/".$channel;
$logprefix = $channel;

$title = "#{$channel} on Freenode";
$subtitle = "";

$host = getenv('APP_HOST') ? getenv('APP_HOST') : $_SERVER['HTTP_HOST'];
$baseurl = "http://".$host;

$date = isset($_REQUEST['date']) && $_REQUEST['date'] ? $_REQUEST['date'] : date('Y-m-d');

if(getenv('APP_ENV') == 'local') {
    $logdir = dirname(__FILE__)."/sample-logs";
} else {
    $logdir = $eggdrop_log_dir;
}

$lines = array();
if($date && preg_match("/^[\d]{4}-[\d]{2}-[\d]{2}$/", $date)) {
    $filename = $logdir.'/'.$logprefix.'.log.'.$date;
    $lines = file_exists($filename) ? file($filename) : null;
    $subtitle = " ".$date;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title><?=$title.$subtitle?></title>
        <link rel="stylesheet" type="text/css" href="/css/default.css" media="screen" />
        <script type="text/javascript" src="/js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="/js/jquery.plugins.js"></script>
        <script type="text/javascript" src="/js/global.js"></script>
    </head>
    <body><div class="wrapper">
<?
	if(preg_match('/^[\d]{4}-[\d]{2}-[\d]{2}$/',$date) && count($lines)) {
		$files = array_slice(scandir($logdir),2);
		foreach($files as $i => $file) {
            if($file == $logprefix.'.log.'.$date) {
                $prev = $i > 0 ? substr($files[$i-1], strlen($logprefix)+5) : '';
                $next = $i < count($files)-1 ? substr($files[$i+1], strlen($logprefix)+5) : '';
                break;
            }
        }
    ?>
        <div class="hdr">
            <h1>
                <?=$title?> 
                <span class="date"><?=$date?></span>
                <span class="txt">(<a href="/<?=$date?>.txt">txt</a>)</span>
            </h1>
            <ul class="nav">
                <li class="index"><a href='/index.html'>index</a></li>
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
            
            if(preg_match("/^[^\s]+ Action: /",$line)) {
                $line = substr($line, 0, 8).substr($line, 16);
                $line_classes[] = 'action';
            }
            if(preg_match("/^[^\s]+ Nick change: /",$line)) {
                $line_classes[] = 'nickchange';
            } else if(preg_match("/ joined #{$channel}\.$/",$line)) {
                $line_classes[] = 'join';
            } else if(preg_match("/ mode change /",$line)) {
                $line_classes[] = 'mode';
            } else if(preg_match("/ left #{$channel}\.$/",$line)) {
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
            <li class="webchat"><a href="http://webchat.freenode.net/?randomnick=1&channels=rest">Freenode WebChat</a>
            <li class="github"><a href="http://github.com/KevBurnsJr/rest-irc-log-viewer">Fork me on GitHub</a>
        </ul>
<?
	} else {
?>
        <div style="padding: 12px 20px 18px;">
            <h1><?=$title?></h1>
        </div>
        <div class="dates">
<?
        $maxsize = 0;
		$files = array_reverse(scandir($logdir));
		foreach($files as $file) {
            $maxsize = max($maxsize,filesize($logdir."/".$file));
		}
		foreach($files as $file) {
			if(strpos($file, $logprefix.'.log') > -1) {
                $w = floor(filesize($logdir."/".$file)/$maxsize*100);
				$filedate = substr($file, strlen($logprefix)+5);
				echo "<a href='".$baseurl."/".$filedate.".html'>".$filedate."</a>";
                echo "<div class='bar'><div style='width:".$w."%'>&nbsp;</div></div>";
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
var pageTracker = _gat._getTracker("UA-13006615-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>