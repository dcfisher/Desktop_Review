<?php
//Lets Generate the Fancy Form
//Dustin - Old function def, updated 4/13/2016
// function DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strWebBrowser, $strIESecurityLevel, $SLA, $VSLA, $OSLA, $strInstall, $strNotes)
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
	echo "<tr><td  colspan=\"2\" class=\"SmallTitle\" >" . date("Y") . " Desktop Review Audit</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">EMPLOYEE NAME</td></tr>";
	echo "<tr><td>Employee Name: " . $strEmployeeName . "</td><td>Department: " . $strDepartmentName . "</td></tr>";
	echo "<tr><td>Computer Name: " . $strComputerName . "</td><td>Location: " . $strLocationName . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">AUDIT CHECKLIST</td></tr>";
	echo "<tr><td>Domain: </td><td class=\"SmallTitleCenter\" >" . $strDomain . "</td></tr>";
	echo "<tr><td>Windows Version: </td><td class=\"SmallTitleCenter\" >" . $strWindows . "</td></tr>";
	echo "<tr><td>Manufacturer: </td><td class=\"SmallTitleCenter\" >" . $strManufacturer . "</td></tr>";
	echo "<tr><td>Model: </td><td class=\"SmallTitleCenter\" >" . $strModel . "</td></tr>";
	echo "<tr><td>Serial Number: </td><td class=\"SmallTitleCenter\" >" . $strSerialNumber . "</td></tr>";
	echo "<tr><td>Memory: </td><td class=\"SmallTitleCenter\" >" . $iMemory . "</td></tr>";
	echo "<tr><td>Hard Drive: </td><td class=\"SmallTitleCenter\" >" . $strHDD . "</td></tr>";
	echo "<tr><td>Office Match: </td><td class=\"SmallTitleCenter\" >" . $strOfficeMatch . "</td></tr>";
	echo "<tr><td>Java Version: </td><td class=\"SmallTitleCenter\" >" . $strJavaVersion . "</td></tr>";
	// echo "<tr><td>Windows Update Performed:</td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Automatic Updates:</td><td class=\"SmallTitleCenter\" ><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Virus Definitions Updated: </td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Spyware Scan and Removal: </td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Web Browser: </td><td class=\"SmallTitleCenter\">" . $strWebBrowser . "</td></tr>";
	// echo "<tr><td>IE Security Level </td><td class=\"SmallTitleCenter\">" . $strIESecurityLevel . "</td></tr>";
	echo "<tr><td>Does it meet Desktop Service Agreement? </td><td class=\"SmallTitleCenter\">" . $SLA . "</td></tr>";
	echo "<tr><td>Windows 10 Compatible? </td><td class=\"SmallTitleCenter\">" . $WinSLA . "</td></tr>";
	echo "<tr><td>Office 2016 Compatible? </td><td class=\"SmallTitleCenter\">" . $OffSLA . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">PC Information</td></tr>";
	// echo "<tr><td class=\"Border\" colspan=\"2\"   ><span class=\"SmallTitle\">Notes:</span><br /><p style=\"margin-bottom:25px;\">" . $strInstall . "<br />" . $strNotes . "</p></td></tr>";
	echo "<tr><td>Technician Signature: __________________________</td><td>Date Completed: <span class=\"SmallTitleCenter\" >" . date("F d Y") . "</span></td></tr>";
	// echo "<tr><td><a href=\"https://docs.google.com/a/oakland.edu/forms/d/1m43vdBk8qgXm-wRS6wrRpbOUgP8IcL97bWQx9R3i9hw/viewform?usp=send_form\">Enter Serial Number Information</a><td><tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
}
function DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strOS, $strMemory, $strHDD, $strProcessor, $strJavaVer, $strSerNum, $YosCompatible,$SLA,$boolFuncCall, $aComputerLevel, $ifCheckTrue, $ifCheckElse)
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
	echo "<tr><td>Did it go into the function?</td><td class=\"SmallTitleCenter\">" . $boolFuncCall . "</td></tr>";
	echo "<tr><td>Here is the damn processor:</td><td class=\"SmallTitleCenter\">" . $aComputerLevel . "</td></tr>";
	echo "<tr><td>Went to the if statement</td><td class=\"SmallTitleCenter\">" . $ifCheckTrue . "</td></tr>";
	echo "<tr><td>Went to the else statement</td><td class=\"SmallTitleCenter\">" . $ifCheckElse . "</td></tr>";
	echo "<tr><td>Does This Computer Meet Mac Service Level</td><td class=\"SmallTitleCenter\">" . $SLA . "</td></tr>";
	echo "<tr><td>Is This Computer Yosemite(OSX 10.10) Compatible</td><td class=\"SmallTitleCenter\">" . $YosCompatible . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">PC Information</td></tr>";
	echo "<tr><td>Technician Signature: __________________________</td><td>Date Completed: <span class=\"SmallTitleCenter\" >" . date("F d Y") . "</span></td></tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
}
?>