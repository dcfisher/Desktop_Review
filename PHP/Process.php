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

$strTech = mysql_real_escape_string($_POST['frmTech']);


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




$iProcessorLine = -1;
$iHardDriveLine = -1;

//Dustin - Removing because unnecessary 
$iMemoryLine = -1;
$iManucfacturer = -1;
$iSerialNumber = -1;
$iModel = -1;
$iJavaVersion = -1;
$iOfficeMatch = -1;

$strProcessor = "None";
$strHDD = "None";
$strRAM = 0;
$strSerialNumber = "None";
$strManufacturer = "None";
$strModel = "None";
$strWindowsVersion = "None";
$strJavaVersion = "None";
$strOfficeMatch = "None";

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
				

							
				//Lets get the name, SerialNumber, Manufacturer, Model, System Type
				$iNameLine = Search_File("System Name:", $aMainLines);
				$iSerialNumber = Search_File("Serial Number:", $aMainLines);
				$iManucfacturer = Search_File("System Manufacturer:",$aMainLines);
				$iModel = Search_File("System Model:",$aMainLines);
				$iOfficeMatch = Search_File("Office Match:", $aMainLines);

				unset($aMatches);
				preg_match("/System Name: (.*)/",$aMainLines[$iNameLine],$aMatches);
				$strComputerName = $aMatches[1];

				unset($aMatches);
				preg_match("/Serial Number: (.*)/",$aMainLines[$iSerialNumber],$aMatches);
				$strSerialNumber = $aMatches[1];

				unset($aMatches);
				preg_match("/System Manufacturer: (.*)/",$aMainLines[$iManucfacturer],$aMatches);
				$strManufacturer = $aMatches[1];

				unset($aMatches);
				preg_match("/System Model: (.*)/",$aMainLines[$iModel],$aMatches);
				$strModel = $aMatches[1];

				unset($aMatches);
				preg_match("/Office Match: (.*)/",$aMainLines[$iOfficeMatch],$aMatches);
				$strOfficeMatch = $aMatches[1];


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
				
		
					

				//Now we need to get information about the Processor
				$iProcessorLine = Search_File("Processor:", $aMainLines);
				$iHardDriveLine = Search_File("HDD:",$aMainLines);
				$iMemoryLine = Search_File("Memory:", $aMainLines);
				$iJavaVersion = Search_File("Java Version:", $aMainLines);
				$aComputerLevel['Processor'] = $aMainLines[$iProcessorLine];
				
				unset($aMatches);
				preg_match("/Processor: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
				$strProcessor = $aMatches[1];
				$aComputerLevel['Processor'] = $strProcessor;

				unset($aMatches);
				preg_match("/HDD: (.*)/",$aMainLines[$iHardDriveLine],$aMatches);
				$strHDD = $aMatches[1];
				// $aComputerLevel['HardDrive'] = $aMainLines[$iHardDriveLine];

				unset($aMatches);
				preg_match("/Memory: (.*)/",$aMainLines[$iMemoryLine],$aMatches);
				$strRAM = $aMatches[1];
				$strRAM = round($strRAM);

				// $aComputerLevel['RAM'] = $aMainLines[$iMemoryLine];

				unset($aMatches);
				preg_match("/Java Version: (.*)/",$aMainLines[$iJavaVersion],$aMatches);
				$strJavaVersion = $aMatches[1];

				
				
				//Run the three Checks
				if (Meets_Service_Level($aServiceLevels["Service Level"],$aComputerLevel))
				{
					$aMeetsServiceLevel["Service Level"] = "Yes";
					$aMeetsServiceLevel["Win10"] = "Yes";
					$aMeetsServiceLevel["Office 2016"] = "Yes";
				}
				else
				{
					$aMeetsServiceLevel["Service Level"] = "No";
					$aMeetsServiceLevel["Win10"] = "No";
					$aMeetsServiceLevel["Office 2016"] = "No";
				}
				
				
			
				//sometimes the variables have whitespace, this causes problems
				$strComputerName = trim($strComputerName);
				$strLocationName = trim($strLocationName);
				$strDomain = trim($strDomain);
				
				//Check if duplicate record exists by ComputerName, Domain and Current Year
				$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . addslashes($strComputerName) . "' AND ReviewYear='" . date("Y") ."' AND FName='" . $strFName . "' AND LName='" . $strLName . "' AND Location='" . $strLocationName . "'";
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
				DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strManufacturer, $strModel, $strSerialNumber, $strRAM, $strHDD, $strOfficeMatch, $strProcessor, $strJavaVersion, $aMeetsServiceLevel["Service Level"], $aMeetsServiceLevel["Win10"], $aMeetsServiceLevel["Office 2016"]);
				
				SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strManufacturer, $strSerialNumber, $strModel, $strJavaVersion, $aMeetsServiceLevel["Service Level"],$aMeetsServiceLevel["Win10"], $aMeetsServiceLevel["Office 2016"], $strTech, "N/A", $strProcessor, $strHDD, $strRAM, $strOfficeMatch);

				
			break;
			
			case 2: //This is for OS X
				//Which Type of OS X are we dealing with...It seems that each version outputs a different file
				$strOS = "Too Old";
				$strProcessor = "Too Old";
				$iHardDrive = 0;
				$iMemory = 0;
				$strComputerName = "";
				$strSerNum = "None";
				$iSerNumLine = -1;
				$boolMemCheck = false;
				$strProcSpeed = "None";
				$iSpeedLine = -1;
				$iJavaLine = -1;
				$strJavaVer = "None";
				



				if (strripos($aMainLines[0],"10.11") || strripos($aMainLines[0],"10.10") || strripos($aMainLines[0],"10.9") || strripos($aMainLines[0],"10.8") || strripos($aMainLines[0],"10.7") || strripos($aMainLines[0],"10.6")) //Fun with Yosemite
				{

					$VerIndex = strripos($aMainLines[0],"10.");
					$strOS = substr($aMainLines[0], $VerIndex - 3, 7);
					$strOS = trim($strOS);
					
					//Get the Processor
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					preg_match("/Processor Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					$strProcessor = $aMatches[1];
					$aComputerLevel['Processor'] = $strProcessor;
					
					//Check for a Computer Name
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Computer Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					//Check for Ram Size
					$iMemoryLine = Search_File("Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
					$iMemory = $aMatches[1];
					
					if($aMatches[1] >= 2)
					{
						$boolMemCheck = true;
					}
					$iMemory .= " GB";
					
					//Get the Hard Drive
					$iDriveLine = Search_File("Capacity:",$aMainLines);
					unset($Matches);
					preg_match("/Capacity: (.*)/",$aMainLines[$iDriveLine], $aMatches);
					$iHardDrive = $aMatches[1];
					$iHardDrive = substr($iHardDrive,0,9);
					
					$iSerNumLine = Search_File("Serial Number ",$aMainLines);
					unset($aMatches);
					preg_match("/Serial Number (.*)/", $aMainLines[$iSerNumLine], $aMatches);
					$strSerNum = $aMatches[1];
					$strSerNum = substr($strSerNum,10);
					$strSerNum = trim($strSerNum);

					$iSpeedLine = Search_File("Processor Speed:",$aMainLines);
					unset($Matches);
					preg_match("/Processor Speed: (.*)/",$aMainLines[$iSpeedLine], $aMatches);
					$strProcSpeed = $aMatches[1];

					$iJavaLine = Search_File("java version",$aMainLines);
					unset($Matches);
					preg_match("/java version (.*)/",$aMainLines[$iJavaLine], $aMatches);
					$strJavaVer = $aMatches[1];
					$strJavaVer = trim($strJavaVer,'"');

					$iModel = Search_File("Model Name:",$aMainLines);
					unset($Matches);
					preg_match("/Model Name: (.*)/",$aMainLines[$iModel], $aMatches);
					$strModel = $aMatches[1];

					if (Meets_Service_Level($aComputerLevel))
					{		
						$aMeetsServiceLevels["OSX"] = "Yes";
						$aMeetsServiceLevels["YosCompatible"] = "Yes";
					}
					else
					{
						$aMeetsServiceLevels["OSX"] = "No";
						$aMeetsServiceLevels["YosCompatible"] = "No";
					}
				}
				
				// $strProcessor = trim($strProcessor);
				// $aComputerLevel["Processor"] = $strProcessor;
				
				// if (Meets_Service_Level($aServiceLevels["OSX"],$aComputerLevel))
				

				
				//Check if duplicate record exists by ComputerName, Domain and current year
				$strComputerName = trim($strComputerName);
				$strLocationName = trim($strLocationName);
				$strDomain = "None";
			
				if ($strComputerName == "")
				{
					header('Location: ./errors/Error_Unsupported.html');
					exit();
				}
			
				$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . addslashes($strComputerName) . "' AND ReviewYear='" . date("Y") ."' AND FName='" . $strFName . "' AND LName='" . $strLName . "' AND Location='" . $strLocationName . "'";
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
					exit(); //prevent further execution of this script
				}
				

				//Dustin - Combining processor and speed
				$strProcessor .= " " . $strProcSpeed;

				DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strOS, $iMemory, $iHardDrive, $strProcessor, $strJavaVer, $strSerNum, $aMeetsServiceLevels["YosCompatible"], $aMeetsServiceLevels["OSX"]);

				SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, "None", $strOS, "Apple", $strSerNum, $strModel, $strJavaVer, $aMeetsServiceLevels["OSX"], "N/A", "N/A", $strTech , $aMeetsServiceLevels["YosCompatible"], $strProcessor, $iHardDrive, $iMemory, "N/A");
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

