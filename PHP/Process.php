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
$flFile = "tmpfiles/review_" . time() . "_" . $strLocationName . ".txt";
$iFileType = 0;

// Dustin - these variables store the line numbers used below. They will probably disappear
// when I change how we gather info
$iProcessorLine = -1;
$iHardDriveLine = -1;
$strRAMLine = -1;
$iManucfacturer = -1;
$iSerialNumber = -1;
$iModel = -1;
$iJavaVersion = -1;
$iOfficeMatch = -1;

// Dustin - Variables for what we actually want
$boolMemCheck = false;
$strProcSpeed = "None";
$iSpeedLine = -1;
$strOS = "None";
$strDomain = "None";
$strComputerName = "";
$strWindows = "";
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

if (file_exists($strTempFile))
{
	if (move_uploaded_file($strTempFile, $flFile))
	{
		$aMainLines = file($flFile);
		//Check the First Line for OS Type
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

				// Dustin - Okay, not my favorite way to do this and I will look at changing it but for now 
				// this block searches through the review.txt and finds which line the search term is on.
				// For example $iNameLine holds the line number where "System Name:" is located.
				$iNameLine = Search_File("System Name:", $aMainLines);
				$iSerialNumber = Search_File("Serial Number:", $aMainLines);
				$iManucfacturer = Search_File("System Manufacturer:",$aMainLines);
				$iModel = Search_File("System Model:",$aMainLines);
				$iOfficeMatch = Search_File("Office Match:", $aMainLines);
				$iProcessorLine = Search_File("Processor:", $aMainLines);
				$iHardDriveLine = Search_File("HDD:",$aMainLines);
				$strRAMLine = Search_File("Memory:", $aMainLines);
				$iJavaVersion = Search_File("Java Version:", $aMainLines);
				$aComputerLevel['Processor'] = $aMainLines[$iProcessorLine];

				// Dustin - Here pregmatch is used to search the line number found above and finds the search term
				// then divides what's left on the line into an array. So "system Name: " is found on the line
				// found above and enters it into cell 0 then puts the rest of the line in cell 1. 
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
				
				unset($aMatches);
				preg_match("/Processor: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
				$strProcessor = $aMatches[1];
				$aComputerLevel['Processor'] = $strProcessor;

				unset($aMatches);
				preg_match("/HDD: (.*)/",$aMainLines[$iHardDriveLine],$aMatches);
				$strHDD = $aMatches[1];

				unset($aMatches);
				preg_match("/Memory: (.*)/",$aMainLines[$strRAMLine],$aMatches);
				$strRAM = $aMatches[1];
				$strRAM = round($strRAM);

				unset($aMatches);
				preg_match("/Java Version: (.*)/",$aMainLines[$iJavaVersion],$aMatches);
				if (isset($aMatches[1]) 
				{
					$strJavaVersion = $aMatches[1];
				}
					

				// Dustin - This uses the line searching method to find if the search term even
				// exists and if it does then the search term exists it sets the windows version
				// variable. I hate this section and I will make it better later
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
				
				// Dustin - This section uses our NEW and less stupid method to check the processor
				// against a list of accepted processors to ensure the PC meets the SLA
				if (Meets_Service_Level($aComputerLevel))
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
				
				// Check if duplicate record exists by computername, review date, first name, last name and location. 
				$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . addslashes($strComputerName) . "' AND ReviewYear='" . date("Y") ."' AND FName='" . $strFName . "' AND LName='" . $strLName . "' AND Location='" . $strLocationName . "'";
				
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
						echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
						echo "<input type='hidden' name='MeetsSLA' value='" . $aMeetsServiceLevel["Service Level"] . "'>";
						echo "<input type='hidden' name='MeetsVSLA' value='" . $aMeetsServiceLevel["Win10"] . "'>";
						echo "<input type='hidden' name='MeetsOSLA' value='" . $aMeetsServiceLevel["Office 2016"] . "'>";
						echo "<input type='hidden' name='Tech' value='" . $strTech . "'>";
						echo "<input type='hidden' name='ComputerName' value='" . $strComputerName . "'>";
						echo "<input type='hidden' name='Domain' value='" . $strDomain . "'>";
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

				// Dustin - these two method calls send the various data to the display form and saves them in the database
				// I really hate this and it seems sort of pointless to have them in separate files and I'll probably change it
				DispFormWin($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strManufacturer, $strModel, $strSerialNumber, $strRAM, $strHDD, $strOfficeMatch, $strProcessor, $strJavaVersion, $aMeetsServiceLevel["Service Level"], $aMeetsServiceLevel["Win10"], $aMeetsServiceLevel["Office 2016"]);
				
				SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strWindows, $strManufacturer, $strSerialNumber, $strModel, $strJavaVersion, $aMeetsServiceLevel["Service Level"],$aMeetsServiceLevel["Win10"], $aMeetsServiceLevel["Office 2016"], $strTech, "N/A", $strProcessor, $strHDD, $strRAM, $strOfficeMatch);
			break;
			
			case 2: //This is for OS X
				//Which Type of OS X are we dealing with...It seems that each version outputs a different file
				if (strripos($aMainLines[0],"10.11") || strripos($aMainLines[0],"10.10") || strripos($aMainLines[0],"10.9") || strripos($aMainLines[0],"10.8") || strripos($aMainLines[0],"10.7") || strripos($aMainLines[0],"10.6")) //Fun with Yosemite
				{
					// Dustin - Here the Mac version is found and the variable is created... just look up
					// substring because I'm not going to explain something like this, it would just take too long
					$VerIndex = strpos($aMainLines[0],"10.");
					$strOS = substr($aMainLines[0], $VerIndex, 7);
					$strOS = trim($strOS);
					
					// Dustin - Same as the PC side, just review the doc and get the line numbers then use
					// pregmatch to get the data. Just different because the scripts output different
					// names than the PC scripts. 
					$iProcessorLine = Search_File("Processor Name:", $aMainLines);
					unset($aMatches);
					preg_match("/Processor Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
					$strProcessor = $aMatches[1];
					
					$iNameLine  = Search_File("Computer Name:" , $aMainLines);
					unset($aMatches);
					if ($iNameLine > 0)
					{
						preg_match("/Computer Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
						$strComputerName = $aMatches[1];
					}
					
					$strRAMLine = Search_File("Memory:", $aMainLines);
					unset($aMatches);
					preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$strRAMLine], $aMatches);
					$strRAM = $aMatches[1];
					
					if($aMatches[1] >= 2)
					{
						$boolMemCheck = true;
					}
					$strRAM .= " GB";
					
					$iDriveLine = Search_File("Capacity:",$aMainLines);
					unset($Matches);
					preg_match("/Capacity: (.*)/",$aMainLines[$iDriveLine], $aMatches);
					$strHDD = $aMatches[1];
					$strHDD = substr($strHDD,0,9);
					
					$iSerialNumber = Search_File("Serial Number ",$aMainLines);
					unset($aMatches);
					preg_match("/Serial Number (.*)/", $aMainLines[$iSerialNumber], $aMatches);
					$strSerialNumber = $aMatches[1];
					$strSerialNumber = substr($strSerialNumber,10);
					$strSerialNumber = trim($strSerialNumber);

					$iSpeedLine = Search_File("Processor Speed:",$aMainLines);
					unset($Matches);
					preg_match("/Processor Speed: (.*)/",$aMainLines[$iSpeedLine], $aMatches);
					$strProcSpeed = $aMatches[1];

					$iJavaVersion = Search_File("java version",$aMainLines);
					unset($Matches);
					preg_match("/java version (.*)/",$aMainLines[$iJavaVersion], $aMatches);

					if (isset($aMatches[1]) 
					{
						$strJavaVersion = $aMatches[1];
					}
					$strJavaVersion = trim($strJavaVersion,'"');

					$iModel = Search_File("Model Name:",$aMainLines);
					unset($Matches);
					preg_match("/Model Name: (.*)/",$aMainLines[$iModel], $aMatches);
					$strModel = $aMatches[1];

					$strProcessor = $strProcSpeed . " " . $strProcessor;
					$aComputerLevel['Processor'] = $strProcessor;

					// Dustin - Checks the SLA same as the PC side
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
				//Check if duplicate record exists by ComputerName, Domain and current year
				$strComputerName = trim($strComputerName);
				$strLocationName = trim($strLocationName);
				$strDomain = "None";
			
				if ($strComputerName == "")
				{
					header('Location: ./errors/Error_Unsupported.html');
					exit();
				}
				
				// Check if duplicate record exists by computername, review date, first name, last name and location. 
				// everything below here is freaking redundant and I will fix later. I don't know why he redid so much
				// code but our only goal was to make it work. 
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
				  		echo "<html>";
						echo "<head><title>Redirecting to Conflict Resolution</title></head>";
						echo "<body>";
						echo "<form action='ConflictResolution.php' method='post' name='ConflictResolutionForm'>";
						echo "<input type='hidden' name='FName' value='" . $strFName . "'>";
						echo "<input type='hidden' name='LName' value='" . $strLName . "'>";
						echo "<input type='hidden' name='Department' value='" . $strDepartmentName . "'>";
						echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
						echo "<input type='hidden' name='WindowsVersion' value='" . $strWindows . "'>";
						echo "<input type='hidden' name='Location' value='" . $strLocationName . "'>";
						echo "<input type='hidden' name='Tech' value='" . $strTech . "'>";
						echo "<input type='hidden' name='ComputerName' value='" . $strComputerName . "'>";
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
				
				// Dustin - these two method calls send the various data to the display form and saves them in the database
				// I really hate this and it seems sort of pointless to have them in separate files and I'll probably change it
				DispFormMac($strEmployeeName, $strDepartmentName, $strComputerName, $strLocationName, $strDomain, $strOS, $strRAM, $strHDD, $strProcessor, $strJavaVersion, $strSerialNumber, $aMeetsServiceLevels["YosCompatible"], $aMeetsServiceLevels["OSX"]);

				SaveRecordInDB($strFName, $strLName, $strDepartmentName, $strComputerName, $strLocationName, "None", $strOS, "Apple", $strSerialNumber, $strModel, $strJavaVersion, $aMeetsServiceLevels["OSX"], "N/A", "N/A", $strTech , $aMeetsServiceLevels["YosCompatible"], $strProcessor, $strHDD, $strRAM, "N/A");
		}
	}
}
else
{	
	header('Location: ./errors/Error_NoFileAttached.html');
}


// Dustin - This function searches the passed txt file (review.txt) and finds the search
// term and returns the line number. Probably will be made useless when I change how the
// fields are populated later. 
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
// Dustin - This function checks the processor against a list of accepted ones and returns true or false
function Meets_Service_Level($findMe)
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
?> 