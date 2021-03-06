<?php
//Lets Generate the Fancy Form
function DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strManufacturer, $strModel, $strSerialNumber, $iMemory, $strHDD, $strOfficeMatch, $strProcessor, $strJavaVersion, $SLA, $WinSLA, $OffSLA)
{
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	echo "<head>";
	echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />";
	echo "<title>Desktop Review</title>";
	echo "<style type=\"text/css\" media=\"screen, print\">";
	echo "@import url(./css/pstyle.css);";
	echo "</style>";
	echo "</head>";
	echo "<body>";
	echo "<table class=\"MainForm\" >";
	echo "<tr><td  colspan=\"2\"><p style=\"text-align:right;float:right;\"><span class=\"BoldTitle\">OU Helpdesk</span><br />202 Kresge Library<br />Phone: 248-370-4357 Fax: 248-370-4209</p></td></tr>";
	echo "<tr><td  colspan=\"2\" class=\"SmallTitle\" >" . date("F d Y") . " Desktop Review Audit</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">EMPLOYEE NAME</td></tr>";
	echo "<tr><td>Employee Name: " . $strEmployeeName . "</td><td>Department: " . $strDepartmentName . "</td></tr>";
	echo "<tr><td>Computer Name: " . $strComputerName . "</td><td>Location: " . $strLocationName . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">AUDIT CHECKLIST</td></tr>";
	echo "<tr><td>Domain: </td><td class=\"SmallTitleCenter\" >" . $strDomain . "</td></tr>";
	echo "<tr><td>Windows Version: </td><td class=\"SmallTitleCenter\" >" . $strWindows . "</td></tr>";
	echo "<tr><td>Manufacturer: </td><td class=\"SmallTitleCenter\" >" . $strManufacturer . "</td></tr>";
	echo "<tr><td>Model: </td><td class=\"SmallTitleCenter\" >" . $strModel . "</td></tr>";
	echo "<tr><td>Processor: </td><td class=\"SmallTitleCenter\" >" . $strProcessor . "</td></tr>";
	echo "<tr><td>Serial Number: </td><td class=\"SmallTitleCenter\" >" . $strSerialNumber . "</td></tr>";
	echo "<tr><td>Memory: </td><td class=\"SmallTitleCenter\" >" . $iMemory . "</td></tr>";
	echo "<tr><td>Hard Drive: </td><td class=\"SmallTitleCenter\" >" . $strHDD . "</td></tr>";
	echo "<tr><td>Office Match: </td><td class=\"SmallTitleCenter\" >" . $strOfficeMatch . "</td></tr>";
	echo "<tr><td>Java Version: </td><td class=\"SmallTitleCenter\" >" . $strJavaVersion . "</td></tr>";
	echo "<tr><td>Does it meet Desktop Service Agreement? </td><td class=\"SmallTitleCenter\">" . $SLA . "</td></tr>";
	echo "<tr><td>Windows 10 Compatible? </td><td class=\"SmallTitleCenter\">" . $WinSLA . "</td></tr>";
	echo "<tr><td>Office 2016 Compatible? </td><td class=\"SmallTitleCenter\">" . $OffSLA . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">PC Information</td></tr>";
	echo "<tr><td>Technician Signature: __________________________</td><td>Date Completed: <span class=\"SmallTitleCenter\" >" . date("F d Y") . "</span></td></tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
}
function DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strOS, $strMemory, $strHDD, $strProcessor, $strJavaVer, $strSerNum, $YosCompatible,$SLA)
{
	if ($YosCompatible == "")
	{
		$YosCompatible = "No";
	}
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
	echo "<head>";
	echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />";
	echo "<title>Desktop Review</title>";
	echo "<style type=\"text/css\" media=\"screen, print\">";
	echo "@import url(./css/pstyle.css);";
	echo "</style>";
	echo "</head>";
	echo "<body>";
	echo "<table class=\"MainForm\" >";
	echo "<tr><td  colspan=\"2\"><p style=\"text-align:right;float:right;\"><span class=\"BoldTitle\">OU Helpdesk</span><br />202 Kresge Library<br />Phone: 248-370-4357 Fax: 248-370-4209</p></td></tr>";
	echo "<tr><td  colspan=\"2\" class=\"SmallTitle\" >". date("Y") . " Mac Review Audit</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">EMPLOYEE NAME</td></tr>";
	echo "<tr><td>Employee Name: " . $strEmployeeName . "</td><td>Department: " . $strDepartmentName . "</td></tr>";
	echo "<tr><td>Computer Name: " . $strComputerName . "</td><td>Location: " . $strLocationName . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">AUDIT CHECKLIST</td></tr>";
	echo "<tr><td>OSX Version:</td><td class=\"SmallTitleCenter\">" . $strOS . "</td></tr>";
	echo "<tr><td>Memory:</td><td class=\"SmallTitleCenter\">" . $strMemory . "</td></tr>";
	echo "<tr><td>Capacity:</td><td class=\"SmallTitleCenter\">" . $strHDD . "</td></tr>";
	echo "<tr><td>Processor:</td><td class=\"SmallTitleCenter\">" . $strProcessor . "</td></tr>";
	echo "<tr><td>Java Version:</td><td class=\"SmallTitleCenter\">" . $strJavaVer . "</td></tr>";
	echo "<tr><td>Serial Number:</td><td class=\"SmallTitleCenter\">" . $strSerNum . "</td></tr>";
	echo "<tr><td>Does This Computer Meet Mac Service Level</td><td class=\"SmallTitleCenter\">" . $SLA . "</td></tr>";
	echo "<tr><td>Is This Computer Yosemite(OSX 10.10) Compatible</td><td class=\"SmallTitleCenter\">" . $YosCompatible . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">PC Information</td></tr>";
	echo "<tr><td>Technician Signature: __________________________</td><td>Date Completed: <span class=\"SmallTitleCenter\" >" . date("F d Y") . "</span></td></tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
}
?>