//Dustin - Reference
// Meets_Service_Level($aServiceLevels["Service Level"],$aComputerLevel)

function Meets_Service_Level($slaDoc,$findMe)
{
	$processorArray = array("AMD A4","AMD A6","AMD A8","AMD A10","Core(TM)2","Core(TM)2 Quad","Pentium(R) 4","Pentium(R) D","Pentium®","Core(TM) i3","Core(TM) i5","Core(TM) i7","Pentium(R) Dual","Turion(tm) 64","AMD Phenom(tm)","Intel Xeon","Atom(TM)","Sempron(tm)","Athlon(tm) 64","Athlon(tm) 64 Dual Core","Athlon(tm) Dual Core","Pentium(R) M","Turion(tm) X2","Turion(tm) 64 X2", "Celeron(R)", "Athlon(tm) II","Intel(R) Xeon(R)", "Core 2","Intel Core i3","Intel Core i5","Intel Core i7","Intel Core 2 Duo","Intel Xeon");

	foreach ($processorArray as $value)
	{
		if(strripos($findMe['Processor'],$value))
		{
			return true;
		}
		
	}
	return false;
}


// function Meets_Service_Level($aServiceLevel,$aComputerLevel)
// {
// 	//echo "------------------------------------------------------<br />";
// 	$boolMeetsLevel = true;
// 	$aSearchCats = array("Processor");
// 	$aCompareCats = array("HardDrive","RAM", "OSX");
// 	// $aNotFindCats = array("Display");
	
