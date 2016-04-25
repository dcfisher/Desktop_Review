<?php
include_once('info.php');
//Dustin - Old sql injection, in case I screw up again.
// function SaveRecordInDB($strFName, $strLName, $strDepartment, $strComputerName, $strLocation, $strDomain, $strWindowsVersion, $strWindowsUpdate, $strAutomaticUpdates, $strVirusDefinitions, $strSpywareScan, $strWebBrowser, $strInstallations, $strMeetServiceLevel, $strVistaCompatible, $strOffice2007Compatible, $strComments, $strTech, $LionCompatible, $Processor, $HardDrive, $Optical, $RAM, $Graphics, $Resolution)
function SaveRecordInDB($strFName, $strLName, $strDepartment, $strComputerName, $strLocation, $strDomain, $strWindowsVersion, $strManufacturer, $strSerialNumber, $strModel, $strJavaVersion, $strMeetServiceLevel, $strWindowsCompatible, $strOfficeCompatible, $strTech, $LionCompatible, $Processor, $HardDrive, $RAM)
{
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
	| ReviewYear           | text		  | YES	 |     | NULL    |                |
	| LionCompatible	   | varchar(4)   | YES  |     | NULL    |				  |
	+----------------------+--------------+------+-----+---------+----------------+
	*/
	
	$strComputerName = trim($strComputerName);
	//Set values if missing
	if ($strWebBrowser == "")
	{
		$strWebBrowser = "N/A";
	}
	
	if ($strVistaCompatible == "")
	{
		$strVistaCompatible = "No";
	}

	if ($strOffice2007Compatible == "")
	{
		$strOffice2007Compatible = "No";
	}
	
	if ($LionCompatible == "")
	{
		$LionCompatible = "No";
	}
	
	//code for the SQL Query for the insertion of the record

	//Dustin - Old sql injection, in case I screw up again.
	// $sqlInsertRecord = "insert into ReviewData(FName, LName, Department, ComputerName, Location, Domain, WindowsVersion, WindowsUpdate, AutomaticUpdates, VirusDefinitions, SpywareScan, WebBrowser, Installations, MeetServiceLevel, VistaCompatible, Office2007Compatible, Comments, Tech, DateCompleted, ReviewYear, LionCompatible, Processor, HardDrives, OpticalDrive, RamSize, Graphics, DisplayResolution)";	
	$sqlInsertRecord = "insert into ReviewData(FName, LName, Department, ComputerName, Location, Domain, WindowsVersion, Manufacturer, SerialNumber, Model, JavaVersion, MeetServiceLevel, WindowsCompatible, OfficeCompatible, Tech, DateCompleted, ReviewYear, LionCompatible, Processor, HardDrives, RamSize)";

	//Dustin - Old sql injection, in case I screw up again.
	// $sqlInsertRecord .= " values('" . $strFName . "','" . $strLName . "','" . $strDepartment ."','" . mysql_real_escape_string($strComputerName) . "','" . $strLocation . "','" . $strDomain . "','" . $strWindowsVersion . "','" . $strWindowsUpdate .  "','" . $strAutomaticUpdates . "','" . $strVirusDefinitions . "','" . $strSpywareScan . "','" . $strWebBrowser . "','" . $strInstallations . "','" . $strMeetServiceLevel . "','" . $strVistaCompatible . "','" . $strOffice2007Compatible . "','" . $strComments . "','" .  $strTech  .  "','" . time() . "','" . date("Y") . "','" . $LionCompatible . "','" . $Processor . "','" . $HardDrive . "','" . $Optical . "','" . $RAM . "','" . $Graphics . "','" . $Resolution . "')";	
	$sqlInsertRecord .= " values('" . $strFName . "','" . $strLName . "','" . $strDepartment ."','" . mysql_real_escape_string($strComputerName) . "','" . $strLocation . "','" . $strDomain . "','" . $strWindowsVersion . "','" . $strManufacturer .  "','" . $strSerialNumber . "','" . $strModel . "','" . $strJavaVersion . "','"  . $strMeetServiceLevel . "','" . $strWindowsCompatible . "','" . $strOfficeCompatible . "','" .  $strTech  .  "','" . time() . "','" . date("Y") . "','" . $LionCompatible . "','" . $Processor . "','" . $HardDrive . "','"  . $RAM . "')";

	
	$resultInsertRecord = mysql_query($sqlInsertRecord);
	if ($resultInsertRecord)
	{
		echo "Record was successfully submitted";
	}
	else
	{
		echo "<BR><h1>!!!Record was not saved!!!</h1>";
		echo "<BR>Please save a copy of this computers review.txt file for diagnostic purposes<BR>";
		echo "<BR>The query that returned an error was: <BR><B>" . $sqlInsertRecord . "</B>";
	}
}
?>
