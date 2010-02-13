<?
$title = "#Rest on Freenode";

if(getenv('APP_ENV') == 'local') {
    $logdir = dirname(__FILE__)."/sample-logs";
    $baseurl = "http://rest.local";
    $logprefix = "rest";
} else {
    $logdir = "/home/kevburns/eggdrop/logs/rest";
    $baseurl = "http://rest.hackyhack.net";
    $logprefix = "rest";
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title><?=$title?></title>
        <link rel="stylesheet" type="text/css" href="/css/default.css" media="screen" />
        <script type="text/javascript" src="/js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="/js/jquery.plugins.js"></script>
        <script type="text/javascript" src="/js/global.js"></script>
    </head>
    <body><a name="top"></a><div class="wrapper">
<?
    if(isset($_REQUEST['date'])) {
        $logdate = $_REQUEST['date'];
        $filename = $logdir.'/'.$logprefix.'.log.'.$logdate;
        $lines = file_exists($filename) ? file($filename) : null;
    }
	if(isset($_REQUEST['date']) && count($lines)) { ?>
        <div class="hdr">
            <h1><?=$title?> - <?=$logdate?></h1>
            <ul class="nav">
                <!-- <li class="prev"><a href='/'>prev</a></li> -->
                <li class="index"><a href='/'>index</a></li>
                <!-- <li class="next"><a href='/'>next</a></li> -->
            </ul>
        </div>
        <ul class="lines">
        <? 
        $times = array();
        $i = 0;
        foreach ($lines as $line_num => $line) { 
            $line = trim($line,"\r\n");
            $line_classes = array();
            
            if(preg_match("/^Action: /",$line)) {
                $line = preg_replace("/^Action: /","",$line);
                $line_classes[] = 'action';
            } else if(preg_match("/^Nick change: /",$line)) {
                $line_classes[] = 'nickchange';
            } else if(preg_match("/ joined /",$line)) {
                $line_classes[] = 'join';
            } else if(preg_match("/ mode change /",$line)) {
                $line_classes[] = 'mode';
            } else if(preg_match("/ left irc: /",$line)) {
                $line_classes[] = 'left';
            } else if(preg_match("/left irc: Quit: /",$line)) {
                $line_classes[] = 'left';
                $line_classes[] = 'quit';
            } else if(preg_match("/left irc: Read error: /",$line)) {
                $line_classes[] = 'left';
                $line_classes[] = 'error';
            }
            
            $line = htmlspecialchars($line);
            
            $line = preg_replace("/^\[([\d]{2}):([\d]{2})\](.*)/", "<a href='#l$i' class='ts'>[\\1:\\2]</a><span class='t'>\\3</span>", $line);
            
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
            <li class="top"><a href='#top' title="Top">Top</a></li>
            <li class="bottom"><a href='#bottom' title="Bottom">Bottom</a></li>
            <li class="clear"><a href='#none' title="Clear Selection">Clear Selection</a></li>
            <li class="permalink"><a href='#' title="Permalink">Permalink</a></li>
        </ul>
<?
	} else {
		$files = scandir($logdir);
		foreach($files as $file) {
			if(strpos($file, $logprefix.'.log') > -1) {
				$filedate = substr($file, strlen($logprefix)+5);
				echo "<a href='".$baseurl."/".$filedate.".html'>".$filedate."</a><br />";
			}
		}
	}
?>
    </div><a name="bottom"></a></body>
</html>

