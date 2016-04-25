<?php
//Do not output anything to client before duplicate record checking, you will break the redirect on error if you do.
//Connect to MYSQL
include_once('info.php');
include_once('SaveRecord.php');
include_once('DisplayForm.php');

//Get the Information that the Tech Entered
$strEmployeeName = mysql_real_escape_string($_POST['frmEmployee']);
$strDepartmentName = mysql_real_escape_string($_POST['frmDepartment']);
$strRoomNum = mysql_real_escape_string($_POST['frmRoom']);
$strLocationName = mysql_real_escape_string($_POST['frmLocation']) . " " . $strRoomNum;
$strNotes = mysql_real_escape_string(CleanUpNotes($_POST['frmNotes']));
$strTech = mysql_real_escape_string($_POST['frmTech']);

//Dustin - Removed because we don't care what is installed
// $strInstall = mysql_real_escape_string(CleanUpNotes($_POST['frmInstallNotes']));

if (empty($strEmployeeName)){ //is employee name empty?
	header('Location: ./errors/Error_EmptyEmployee.html');
	exit();
}

if (empty($strRoomNum)){ //is Room number empty?
	header('Location: ./errors/Error_EmptyRoomNumber.html');
	exit();
}

//Break Employee Name into First and Last Name
$aNameParts = explode(' ',$strEmployeeName);
$strFName = $aNameParts[0];

if (count($aNameParts) > 1) //prevent sending client a warning
{
	$strLName = $aNameParts[1];
}

if ((empty($strFName)) || (empty($strLName))){ //yes, some people don't put the users full name
	header('Location: ./errors/Error_EmptyFNLN.html');
	exit();
}

$strTempFile = $_FILES['frmDataFile']['tmp_name'];
//$flFile = "review_" . time() . "_" . $strLocationName . ".txt";
$flFile = "tmpfiles/review_" . time() . "_" . $strLocationName . ".txt";
$iFileType = 0;

$strDomain = "None";
$strComputerName = "";
$strWindows = "";

//Dustin - Removing because unnecessary 
//$strAutoUpdates = "Previously, Auto Updates Were Turned Off";
//$strWebBrowser = "Other";
//$strIESecurityLevel = "Custom";
// $boolCDRom = false;
// $boolDVDRom = false;
// $iDisplayLine = -1;
// $iGraphicLine = -1;


$iProcessLine = -1;
$iHardDriveLine = -1;

//Dustin - Removing because unnecessary 
$iMemoryLine = -1;
$iSerialNumber = -1;
$iManucfacturer = "None";
$iModel = "None";
$iSystemType = "None";
$iJavaVersion = "None";

unset($aComputerLevel);
unset($aServiceLevels);
unset($aMeetsServiceLevels);

$strCurrentLevel = "";
//Open up the Service Level File are load in its properties
$aServiceLevelFiles = file("ServiceLevels.txt");
foreach ($aServiceLevelFiles as $iLine => $strLine)
{
	if (substr($strLine,0,3) == "***")  //This is the heading of a Service Level Section
	{
		unset($aMatch);
		preg_match("/\*\*\*(.*)\*\*\*/",$strLine,$aMatch);
		$strCurrentLevel = $aMatch[1];
	}
	else  //Otherwise this is a service level item
	{
		if (strlen(trim($strLine)) > 0) //Don't worry about blank lines
		{
			//split the item by the =>
			$aParts = explode('=>',$strLine);
			$aServiceLevels[$strCurrentLevel][trim($aParts[0])]  = trim($aParts[1]);	
		}
	}
}

