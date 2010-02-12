<?
	require_once "functions/codes.php";
	
	$logdir = "/home/kevburns/eggdrop/logs/";
	$baseurl = "";
	$logprefix = "rest";

?>
<html>
    <head>
        <title>#Rest on Freenode</title>
    </head>
    <body bgcolor="black" text="white">
<?
	$logdate = $_REQUEST['date'];
	$filename = $logdir.$logprefix.'.log.'.$logdate;
	$lines = file_exists($filename) ? file($filename) : null;
	if(empty($lines)) {
		$files = scandir($logdir);
		foreach($files as $file) {
			if(strpos($file, $logprefix.'.log') > -1) {
				$filedate = substr($file, strlen($logprefix)+5);
				echo "<a href='".$baseurl."/".$filedate.".html'>".$filedate."</a><br />";
			}
		}
	} else {
		echo "<a href='/'>back</a><br />";
		foreach ($lines as $line_num => $line) {
			echo irc_color_codes($line);
			echo "<br>\n";
	    }
	}
?>
    </body>
</html>

