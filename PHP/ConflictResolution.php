<?php
$strFName = $_POST['FName'];
$strLName = $_POST['LName'];
$strDepartmentName = $_POST['Department'];
$strLocationName = $_POST['Location'];
$strWindows = $_POST['WindowsVersion'];
$strWebBrowser = $_POST['WebBrowser'];
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
$strComputerName = urldecode($_POST['ComputerName']);
$strDomain = $_POST['Domain'];
$strExtra = $_POST['Extra'];
$OST = $_POST['OST'];
$LionCompatible = $_POST['LionCompatible'];
include_once('info.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Desktop Review Conflict Resolution</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="./css/cstyle.css" />
</head>
<body>
<div id="content">
<?php
echo "<div id=\"top\"><h1>Resolve conflict  for \"" . $strComputerName . "\"</h1></div>";
echo "<div id=\"basic\">";
echo "<table border='1'><tr><th><p>Record Type</p></th><th>Employee Name</th><th>Department</th><th>Location</th><th>OS Version</th><th>Tech Name</th><th>Date</th><th>Action</th></tr>";
$strQuery = "SELECT * FROM ReviewData WHERE ComputerName='" . addslashes($strComputerName) . "' AND ReviewYear='" . date("Y") . "'";
$result = mysql_query($strQuery);
while ($row = mysql_fetch_array($result))
{
	echo "<tr><td><b>Current</b></td>";
	echo "<td><p>" . $row['FName'] . " " . $row['LName'] . "</p></td>";
	echo "<td><p>" . $row['Department'] . "</p></td>";
	echo "<td><p>" . $row['Location'] . "</p></td>";
	echo "<td><p>" . $row['WindowsVersion'] . "</p></td>";
	echo "<td><p>" . $row['Tech'] . "</p></td>";
	echo "<td><p>" . date("F d Y", $row['DateCompleted']) . " @ " . date("g:i A", $row['DateCompleted']) . "</p></td>";
	//The following code will create an Update button for each conflicting record
	// echo "<td><form action=\"ConflictResolutionStage2.php\" method=\"post\">";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"FName\" value=\"" . $strFName . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"LName\" value=\"" . $strLName . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Department\" value=\"" . $strDepartmentName . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"ComputerName\" value=\"" . $strComputerName . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Domain\" value=\"" . $strDomain . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Location\" value=\"" . $strLocationName . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"WindowsVersion\" value=\"" . $strWindows . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"WebBrowser\" value=\"" . $strWebBrowser . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"IESecurityLevel\" value=\"" . $strIESecurityLevel . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"MeetsSLA\" value=\"" . $MeetsSLA . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"MeetsVSLA\" value=\"" . $MeetsVSLA . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"MeetsOSLA\" value=\"" . $MeetsOSLA . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Install\" value=\"" . $strInstall . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Notes\" value=\"" . $strNotes . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"WindowsUpdate\" value=\"" . $WindowsUpdate . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"AutomaticUpdates\" value=\"" . $AutomaticUpdates . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"VirusDefinitions\" value=\"" . $VirusDefinitions . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"SpywareScan\" value=\"" . $SpywareScan . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Tech\" value=\"" . $strTech . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"OST\" value=\"" . $OST. "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"OS\" value=\"" . $strOS. "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"Extra\" value=\"" . $strExtra. "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"ReviewId\" value=\"" . $row['ReviewId'] . "\"/>";
	// echo "<input type=\"text\" style=\"display:none\"  name=\"LionCompatible\" value=\"" . $row['LionCompatible'] . "\"/>";
	// echo "<input type=\"submit\" value=\"Update\"/></form></td></tr>";
}
echo "<tr><td><b>Proposed</b></td>";
echo "<td><p>" . $strFName . " " . $strLName . "</p></td>";
echo "<td><p>" . $strDepartmentName . "</p></td>";
echo "<td><p>" . $strLocationName . "</p></td>";
echo "<td><p>" . $strWindows . "</p></td>";
echo "<td><p>" . $strTech . "</p></td>";
echo "<td><p>" . date("F d Y") . " @ " . date("g:i A") . "</p></td>";
echo "<td><form action=\"ConflictResolutionStage2.php\" method=\"post\">";
echo "<input type=\"text\" style=\"display:none\"  name=\"FName\" value=\"" . $strFName . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"LName\" value=\"" . $strLName . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"Department\" value=\"" . $strDepartmentName . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"Location\" value=\"" . $strLocationName . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"ComputerName\" value=\"" . $strComputerName . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"Domain\" value=\"" . $strDomain . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"WindowsVersion\" value=\"" . $strWindows . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"WebBrowser\" value=\"" . $strWebBrowser . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"IESecurityLevel\" value=\"" . $strIESecurityLevel . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"MeetsSLA\" value=\"" . $MeetsSLA . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"MeetsVSLA\" value=\"" . $MeetsVSLA . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"MeetsOSLA\" value=\"" . $MeetsOSLA . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"Install\" value=\"" . $strInstall . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"Notes\" value=\"" . $strNotes . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"WindowsUpdate\" value=\"" . $WindowsUpdate . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"AutomaticUpdates\" value=\"" . $AutomaticUpdates . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"VirusDefinitions\" value=\"" . $VirusDefinitions . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"SpywareScan\" value=\"" . $SpywareScan . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"Tech\" value=\"" . $strTech . "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"OST\" value=\"" . $OST. "\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"ReviewId\" value=\"0\"/>";
echo "<input type=\"text\" style=\"display:none\"  name=\"LionCompatible\" value=\"" . $LionCompatible . "\"/>";
echo "<input type=\"submit\" value=\"Add\"/>";
echo "</form></td></tr>";
echo "</table>";
echo "</div>";
echo "</div>";
?>
</body>
</html>