<?php
//error_reporting(0);
header ("Content-Type: text/html; charset=utf-8");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header ("Content-Type: text/html; charset=utf-8");
//phpinfo();
//var $info1, $info2;


echo "<!DOCTYPE html>
<html>
<head>
<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">
<link rel=\"stylesheet\" href=\"css/bootstrap-theme.min.css\"> 
<script src=\"js/bootstrap.min.js\"></script> 
<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">
<title>print</title>
</head>
<body style=\"background: url(fon.png) top left repeat-x\">
<div class=\"container\">";

$runtime = date("Y-m-d H:i:s");
echo ("<div class=\"page-header\">
  <h1>Инфа по принтерам: <small>".$runtime."</small></h1></div>");
//echo "type: ".gettype($runtime)."</br>";


snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
$xml = simplexml_load_file('config.xml');
   // print_r($xml);

foreach($xml->printers->printer as $printer) {
	echo('<div class="panel panel-primary">
  	<div class="panel-heading"><span class="glyphicon glyphicon-print"></span>');
	//$last=$printer->last;
	$ip = $printer->ip;
	echo "  minolta ".$printer->id."</div><div class=\"panel-body\">";

	echo "<div>control date: <span class=\"badge\">".$printer->lastControlTime."</span></div>";
	echo "<div>control count: <span class=\"badge\">".$printer->lastControlCount."</span></div>";
	$info =  (int) snmpget($ip, "private", ".1.3.6.1.4.1.18334.1.1.1.5.7.2.1.1.0");
	echo "<div>current count: <span class=\"badge\">".$info."</span></div>";
	//echo "type: ".gettype($info)."</br>";
	$lastdelta=$info-$printer->last;
//	echo "last delta: ".$lastdelta."</br>";
	$fulldelta=$info-$printer->lastControlCount;
	$status="success";
	if ($fulldelta>7000) {$status="warning"; }
	if ($fulldelta>15000) {$status="danger"; }
	echo "<div>full delta: <span class=\"label label-".$status."\">".$fulldelta."</span></div>";
	$printer->last=$info;	
	//$printer->lastControlCount=$info;
	//$printer->lastControlTime=$runtime;

	$d1= new DateTime($printer->lastControlTime);
	$d2= new DateTime($runtime);
	$interval = $d1->diff($d2)->format('%R%a дней');
	echo "interval: ".$interval."</br>";
	echo("</div></div>");



	$printer->lasttime=$runtime;
};

//$xml->printer[1]->last=2;
$xml->asXML('config.xml');


//echo "minolta sbit current counter: ".$info1."</br>";
//echo "minolta pko  current counter: ".$info2."</br>";
echo "</div></body>
</html>";
?>