// 	//Dustin - Reference
// 	// Meets_Service_Level($aServiceLevels["Service Level"],$aComputerLevel)

// 	foreach ($aServiceLevel as $strTerm => $strValues)
// 	{
// 			//echo "*****" . $strTerm . "<br />";
// 		if (!isset($aComputerLevel[$strTerm]) || $aComputerLevel[$strTerm] == -1)
// 		{
// 			$boolMeetsLevel = false;  //The computer does not have the device defined....This machine must be a piece of crap
// 		}
// 		else
// 		{
// 			$boolRequirementMet = false;
// 			//Determine which type of check to make
// 			if (in_array($strTerm,$aSearchCats))  //Is this the type of operation to search for a term
// 			{
// 				//Break up the values into an array
// 				$aValues = explode(',',$strValues);
// 				foreach ($aValues as $strValue) //go through each of the values and see if it fits
// 				{
// 					//echo "Looking for " . $strValue . " in " . $aComputerLevel[$strTerm] . "<br />";
// 					if (strripos($aComputerLevel[$strTerm],$strValue) == false)
// 					{
// 					}
// 					else
// 					{
// 						//echo "Found: " . $strValue . "<br />";
// 						$boolRequirementMet = true;
// 					}
// 				}
// 			}
			
// 			//Dustin - Ignoring for now, making sure the processor SLA works
// 			// if (in_array($strTerm,$aCompareCats)) //Is this the type of operation to compare values
// 			// {
// 			// 	$strValue = $aServiceLevel[$strTerm];

// 			// 	//echo "Term: " . $strTerm . " - " . $aComputerLevel[$strTerm] . " >= " . $strValue . "<br />";
// 			// 	if ($aComputerLevel[$strTerm] >= $strValue)
// 			// 	{
// 			// 		$boolRequirementMet = true;
// 			// 	}
// 			// }
			
// 			// Dustin - 
// 			// if (in_array($strTerm,$aNotFindCats)) //Is this the type of operation where we do not want to find drives
// 			// {
				
// 			// 	$boolRequirementMet = true;
// 			// 	//Break up the values into an array
// 			// 	$aValues = explode(',',$strValues);
// 			// 	foreach ($aValues as $strValue) //go through each of the values and see if it fits
// 			// 	{
// 			// 		if (strripos($aComputerLevel[$strTerm],$strValue))
// 			// 		{
// 			// 			$boolRequirementMet = false;
// 			// 		}
// 			// 	}
// 			// }
// 			// if (!$boolRequirementMet)
// 			// {
// 			// 	$boolMeetsLevel = false; ////Epic Fail
// 			// }
			
// 		}
// 	}
// 	return $boolMeetsLevel;
// }

function CleanUpNotes($strNotes)
{
	//replace the different types of line breaks
	$strNotes = preg_replace('/\\r\\n/','<br />',$strNotes);
	
	$strNotes = preg_replace('/\\n/','<br />', $strNotes);
	
	return $strNotes;
}

?> 