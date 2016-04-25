<?php

/*INSERT INTO `Desktop Review` (FNAME,LNAME,Department,`Computer Name`,Location,Domain,`Windows Version`,
`Windows Update Performed`,`Automatic Windows Update enabled`,`Virus Definitions Updated`,`Spyware Scan Performed`,
`Web Browser`,Installations,`Meet 2007/08 Service Level Agreement?`,`Vista Compatible`,`Office 2013 Compatible`, Comments, 
Technician, `Date Completed`)  VALUES ('Jason','Rutherford','UTS Helpdesk','Power Mac G5','202 Kresge Library','n/a','10.4 Tiger',
'Yes','Yes','No','No','N/A','Office 2008','Yes','N/A','N/A','Too many PEBCAK occurances','Jason Rutherford','6/15/2009') */


//Old way to Connect to  MYSQL
//$dbConnect = mysql_connect('localhost','Desktop','R3vi3w') or die("Error - Mysql Cannot Be Reached");
//$dbSelect = mysql_select_db('DesktopReview2011',$dbConnect);

//New way to Connect to MYSQL
include_once('info.php');
include_once('TextFunctions.php');

//Get the data from the table
/*
+----------------------+--------------+------+-----+---------+----------------+
| Field                | Type         | Null | Key | Default | Extra          |
+----------------------+--------------+------+-----+---------+----------------+
| ReviewId             | int(11)      | NO   | PRI | NULL    | auto_increment | 
| FName                | varchar(50)  | YES  |     | NULL    |                | 
| LName                | varchar(50)  | YES  |     | NULL    |                | 
| Department           | varchar(255) | YES  |     | NULL    |                | 
| ComputerName         | varchar(500) | YES  |     | NULL    |                | 
| Location             | varchar(25)  | YES  |     | NULL    |                | 
| Domain               | varchar(20)  | YES  |     | NULL    |                | 
| WindowsVersion       | varchar(50)  | YES  |     | NULL    |                | 
| WindowsUpdate        | varchar(4)   | YES  |     | NULL    |                | 
| AutomaticUpdates     | varchar(4)   | YES  |     | NULL    |                | 
| VirusDefinitions     | varchar(4)   | YES  |     | NULL    |                | 
| SpywareScan          | varchar(4)   | YES  |     | NULL    |                | 
| WebBrowser           | varchar(25)  | YES  |     | NULL    |                | 
| Installations        | text         | YES  |     | NULL    |                | 
| MeetServiceLevel     | varchar(4)   | YES  |     | NULL    |                | 
| VistaCompatible      | varchar(4)   | YES  |     | NULL    |                | 
| Office2007Compatible | varchar(4)   | YES  |     | NULL    |                | 
| Comments             | text         | YES  |     | NULL    |                | 
| Tech                 | varchar(100) | YES  |     | NULL    |                | 
| DateCompleted        | int(11)      | YES  |     | NULL    |                | 
| Processor			   | varchar(500) | YES  |     | NULL	 |				  |
| HardDrives		   | varchar(100) | YES  |     | NULL    |				  |
| OpticalDrive		   | varchar(100) | YES  |     | NULL	 |				  |
| RamSize			   | varchar(10)  | YES  |	   | NULL	 |				  |
| Graphics			   | varchar(100) | YES	 |	   | NULL	 |				  |
| DisplayResolution	   | varchar(100) | YES	 |	   | NULL	 |				  |
+----------------------+--------------+------+-----+---------+----------------+
*/

//$fOut = fopen("../../exportdata/DatabaseExport.txt",'w');
$fOut = fopen("DatabaseExport.txt",'w');

$sqlGetTableData = "select FName,LName,Department,ComputerName,Location,Domain,WindowsVersion,WindowsUpdate,AutomaticUpdates,VirusDefinitions,SpywareScan,WebBrowser,Installations,MeetServiceLevel,VistaCompatible,Office2007Compatible,Comments,Tech,DateCompleted, LionCompatible, Processor, OpticalDrive, RamSize, Graphics, DisplayResolution from ReviewData where ReviewYear = ". date("Y");
//echo $sqlGetTableData . "<br />";

$resultGetTableData = mysql_query($sqlGetTableData);
$arrayGetTableData = mysql_fetch_array($resultGetTableData);

$strRows = "FNAME;LNAME;Department;Computer Name;Location;Domain;Windows Version;Windows Update Performed;Automatic Windows Update enabled;Virus Definitions Updated;Spyware Scan Performed;Web Browser;Installations;Meets Service Level Agreement?;Vista/7 Compatible;Office 2013 Compatible;Comments;Technician;Date Completed;Lion Compatible;Processor;OpticalDrive;RamSize;Graphics"; //DisplayResolution";

fwrite($fOut,$strRows ."\n",2000);

