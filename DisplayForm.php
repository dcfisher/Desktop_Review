<?php
//Lets Generate the Fancy Form
//Dustin - Old function def, updated 4/13/2016
// function DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strWebBrowser, $strIESecurityLevel, $SLA, $VSLA, $OSLA, $strInstall, $strNotes)
function DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $SLA, $VSLA, $OSLA, $strInstall, $strNotes)
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
	echo "<tr><td>Windows: </td><td class=\"SmallTitleCenter\" >" . $strWindows . "</td></tr>";
	// echo "<tr><td>Windows Update Performed:</td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Automatic Updates:</td><td class=\"SmallTitleCenter\" ><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Virus Definitions Updated: </td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Spyware Scan and Removal: </td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	// echo "<tr><td>Web Browser: </td><td class=\"SmallTitleCenter\">" . $strWebBrowser . "</td></tr>";
	// echo "<tr><td>IE Security Level </td><td class=\"SmallTitleCenter\">" . $strIESecurityLevel . "</td></tr>";
	echo "<tr><td>Does it meet Desktop Service Agreement? </td><td class=\"SmallTitleCenter\">" . $SLA . "</td></tr>";
	echo "<tr><td>Windows 7 Compatible? </td><td class=\"SmallTitleCenter\">" . $VSLA . "</td></tr>";
	// echo "<tr><td>Office 2013 Compatible? </td><td class=\"SmallTitleCenter\">" . $OSLA . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">PC Information</td></tr>";
	echo "<tr><td class=\"Border\" colspan=\"2\"   ><span class=\"SmallTitle\">Notes:</span><br /><p style=\"margin-bottom:25px;\">" . $strInstall . "<br />" . $strNotes . "</p></td></tr>";
	echo "<tr><td>Technician Signature: __________________________</td><td>Date Completed: <span class=\"SmallTitleCenter\" >" . date("F d Y") . "</span></td></tr>";
	echo "<tr><td><a href=\"https://docs.google.com/a/oakland.edu/forms/d/1m43vdBk8qgXm-wRS6wrRpbOUgP8IcL97bWQx9R3i9hw/viewform?usp=send_form\">Enter Serial Number Information</a><td><tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
}
function DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strOS, $SLA, $strInstall, $strNotes, $strSav, $LionCompatible)
{
	if ($LionCompatible == "")
	{
		$LionCompatible = "No";
	}
	$strExtra = "";
	if ($strSav == "Yes")
	{
		$strExtra = " checked=\"checked\" ";
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
	echo "<tr><td>Mac OS Version:</td><td class=\"SmallTitleCenter\">" . $strOS . "</td></tr>";
	echo "<tr><td>Software Update Performed</td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" checked=\"checked\" disabled=\"disabled\" /></td></tr>";
	echo "<tr><td>Sav Updates Performed</td><td class=\"SmallTitleCenter\"><input type=\"checkbox\" " . $strExtra . " disabled=\"disabled\" /></td></tr>";
	echo "<tr><td>Does This Computer Meet Mac Service Level</td><td class=\"SmallTitleCenter\">" . $SLA . "</td></tr>";
	echo "<tr><td>Is This Computer Yosemite(OSX 10.10) Compatible</td><td class=\"SmallTitleCenter\">" . $LionCompatible . "</td></tr>";
	echo "<tr><td class=\"FormHeader\" colspan=\"2\">PC Information</td></tr>";
	echo "<tr><td class=\"Border\" colspan=\"2\"   ><span class=\"SmallTitle\">Notes:</span><br /><p style=\"margin-bottom:25px;\">" . $strInstall . "<br />" . $strNotes . "</p></td></tr>";
	echo "<tr><td>Technician Signature: __________________________</td><td>Date Completed: <span class=\"SmallTitleCenter\" >" . date("F d Y") . "</span></td></tr>";
	echo "<tr><td><a href=\"https://docs.google.com/a/oakland.edu/forms/d/1m43vdBk8qgXm-wRS6wrRpbOUgP8IcL97bWQx9R3i9hw/viewform?usp=send_form\">Enter Serial Number Information</a><td><tr>";
	echo "</table>";
	echo "</body>";
	echo "</html>";
}
?>