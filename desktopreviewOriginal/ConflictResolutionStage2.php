<?php
$ReviewId = $_POST['ReviewId'];
$strFName = $_POST['FName'];
$strLName = $_POST['LName'];
$strDepartmentName = $_POST['Department'];
$strLocationName = $_POST['Location'];
$strWindows = $_POST['WindowsVersion'];
$strWebBrowser = $_POST['WebBrowser'];
$strLocationName = $_POST['Location'];
$strIESecurityLevel = $_POST['IESecurityLevel'];
$MeetsSLA = $_POST['MeetsSLA'];
$MeetsVSLA = $_POST['MeetsVSLA'];
$MeetsOSLA = $_POST['MeetsOSLA'];
$strInstall = $_POST['Install'];
$strNotes = $_POST['Notes'];
$WindowsUpdate = $_POST['WindowsUpdate'];
$AutomaticUpdates = $_POST['AutomaticUpdates'];
$VirusDefinitions = $_POST['VirusDefinitions'];
$SpywareScan = $_POST['SpywareScan'];
$strTech = $_POST['Tech'];
$strComputerName = $_POST['ComputerName'];
$strDomain = $_POST['Domain'];
$OST = $_POST['OST'];
$LionCompatible = $_POST['LionCompatible'];

//includes
include_once('SaveRecord.php');
include_once('DisplayForm.php');

//Database modifications
if ($ReviewId == 0)
{
	SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $WindowsUpdate, $AutomaticUpdates, $VirusDefinitions, $SpywareScan, $strWebBrowser, $strInstall, $MeetsSLA, $MeetsVSLA, $MeetsOSLA, $strNotes, $strTech, $LionCompatible, "N/A", "N/A", "N/A", "N/A", "N/A", "N/A");
}
else //Update existing record... well deleting and recreating it.
{
	$result = mysql_query("DELETE FROM ReviewData WHERE ReviewId='" . $ReviewId . "'");
	SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $WindowsUpdate, $AutomaticUpdates, $VirusDefinitions, $SpywareScan, $strWebBrowser, $strInstall, $MeetsSLA, $MeetsVSLA, $MeetsOSLA, $strNotes, $strTech, $LionCompatible, "N/A", "N/A", "N/A", "N/A", "N/A", "N/A");
}



//Display Form
if ($OST == "WIN")
{
	$strEmployeeName = $strFName . " " . $strLName;
	DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strWebBrowser, $strIESecurityLevel, $MeetsSLA, $MeetsVSLA, $MeetsOSLA, $strInstall, $strNotes);
}
else
{ //Display Mac version of form
	$strEmployeeName = $strFName . " " . $strLName;
	DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $MeetsSLA, $strInstall, $strNotes, $SpywareScan, $LionCompatible);
}



?>