<?php
include_once('info.php');
function SaveRecordInDB($strFName, $strLName, $strDepartment, $strComputerName, $strLocation, $strDomain, $strWindowsVersion, $strManufacturer, $strSerialNumber, $strModel, $strJavaVersion, $strMeetServiceLevel, $strWindowsCompatible, $strOfficeCompatible, $strTech, $LionCompatible, $Processor, $HardDrive, $RAM, $OfficeMatch)
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
	$timestamp = date('l jS \of F Y h:i:s A');
	
	if ($strWindowsCompatible == "")
	{
		$strWindowsCompatible = "No";
	}

	if ($strOfficeCompatible == "")
	{
		$strOfficeCompatible = "No";
	}
	
	if ($LionCompatible == "")
	{
		$LionCompatible = "No";
	}
	
	// Dustin - Basic SQL record insertion
	$sqlInsertRecord = "insert into ReviewData(FName, LName, Department, ComputerName, Location, Domain, WindowsVersion, Manufacturer, SerialNumber, Model, JavaVersion, MeetServiceLevel, WindowsCompatible, OfficeCompatible, Tech, DateCompleted, ReviewYear, LionCompatible, Processor, HardDrives, RamSize, OfficeMatch)";

	$sqlInsertRecord .= " values('" . $strFName . "','" . $strLName . "','" . $strDepartment ."','" . mysql_real_escape_string($strComputerName) . "','" . $strLocation . "','" . $strDomain . "','" . $strWindowsVersion . "','" . $strManufacturer .  "','" . $strSerialNumber . "','" . $strModel . "','" . $strJavaVersion . "','"  . $strMeetServiceLevel . "','" . $strWindowsCompatible . "','" . $strOfficeCompatible . "','" .  $strTech  .  "','" . $timestamp . "','" . date("Y") . "','" . $LionCompatible . "','" . $Processor . "','" . $HardDrive . "','"  . $RAM . "','"  . $OfficeMatch ."')";

	
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