while ($arrayGetTableData)
{

	

	$txtInstallations = preg_replace('/<br\s*\/>/','.  ', $arrayGetTableData['Installations']);
	$txtInstallations = preg_replace('/\r\n/','.  ',$txtInstallations);
	$txtInstallations = preg_replace('/\n/','.  ',$txtInstallations);
	
	$txtComments = preg_replace('/<br\s*\/>/','.  ', stripslashes($arrayGetTableData['Comments']));
	$txtComments = preg_replace('/\r\n/','.  ',$txtComments);
	$txtComments = preg_replace('/\n/','.  ',$txtComments);
	

	//$strTextStatement  = "'" . $arrayGetTableData['FName'] ."';'" .  $arrayGetTableData['LName']  ."';'" . $arrayGetTableData['Department'] . "';'" . $arrayGetTableData['ComputerName'] . "';'" . $arrayGetTableData['Location'] ."';'" . $arrayGetTableData['Domain'] . "';'" . $arrayGetTableData['WindowsVersion'] . "';	'" . $arrayGetTableData['WindowsUpdate'] . "';'" . $arrayGetTableData['AutomaticUpdates'] . "';'" . $arrayGetTableData['VirusDefinitions'] . "';'" . $arrayGetTableData['SpywareScan'] . "';'" . $arrayGetTableData['WebBrowser'] . "';'" . $txtInstallations ."';'" . $arrayGetTableData['MeetServiceLevel'] ."';'" . $arrayGetTableData['VistaCompatible'] . 	"';'" . $arrayGetTableData['Office2007Compatible'] . "';'" . $txtComments . "';'" . $arrayGetTableData['Tech'] . "';'" . date('m/d/Y',$arrayGetTableData['DateCompleted']) . "';'" . $arrayGetTableData['LionCompatible'] . "'";
	$strTextStatement  = StripDSpace("'" . $arrayGetTableData['FName'] ."';'" .  $arrayGetTableData['LName']  ."';'" . $arrayGetTableData['Department'] . "';'" . $arrayGetTableData['ComputerName'] . "';'" . $arrayGetTableData['Location'] ."';'" . $arrayGetTableData['Domain'] . "';'" . $arrayGetTableData['WindowsVersion'] . "';	'" . $arrayGetTableData['WindowsUpdate'] . "';'" . $arrayGetTableData['AutomaticUpdates'] . "';'" . $arrayGetTableData['VirusDefinitions'] . "';'" . $arrayGetTableData['SpywareScan'] . "';'" . $arrayGetTableData['WebBrowser'] . "';'" . $txtInstallations ."';'" . $arrayGetTableData['MeetServiceLevel'] ."';'" . $arrayGetTableData['VistaCompatible'] . 	"';'" . $arrayGetTableData['Office2007Compatible'] . "';'" . $txtComments . "';'" . $arrayGetTableData['Tech'] . "';'" . date('m/d/Y',$arrayGetTableData['DateCompleted']) . "';'" . $arrayGetTableData['LionCompatible'] . "';'" . $arrayGetTableData['Processor'] . "';'" . $arrayGetTableData['OpticalDrive'] . "';'" . $arrayGetTableData['RamSize'] . "';'" . $arrayGetTableData['Graphics'] . "';'" . "'"); //$arrayGetTableData['DisplayResolution'] . "'");
	$sqlAccessStatement = "INSERT INTO `Desktop Review` (" . $strRows  . ")  VALUES (" . $strTextStatement . ")";
	$strDisplayStatement = preg_replace("/\\'/"," ",$strTextStatement);


/*$sqlAccessStatement = "INSERT INTO `Desktop Review` (FNAME,LNAME,Department,`Computer Name`,Location,Domain,`Windows Version`,
`Windows Update Performed`,`Automatic Windows Update enabled`,`Virus Definitions Updated`,`Spyware Scan Performed`,
`Web Browser`,Installations,`Meet 2007/08 Service Level Agreement?`,`Vista Compatible`,`Office 2013 Compatible`, Comments, 
Technician, `Date Completed`)  VALUES ('" . $arrayGetTableData['FName'] ."','" .  $arrayGetTableData['LName']  ."','" . $arrayGetTableData['Department'] . "','" . $arrayGetTableData['ComputerName'] . "','" . $arrayGetTableData['Location'] ."','" . $arrayGetTableData['Domain'] . "','" . $arrayGetTableData['WindowsVersion'] . "',
'" . $arrayGetTableData['WindowsUpdate'] . "','" . $arrayGetTableData['AutomaticUpdates'] . "','" . $arrayGetTableData['VirusDefinitions'] . "','" . $arrayGetTableData['SpywareScan'] . "','" . $arrayGetTableData['WebBrowser'] . "','" . $arrayGetTableData['Installations'] ."','" . $arrayGetTableData['MeetServiceLevel'] ."','" . $arrayGetTableData['VistaCompatible'] . 
"','" . $arrayGetTableData['Office2007Compatible'] . "','" . $arrayGetTableData['Comments'] . "','" . $arrayGetTableData['Tech'] . "','" . date('m/d/Y',$arrayGetTableData['DateCompleted'])  . "')";*/

//echo $sqlAccessStatement . "<br /><br />";
	fwrite($fOut, $strDisplayStatement . "\n",2000);

	$arrayGetTableData = mysql_fetch_array($resultGetTableData);
}

print "Data has been dumped to DatabaseExport.txt";
?>
<br>
<a href="http://utsmini1.kl.oakland.edu/desktopreview/DatabaseExport.txt">Right-Click and Save Link As</a>
