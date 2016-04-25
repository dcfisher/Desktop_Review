<?php
//for dev
ini_set("display_errors", "1");
error_reporting(-1);
//end dev

date_default_timezone_set('America/New_York');
//Connect to  MYSQL
$dbConnect = mysql_connect('localhost','Desktop','R3vi3w') or die("Error - Mysql Cannot Be Reached");
//$dbSelect = mysql_select_db('DesktopReview',$dbConnect);
mysql_select_db('DesktopReview',$dbConnect);
?>