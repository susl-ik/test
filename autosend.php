<?php
error_reporting(0);
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
date_default_timezone_set("Europe/Kiev"); 
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);

$runtime = date("Y-m-d H:i:s");

$xml = simplexml_load_file('/usr/local/www/apache24/data/minoltas/config.xml');


$from = $xml->global->defaultFrom;
$to = $xml->global->defaultTo;
$cc = $xml->global->defaultCc;
$subject = $xml->global->defaultSubject;;
$message = "Состояние счетчиков печати на ПАО \"ЕВРАЗ Днепродзержинский КХЗ\" \r\n";

$status=TRUE;
foreach($xml->printers->printer as $printer) {
  $fp = fsockopen ("$printer->ip", 80, $errno, $errstr, 15);
  if (!$fp) { $status=FALSE; }
}

$info = [];
$lasttime=[];
foreach($xml->printers->printer as $printer) {
	$ip = $printer->ip;
	$serial = $printer->serial;
	$model = $printer->model;
  if ($status) {
	$info["$serial"] =  (int) snmpget($ip, "private", ".1.3.6.1.4.1.18334.1.1.1.5.7.2.1.1.0");
  $lasttime["$serial"] = $runtime;
  } else {
  $info["$serial"] = $printer->last;
  $lasttime["$serial"] = $printer->lasttime;
  }
	$message .= $model.'   serial: '.$serial.'   current counter: '.$info["$serial"].'     control date: '.$lasttime["$serial"]."\r\n";
}

 $headers = 'From: '.$from."\r\n"."Cc: ".$cc."\r\n"."Content-type: text/plain; charset=UTF-8 \r\n".'X-Mailer: PHP/'.phpversion();
 
 mail($to, $subject, $message, $headers);

foreach($xml->printers->printer as $printer) {
		$printer->lastControlCount=$info["$printer->serial"];
		$printer->lastControlTime=$lasttime["$printer->serial"];
}

$xml->asXML('/usr/local/www/apache24/data/minoltas/config.xml');
?>
