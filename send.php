<?php
//start
//error_reporting(0);
//header ("Content-Type: text/html; charset=utf-8");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set("Europe/Kiev"); 
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
//header ("Content-Type: text/html; charset=utf-8");
//mail("e.gavrilenko@dkhz.com.ua", "My Subject", "Line 1\nLine 2\nLine 3");
//phpinfo();
//var $info1, $info2;


echo (	//head
	'<!DOCTYPE html>
	<html lang="en">
  	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>print</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  	</head>'
	);

echo ( //body
	'<body>
	<div class="container">
    <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>'
	);
include('nav.php');
$runtime = date("Y-m-d H:i:s");
echo ("<div class=\"page-header\">
  <h2>Sending report<small></small></h2></div>");



$default_maito = "e.gavrilenko@dkhz.com.ua";
$default_from = "e.gavrilenko@dkhz.com.ua";
$default_subject = "Minoltas counters report from DKHZ";
$default_message = "message";




 if (isset($_POST['mailto'])) {
 	
 	

 	$to = htmlspecialchars($_POST['mailto']);
 	$headers = 'From: '.htmlspecialchars($_POST['from'])."\r\n".'X-Mailer: PHP/'.phpversion();
 	$subject = htmlspecialchars($_POST['subject']);
 	$message = htmlspecialchars($_POST['massage']);
	
	/*echo($to);
	echo($headers);
	echo($subject);
	echo($message);*/
 
	if (mail($to, $subject, $message, $headers)) {
	echo ('<div class="alert alert-success">Well done! You successfully sent mail.</div>');
	} else {
	echo ('<div class="alert alert-danger">Error!</div>');	
	}

	 /*
	$to      = 'e.gavrilenko@dkhz.com.ua';
	$subject = 'the subject';
	$message = 'hello';
	$headers = 'From: noreply@example.com'."\r\n".'Reply-To: webmaster@example.com'."\r\n".'X-Mailer: PHP/'.phpversion();

	mail($to, $subject, $message, $headers);

	*/
 } else {

 	echo('
	<form action="send.php" method="post" class="form-group">
    <div class="form-group">
    <label for="mailto">mailt to</label>
    <input type="text" class="form-control" id="inputEmail" placeholder="email" name="mailto" value="');
    echo($default_maito);
    echo('">
  	</div>

 	 <div class="form-group">
    <label for="from">from</label>
    <input type="text" class="form-control" id="from" placeholder="from" name="from" value="');
    echo($default_from);
    echo('">
  	</div>

 	 <div class="form-group">
    <label for="subject">subject</label>
    <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" value="');
    echo($default_subject);
    echo('">
  	</div>

	<div class="form-group">
	<label for="Message">Message</label>
	<textarea name="massage" class="form-control" id="Message" rows="3">');
	echo($default_message);
	echo('</textarea>
	</div>

  	<div class="checkbox">
    <label>
      <input type="checkbox" name="save"checked>Save counters to config</input>
    </label>
  	</div>

  	<button type="submit" class="btn btn-default">Submit</button>
    </form>
	');
	}

 echo "<br>POST<br>";
  foreach($_POST as $key => $value)
  {
     echo "\$_POST[".$key."] = ".$value."<br>";
  } 

echo( //end
	'<div>
	</body>
	</html>'
	);
?>