if (file_exists($strTempFile))
{
	//echo "File Exists!<br />";
	if (move_uploaded_file($strTempFile, $flFile))
	{
		$aMainLines = file($flFile);
		//Check the First Line for OS Type
		//Preliminary Windows 8 support
		if (strripos($aMainLines[0], "10") != FALSE)
		{
			$iFileType = 1;
		}
		if (strripos($aMainLines[0], "8") != FALSE)
		{
			$iFileType = 1;
		}	
		if (strripos($aMainLines[0], "7") != FALSE)
		{
			$iFileType = 1;
		}		
		
		if (strripos($aMainLines[0], "Vista") != FALSE)
		{
			$iFileType = 1;
		}
		
		if (stripos($aMainLines[0], "XP") != FALSE)
		{
			$iFileType = 1;
		}
		
		if (stripos($aMainLines[0], "Mac OS X") != FALSE)
		{
			$iFileType = 2;
		}
		
		if (stripos($aMainLines[0], "OS X") != FALSE)
		{
			$iFileType = 2;
		}
		switch($iFileType)
		{
			case 1: //This is for XP, Windows Vista and 7
				//Lets get the Domain
				if (Search_File("admnet",$aMainLines) != -1)
				{
					$strDomain = "ADMNET";
				}
				

				//Dustin - Removing because OPENNET isn't a thing anymore
				// if (Search_File("opennet",$aMainLines) != -1)
				// {
				// 	$strDomain = "OPENNET";
				// }
							
				//Lets get the name, SerialNumber, Manufacturer, Model, System Type
				$iNameLine = Search_File("Machine Name:", $aMainLines);
				$iSerialNumber = Search_File("SerialNumber", $aMainLines);
				$iManucfacturer = Search_File("System Manufacturer:",$aMainLines);
				$iModel = Search_File("System Model:",$aMainLines);
				$iSystemType = Search_File("Operating System:", $aMainLines);

				unset($aMatches);
				preg_match("/System Name: (.*)/",$aMainLines[$iNameLine],$aMatches);
				$strComputerName = $aMatches[1];


				//Look for the Type of Windows
				//Preliminary Windows 8 support
				//Added Windows 10 support 4/13/2016
				if (Search_File("Version 10.0", $aMainLines) != -1)
				{
					$strWindows = "10";
				}
				if (Search_File("Version 6.3", $aMainLines) != -1)
				{
					$strWindows = "8.1";
				}
				if (Search_File("Version 6.2", $aMainLines) != -1)
				{
					$strWindows = "8";
				}
				
				if (Search_File("Version 6.1.7600", $aMainLines) != -1)
				{
					$strWindows = "7";
				}

				if (Search_File("Version 6.1.7601", $aMainLines) != -1)
				{
					$strWindows = "7 SP1";
				}
				//Preliminary 7 SP2 support
				if (Search_File("Version 6.1.7602", $aMainLines) != -1)
				{
					$strWindows = "7 SP2";
				}
				
				if (Search_File("Version 6.0", $aMainLines) != -1)
				{
					$strWindows = "Vista";
				}
				//Added support for Vista SP1
				if (Search_File("Version 6.0.6001", $aMainLines) != -1)
				{
					$strWindows = "Vista SP1";
				}
				//Added support for Vista SP2				
				if (Search_File("Version 6.0.6002", $aMainLines) != -1)
				{
					$strWindows = "Vista SP2";
				}
				
				if (Search_File("Version 5.1", $aMainLines) != -1)
				{
					$strWindows = "XP";
				}
				
				//Dustin - Removing because unnecessary 
				// if (Search_File("currently has automatic updates enabled", $aMainLines) != -1)
				// {
				// 	$strAutoUpdates = "Previously, Auto Updates Were Turned On";
				// }

				// if (Search_File("FIREFOX.EXE", $aMainLines) != -1)
				// {
				// 	$strWebBrowser = "Firefox";
				// }
				
				// if (Search_File("IEXPLORE.exe", $aMainLines) != -1)
				// {
				// 	$strWebBrowser = "Internet Explorer";
				// }
				
				// if (Search_File("IESECURITY:69632",$aMainLines) != -1)
				// {
				// 	$strIESecurityLevel = "Medium";
				// }
				
				// if (Search_File("IESECURITY:70912",$aMainLines) != -1)
				// {
				// 	$strIESecurityLevel = "Medium-High";
				// }
				
				// if (Search_File("IESECURITY:73728",$aMainLines) != -1)
				// {
				// 	$strIESecurityLevel = "High";
				// }
					

				//Now we need to get information about the Processor
				$iProcessorLine = Search_File("Processor:", $aMainLines);
				$iHardDriveLine = Search_File("Total Space:",$aMainLines);
				$iMemoryLine = Search_File("Memory:", $aMainLines);
				$iJavaVersion = Search_File("java version", $aMainLines);
				$aComputerLevel['Processor'] =  $aMainLines[$iProcessorLine];

				//Dustin - Removing because unnecessary - This is the resolution
				// $iDisplayLine = Search_File("Current Mode:", $aMainLines);
				// $iGraphicLine = Search_File("Chip type:", $aMainLines);
				

				//Dustin - Removing because unnecessary 
				// $aComputerLevel['Optical'] = "";
				
				// if (Search_File(" DVD",$aMainLines) != -1)
				// {
				// 	$aComputerLevel['Optical'] = "DVD";
				// if (Search_File(" CD", $aMainLines) != -1)
				// {
				// 	$aComputerLevel['Optical'] .= ", CD";
				// }
				// }
				// elseif (Search_File(" CD", $aMainLines) != -1)
				// {
				// 	$aComputerLevel['Optical'] = "CD";
				// }
				
				//Get the size of the hard drive from this line
				unset($aMatches);
				preg_match('/([0-9\.]+)/',$aMainLines[$iHardDriveLine],$aMatches);
				$aComputerLevel['HardDrive'] = $aMatches[1];
				
				//Get the Amount of Ram in the Computer
				unset($aMatches);
				preg_match('/([0-9\.]+)/',$aMainLines[$iMemoryLine],$aMatches);
				$aComputerLevel['RAM'] = $aMatches[1];
				
				//Dustin - Removing because unnecessary 
				// unset($aMatches);
				// $aComputerLevel['Display']=$iDisplayLine;
			
				//Dustin - Removing because unnecessary 
				// //Deal with the Graphics Memory
				// unset($aMatches);
				// preg_match('/([0-9\.]+)/',$aMainLines[$iGraphicLine],$aMatches);
		
				//HACK ALERT
				//The following is a total hack. Some of the Core i series processors report having "n/a" memory. As you know, n/a is not a number, which is unfortunate. I don't know of another way to check how much VRAM they have so I will give them the benifit of the doubt and assume they have enough to be Vista+ compaitible.
				// if ($aMainLines[$iGraphicLine] == "Display Memory: n/a");
				// {
				// 	$aMatches[1] = 1000;
				// }
				// //End Hack. That made me a tad sick.
				// $aComputerLevel['Graphic'] = $aMatches[1];
				
				//Run the three Checks
				if (Meets_Service_Level($aServiceLevels["Service Level"],$aComputerLevel))
				{
					$aMeetsServiceLevel["Service Level"] = "Yes";
				}
				else
				{
					$aMeetsServiceLevel["Service Level"] = "No";
				}
				
				if (Meets_Service_Level($aServiceLevels["7 Level"],$aComputerLevel))
				{
					$aMeetsServiceLevel["Vista Level"] = "Yes";
				}
				else
				{
					$aMeetsServiceLevel["Vista Level"] = "No";
				}
				

				//Dustin - Removing because I don't think we care what office level they have
				// if (Meets_Service_Level($aServiceLevels["Office 2013"],$aComputerLevel))
				// {
				// 	$aMeetsServiceLevel["Office 2013"] = "Yes";
				// }
				// else
				// {
				// 	$aMeetsServiceLevel["Office 2013"] = "No";
				// }


				unset($aMatches);
				list($junk, $aMatches[1]) = explode(': ',$aMainLines[$iProcessorLine]);
				$aComputerLevel['Processor'] =  $aMatches[1];
			
				//sometimes the variables have whitespace, this causes problems
				$strComputerName = trim($strComputerName);
				$strLocationName = trim($strLocationName);
				$strDomain = trim($strDomain);
				//Get the size of the hard drive from this line
				unset($aMatches);
				list($junk, $aMatches[1]) = explode(': ',$aMainLines[$iHardDriveLine]);
				$aComputerLevel['HardDrive'] = $aMatches[1];
				
				//Get the Amount of Ram in the Computer
				unset($aMatches);
				list($junk, $aMatches[1]) = explode(': ',$aMainLines[$iMemoryLine]);
				$aComputerLevel['RAM'] = $aMatches[1];
				unset($aMatches);
				
				unset($aMatches);
				list($junk, $aMatches[1]) = explode(': ',$aMainLines[$iDisplayLine]);
				$aComputerLevel['Display']= $aMatches[1];

				unset($aMatches);
				list($junk, $aMatches[1]) = explode(': ',$aMainLines[$iGraphicLine]);			
				$aComputerLevel['Graphic'] = $aMatches[1];
				
				//Dustin - Removing because unnecessary 
				//Exile OPENNET computers
				// if ($strDomain == "OPENNET"){
				// 		header('Location: ./errors/Error_OPENNET.html');
				// 		exit();
				// }
					
				//Check if duplicate record exists by ComputerName, Domain and Current Year
				$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . $strComputerName . "' AND Domain='" . $strDomain . "' AND ReviewYear='" . date("Y") ."'";
				//echo $query_string . "<br>";
				$query = mysql_query($query_string);	
				//This will stay '0' if nothing matches as ReviewId should never be 0.
				$LastId=0;
				while($row = mysql_fetch_array($query))
				{
					$LastId = $row[0];
				}
			
				if ($LastId != 0)
				{ 	//If not 0 then a duplicate exists
					if ($strDomain != "None")
					{
						$result = mysql_query("DELETE FROM ReviewData WHERE ReviewId='" . $LastId . "'"); //Remove previous matching record. This should be safe
					}
					else
					{ //To conflict resolution with love
						echo "<html>";
						echo "<head><title>Redirecting to Conflict Resolution</title></head>";
						echo "<body>";
						echo "<form action='ConflictResolution.php' method='post' name='ConflictResolutionForm'>";
						echo "<input type='hidden' name='FName' value='" . $strFName . "'>";
						echo "<input type='hidden' name='LName' value='" . $strLName . "'>";
						echo "<input type='hidden' name='Department' value='" . $strDepartmentName . "'>";
						echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
						echo "<input type='hidden' name='WindowsVersion' value='" . $strWindows . "'>";
						// echo "<input type='hidden' name='WebBrowser' value='" . $strWebBrowser . "'>";
						echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
						// echo "<input type='hidden' name='IESecurityLevel' value='" . $strIESecurityLevel . "'>";
						echo "<input type='hidden' name='MeetsSLA' value='" . $aMeetsServiceLevel["Service Level"] . "'>";
						echo "<input type='hidden' name='MeetsVSLA' value='" . $aMeetsServiceLevel["Vista Level"] . "'>";
						echo "<input type='hidden' name='MeetsOSLA' value='" . $aMeetsServiceLevel["Office 2013"] . "'>";
						
						//Dustin - Removing because unnecessary 
						// echo "<input type='hidden' name='Install' value='" . $strInstall . "'>";
						// echo "<input type='hidden' name='Notes' value='" . $strNotes . "'>";
						// echo "<input type='hidden' name='WindowsUpdate' value='Yes'>";
						// echo "<input type='hidden' name='AutomaticUpdates' value='Yes'>";
						// echo "<input type='hidden' name='VirusDefinitions' value='Yes'>";
						// echo "<input type='hidden' name='SpywareScan' value='Yes'>";
						echo "<input type='hidden' name='Tech' value='" . $strTech . "'>";
						echo "<input type='hidden' name='ComputerName' value='" . $strComputerName . "'>";
						echo "<input type='hidden' name='Domain' value='" . $strDomain . "'>";
						echo "<input type='hidden' name='LionCompatible' value='N/A'>";
						echo "<input type='hidden' name='OST' value='WIN'>"; //Pass OS Type to conflict resolution for use with form generation
						echo "<input type=\"submit\" value=\"If you see this something bad happened. Press me please\"/>"; //if auto submission fails
						echo "</form>";
						echo "<script language=\"JavaScript\">"; 
						echo "document.ConflictResolutionForm.submit();"; //make the form submit itself
						echo "</script>";
						echo "</body>";
						echo "</html>";
						exit(); //prevent further execution of this script
					}
				}
				//And now for something completely different. Lets make a web page!

				//Dustin - Old 
				// DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strWebBrowser, $strIESecurityLevel, $aMeetsServiceLevel["Service Level"], $aMeetsServiceLevel["Vista Level"], $aMeetsServiceLevel["Office 2013"], $strInstall, $strNotes);
				DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strWebBrowser, $strIESecurityLevel, $aMeetsServiceLevel["Service Level"], $aMeetsServiceLevel["Vista Level"], $aMeetsServiceLevel["Office 2013"], $strInstall, $strNotes);
				
				

				//Run the SQL Statement
				SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, "Yes", "Yes", "Yes", "Yes", $strWebBrowser, $strInstall, $aMeetsServiceLevel["Service Level"], $aMeetsServiceLevel["Vista Level"], $aMeetsServiceLevel["Office 2013"], $strNotes, $strTech, "N/A", $aComputerLevel['Processor'], $aComputerLevel['HardDrive'], $aComputerLevel['Optical'], $aComputerLevel['RAM'], $aComputerLevel['Graphic'], $aComputerLevel['Display']);
			break;
			
			case 2: //This is for OS X
				//Which Type of OS X are we dealing with...It seems that each version outputs a different file
				$strOS = "Too Old";
				$strProcessor = "Too Old";
				$iHardDrive = 0;
				$strOptical = "";
				$iMemory = 0;
				$strComputerName = "";
				$strSav = "No";
				$OSXVer = "0";
				if (strripos($aMainLines[0],"10.10")) //Fun with Yosemite
				{
					$VerIndex = strripos($aMainLines[0],"10.10");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.10 Yosemite (" . $OSXVer . ")";
					//$aMeetsServiceLevel["LionCompatible"] = "Yes"; //Dont even bother testing
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("CPU Type:", $aMainLines);
						preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Check for Ram Size
					$iMemoryLine = Search_File(" Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
					$iMemory = $aMatches[1];
					if ($aMatches[2] == "GB")
					{
						$iMemory = $iMemory * 1000;
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
			
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";
					}
				}
				else if (strripos($aMainLines[0],"10.9")) //Fun with Mavericks
				{
					$VerIndex = strripos($aMainLines[0],"10.9");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.9 Mavericks (" . $OSXVer . ")";
					//$aMeetsServiceLevel["LionCompatible"] = "Yes"; //Dont even bother testing
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("CPU Type:", $aMainLines);
						preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Check for Ram Size
					$iMemoryLine = Search_File(" Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
					$iMemory = $aMatches[1];
					if ($aMatches[2] == "GB")
					{
						$iMemory = $iMemory * 1000;
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
			
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";
					}
				}
				else if (strripos($aMainLines[0],"10.8")) //Fun with Mountain Lion
				{
					$VerIndex = strripos($aMainLines[0],"10.8");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.8 Mountian Lion (" . $OSXVer . ")";
					//$aMeetsServiceLevel["LionCompatible"] = "Yes"; //Dont even bother testing
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("CPU Type:", $aMainLines);
						preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Check for Ram Size
					$iMemoryLine = Search_File(" Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
					$iMemory = $aMatches[1];
					if ($aMatches[2] == "GB")
					{
						$iMemory = $iMemory * 1000;
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
			
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";
					}
				}

				else if (strripos($aMainLines[0],"10.7")) //Fun with Lion
				{
					$VerIndex = strripos($aMainLines[0],"10.7");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.7 Lion (" . $OSXVer . ")";
					//$aMeetsServiceLevel["LionCompatible"] = "Yes"; //Dont even bother testing
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("CPU Type:", $aMainLines);
						preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Check for Ram Size
					$iMemoryLine = Search_File(" Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
					$iMemory = $aMatches[1];
					if ($aMatches[2] == "GB")
					{
						$iMemory = $iMemory * 1000;
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
			
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";
					}
				}
				else if (strripos($aMainLines[0],"10.6")) //Fun with Snow Leopard
				{
					//Get specific OSX version
					$VerIndex = strripos($aMainLines[0],"10.6");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.6 Snow Leopard (" . $OSXVer . ")";
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("CPU Type:", $aMainLines);
						preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Check for Ram Size
					$iMemoryLine = Search_File(" Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
					$iMemory = $aMatches[1];
					if ($aMatches[2] == "GB")
					{
						$iMemory = $iMemory * 1000;
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
			
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";
					}
				}
				else if (strripos($aMainLines[0],"10.5")) //Fun with Leopard, This OS is no longer supported 										
				{										  //The memory section was removed to cause a failure
					//Get detailed OS version
					$VerIndex = strripos($aMainLines[0],"10.5");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.5 Leopard (" . $OSXVer . ")";
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("CPU Type:", $aMainLines);
						preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
					
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";	
					}	
				}
				else if (strripos($aMainLines[0],"10.4")) //Fun with Tiger, This OS is no longer supported 										
				{										  //The memory section was removed to cause a failure
					//Get detailed OS version
					$VerIndex = strripos($aMainLines[0],"10.4");
					$OSXVer = substr($aMainLines[0], $VerIndex, 7);
					$OSXVer = trim($OSXVer);
					$strOS = "10.4 Tiger (" . $OSXVer . ")";
					
					//Get the Processor
					$iProcessorLine = Search_File("CPU Type:", $aMainLines);
					unset($aMatches);
					if ($iProcessorLine ==-1)
					{
						$iProcessorLine = Search_File("Processor Name:", $aMainLines);
						preg_match("/Name: ([^\(\)]+)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					else
					{
						preg_match("/Type: ([^\(\)]+)/",$aMainLines[$iProcessorLine],$aMatches);
					}
					$strProcessor = $aMatches[1];
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Machine Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Get the Hard Drive
					$iDriveLine = Search_File("/dev/",$aMainLines);
					unset($Matches);
					preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
					
					//Get Info About the Optical Drive
					$strOptical = "CD";
					if (Search_File("DVD",$aMainLines) != -1)
					{
						$strOptical = "DVD";			
					}
				}
				
				//Check for Symantec Running on this Computer
				if (Search_File("SAV RUN",$aMainLines) != -1)
				{
					$strSav = "Yes";
				}
				
				$aComputerLevel["Processor"] = $strProcessor;
				$aComputerLevel["RAM"] = $iMemory;
				$aComputerLevel["HardDrive"] = $iHardDrive;
				$aComputerLevel["OSX"] = $OSXVer;

				//Dustin - Removing because unnecessary 
				// $aComputerLevel["Optical"] = $strOptical;
				
				if (Meets_Service_Level($aServiceLevels["Mac 2009"],$aComputerLevel))
				{		
					$aMeetsServiceLevel["Mac 2009"] = "Yes";
					$aMeetsServiceLevel["LionCompatible"] = "Yes";
				}
				else
				{
					$aMeetsServiceLevel["Mac 2009"] = "No";
					$aMeetsServiceLevel["LionCompatible"] = "No";
				}

				
				//Check if duplicate record exists by ComputerName, Domain and current year
				$strComputerName = trim($strComputerName);
				$strLocationName = trim($strLocationName);
				$strDomain = "None";
			
				if ($strComputerName == "")
				{
					header('Location: ./errors/Error_Unsupported.html');
					exit();
				}
			
				$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . addslashes($strComputerName) . "' AND ReviewYear='" . date("Y") ."'";
				$query = mysql_query($query_string);	
				//This will stay '0' if nothing matches as ReviewId should never be 0.
				$LastId=0;
				while($row = mysql_fetch_array($query))
				{
					$LastId = $row[0];
				}
				//echo $LastId;
				if ($LastId != 0)
				{ //If not 0 then a duplicate exists
				  //To conflict resolution with love
					echo "<html>";
					echo "<head><title>Redirecting to Conflict Resolution</title></head>";
					echo "<body>";
					echo "<form action='ConflictResolution.php' method='post' name='ConflictResolutionForm'>";
					echo "<input type='hidden' name='FName' value='" . $strFName . "'>";
					echo "<input type='hidden' name='LName' value='" . $strLName . "'>";
					echo "<input type='hidden' name='Department' value='" . $strDepartmentName . "'>";
					echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
					echo "<input type='hidden' name='WindowsVersion' value='" . $strOS . "'>";
					echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
					echo "<input type='hidden' name='MeetsSLA' value='" . $aMeetsServiceLevel["Mac 2009"] . "'>";
					
					//Dustin - Removing because unnecessary 
					// echo "<input type='hidden' name='Install' value='" . $strInstall . "'>";
					// echo "<input type='hidden' name='Notes' value='" . $strNotes . "'>";
					// echo "<input type='hidden' name='WindowsUpdate' value='Yes'>";
					// echo "<input type='hidden' name='AutomaticUpdates' value='Yes'>";
					// echo "<input type='hidden' name='VirusDefinitions' value='Yes'>";
					// echo "<input type='hidden' name='SpywareScan' value='" . $strSav ."'>";


					echo "<input type='hidden' name='Tech' value='" . $strTech . "'>";
					echo "<input type='hidden' name='ComputerName' value='" . urlencode($strComputerName) . "'>";
					echo "<input type='hidden' name='Domain' value='" . $strDomain . "'>";
					echo "<input type='hidden' name='LionCompatible' value='" . $aMeetsServiceLevel["LionCompatible"] . "'>";
					echo "<input type='hidden' name='OST' value='MAC'>"; //Pass OS Type to conflict resolution
					echo "<input type=\"submit\" value=\"If you see this something bad happened. Press me please\"/>"; //if auto submission fails
					echo "</form>";
					echo "<script language=\"JavaScript\">";
					echo "document.ConflictResolutionForm.submit();"; //make the form submit itself
					echo "</script>";
					echo "</body>";
					echo "</html>";
					exit(); //prevent further execution of this script
				}
				DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strOS, $aMeetsServiceLevel["Mac 2009"], $strInstall, $strNotes, $strSav, $aMeetsServiceLevel["LionCompatible"]);
				SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, "None", $strOS, "Yes", "Yes", $strSav , "No","N/A", $strInstall, $aMeetsServiceLevel["Mac 2009"], "N/A", "N/A", $strNotes, $strTech, $aMeetsServiceLevel["LionCompatible"], "N/A", "N/A", "N/A", "N/A", "N/A", "N/A");
		}
	}
}
else
{	
	header('Location: ./errors/Error_NoFileAttached.html');
}

function Search_File($strSearchTerm,$aLines)
{
	$iTermRow = -1;
	//Here is the inefficent part
	foreach ($aLines as $iLineNum => $strLine)
	{
		//echo "Looking for " . $strSearchTerm in 
			if (strripos($strLine, $strSearchTerm) === false)
			{
				//this seems stupid to have....I'll figure out something better someday
			}
			else
			{
				if ($iTermRow == -1) //I think I only want to check for the first  thing
				{
					$iTermRow = $iLineNum;
				}
			}
	}
	return $iTermRow;
}

function Meets_Service_Level($aServiceLevel,$aComputerLevel)
{
	//echo "------------------------------------------------------<br />";
	$boolMeetsLevel = true;
	$aSearchCats = array("Processor","Optical");
	$aCompareCats = array("HardDrive","RAM", "Graphic", "OSX");
	$aNotFindCats = array("Display");
	
	foreach ($aServiceLevel as $strTerm => $strValues)
	{
			//echo "*****" . $strTerm . "<br />";
		if (!isset($aComputerLevel[$strTerm]) || $aComputerLevel[$strTerm] == -1)
		{
			$boolMeetsLevel = false;  //The computer does not have the device defined....This machine must be a piece of crap
		}
		else
		{
			$boolRequirementMet = false;
			//Determine which type of check to make
			if (in_array($strTerm,$aSearchCats))  //Is this the type of operation to search for a term
			{
				//Break up the values into an array
				$aValues = explode(',',$strValues);
				foreach ($aValues as $strValue) //go through each of the values and see if it fits
				{
					//echo "Looking for " . $strValue . " in " . $aComputerLevel[$strTerm] . "<br />";
					if (strripos($aComputerLevel[$strTerm],$strValue) === false)
					{
					}
					else
					{
						//echo "Found: " . $strValue . "<br />";
						$boolRequirementMet = true;
					}
				}
			}
			
			if (in_array($strTerm,$aCompareCats)) //Is this the type of operation to compare values
			{
				$strValue = $aServiceLevel[$strTerm];

				//echo "Term: " . $strTerm . " - " . $aComputerLevel[$strTerm] . " >= " . $strValue . "<br />";
				if ($aComputerLevel[$strTerm] >= $strValue)
				{
					$boolRequirementMet = true;
				}
			}
			
			if (in_array($strTerm,$aNotFindCats)) //Is this the type of operation where we do not want to find drives
			{
				
				$boolRequirementMet = true;
				//Break up the values into an array
				$aValues = explode(',',$strValues);
				foreach ($aValues as $strValue) //go through each of the values and see if it fits
				{
					if (strripos($aComputerLevel[$strTerm],$strValue))
					{
						$boolRequirementMet = false;
					}
				}
			}
			if (!$boolRequirementMet)
			{
				$boolMeetsLevel = false; ////Epic Fail
			}
			
		}
	}
	return $boolMeetsLevel;
}

function CleanUpNotes($strNotes)
{
	//replace the different types of line breaks
	$strNotes = preg_replace('/\\r\\n/','<br />',$strNotes);
	
	$strNotes = preg_replace('/\\n/','<br />', $strNotes);
	
	return $strNotes;
}

?> 