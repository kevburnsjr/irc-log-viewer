<?
function irc_color_codes($eilute){
$eilute=htmlspecialchars($eilute);
//-----------------------background------------------
if(eregi("",$eilute,$regs)){
$pieces = explode("", $eilute);
$x=0;
$y=0;
$eilute="";
while ($pieces[$x]){
			if(ereg(",0",$pieces[$x],$regs)&&$y==0){
							 $pieces[$x]=str_replace(",0","<span style='background-color: #FFFFFF'>",$pieces[$x]);
							 $y=1;
			}
			else if(ereg(",0",$pieces[$x],$regs)&&$y==1){
							 $pieces[$x]=str_replace(".0","</span>",$pieces[$x]);
							 $y=0;
			}
			$eilute=$eilute.$pieces[$x];
			$x++;
		
	if($y==1)$eilute=$eilute."</span>";
$eilute."/n";
}
}
//-----------------------eof background------------------
//-----------------------Bold------------------
if(eregi("",$eilute,$regs)){
$pieces = explode("", $eilute);
$x=0;
$y=0;
$eilute="";
while ($pieces[$x]){
			if(ereg(",0",$pieces[$x],$regs)&&$y==0){
							 $pieces[$x]="<B>".$pieces[$x];
							 $y=1;
			}
			else if(ereg(",0",$pieces[$x],$regs)&&$y==1){
							 $pieces[$x]="</B>".$pieces[$x];
							 $y=0;
			}
			$eilute=$eilute.$pieces[$x];
			$x++;
		
	if($y==1)$eilute=$eilute."</b>";
$eilute."/n";
}
}
//-----------------------eof Bold------------------
if(ereg("Action: ",$eilute,$regs)){
	$eilute=str_replace("Action: ","",$eilute);
	$eilute="<font color='#9C009C'><b>".$eilute."</b></font>";
}
if(ereg("Nick change: ",$eilute,$regs)||ereg(" joined ",$eilute,$regs)||ereg(" mode change ",$eilute,$regs)){
	$eilute="<font color='#009300'><b>".$eilute."</b></font>";
    }
if(ereg(" left irc: ",$eilute,$reg)||ereg("left irc: Quit: ",$eilute,$reg)||ereg("left irc: Read error: ",$eilute,$reg)){
	$eilute="<font color='#0000FC'><b>".$eilute."</b></font>";
    }
$eilute=str_replace("15","</font><font color='#D2D2D2' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("14","</font><font color='#7F7F7F' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("13","</font><font color='#FF00FF' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("12","</font><font color='#0000FC' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("11","</font><font color='#00FFFF' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("10","</font><font color='#009393' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("9","</font><font color='#00FC00' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("8","</font><font color='#FFFF00' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("7","</font><font color='#FC7F00' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("6","</font><font color='#9C009C' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("5","</font><font color='#7F0000' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("4","</font><font color='#FF0000' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("3","</font><font color='#009300' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("2","</font><font color='#00007F' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("1","</font><font color='#000000' face='tahoma' font size='2'>",$eilute);
$eilute=str_replace("0","</font><font color='#FFFFFF' face='tahoma' font size='2'>",$eilute);
$eilute=$eilute."</font><font face='tahoma' font size='2'>";
//su shitais dar nesugalvojau ka daryti :|
#$eilute=str_replace(",0","",$eilute);
#$eilute=str_replace("","",$eilute);
#$eilute=str_replace("","",$eilute);
#$eilute=str_replace("","",$eilute);
return $eilute;
}
?>