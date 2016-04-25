<?php
include_once('info.php');
include_once('TextFunctions.php');
include_once('OSX_Functions.php');
$iFileType = 0;

$files = array_diff(scandir("./tmpfiles/", true), array(".", ".."));
for ($i=0; $i < count($files); $i++)
{
	$files[$i] = "./tmpfiles/" . $files[$i];
}
for ($file=0; $file < count($files); $file++)
{
	
	$aMainLines = file($files[$file]);
	
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
	
	switch($iFileType)
	{
		case 1:
		if (Search_File("admnet",$aMainLines) != -1)
		{
			$strDomain = "ADMNET";
		}
		
		if (Search_File("opennet",$aMainLines) != -1)
		{
			$strDomain = "OPENNET";
		}
				
		//Lets get the name
		$iNameLine = Search_File("System Name: ", $aMainLines);
		unset($aMatches);
		preg_match("/System Name: (.*)/",$aMainLines[$iNameLine],$aMatches);
		$strComputerName = $aMatches[1];
		
		if (Search_File("Version 6.2", $aMainLines) != -1)
		{
			$strWindows = "8";
		}
		else if (Search_File("Version 6.1.7601", $aMainLines) != -1)
		{
			$strWindows = "7 SP1";
		}
		else if (Search_File("Version 6.1.7600", $aMainLines) != -1)
		{
			$strWindows = "7";
		}	
		else if (Search_File("Version 6.0.6002", $aMainLines) != -1)
		{
			$strWindows = "Vista SP2";
		}
		else if (Search_File("Version 6.0.6001", $aMainLines) != -1)
		{
			$strWindows = "Vista SP1";
		}
		else if (Search_File("Version 6.0.6001", $aMainLines) != -1)
		{
			$strWindows = "Vista";
		}
		else if (Search_File("Version 6.0.6002", $aMainLines) != -1)
		{
			$strWindows = "Vista SP2";
		}
		else if (Search_File("XP", $aMainLines) != -1)
		{
			$strWindows = "XP";
		}

		$aDisplayCardName = array();
		$aDisplayMemory = array();
		$aDisplayCurrentMode = array();
		$aHardDriveLetter = array();
		$aHardDriveSpace = array();
		$aOpticalDriveLetter = array();
		$aOpticalDriveType = array();

		//Get Processor info
		$iProcessorLine = Search_File("Processor:", $aMainLines);
		$aComputerLevel['Processor'] = str_replace("Processor: ", "", stripDSpace($aMainLines[$iProcessorLine])); //Only one Processor line should be present
		
		//Get Memory info
		$iMemoryLine = Search_File(" Memory:", $aMainLines);
		preg_match('/([0-9\.]+)/',$aMainLines[$iMemoryLine],$aMatches);
		$aComputerLevel['RAM'] = $aMatches[1];
		$strRam = $aComputerLevel['RAM'] . " MB";
		
		//Get optical info
		$aComputerLevel['Optical'] = "";
		if (Search_File(" DVD",$aMainLines) != -1)
		{
			$aComputerLevel['Optical'] = "DVD,";
		}
		if (Search_File(" CD", $aMainLines) != -1)
		{
			$aComputerLevel['Optical'] .= "CD,";
		}
		$strOptical = substr(stripDSpace($aComputerLevel['Optical']), 0, -1);
		
		//Find data for items with multiplicity
		for ($i = 0; $i < count($aMainLines); $i++)
		{	
			//We assume that All of the following information for each card will appear in the file before a second.
			if (strpos($aMainLines[$i], "Display Memory:") !== false)
			{	//Get "Display Memory," strip extra charachters and place a "0" in place of "n/a"
			array_push($aDisplayMemory, trim(str_replace("n/a", "0", str_replace(" MB", "", str_replace("Display Memory: ", "", $aMainLines[$i])))));	
			}	
			if (strpos($aMainLines[$i], "Card name:") !== false)
			{	//Get the name of each card
			array_push($aDisplayCardName, str_replace("Card name: ", "", stripDSpace($aMainLines[$i])));	
			}
			if (strpos($aMainLines[$i], "Current Mode:") !== false)
			{	//Get the current resolution of this card
			array_push($aDisplayCurrentMode, $aMainLines[$i]);			
			}	
			if (strpos($aMainLines[$i], "Total Space:") !== false)
			{	//Key on "Total Space:" for Drive Stuff
			array_push($aHardDriveLetter, str_replace("Drive: ", "", $aMainLines[$i-2])); //"Drive:"
			array_push($aHardDriveSpace, str_replace(" GB", "", str_replace("Total Space: ", "", $aMainLines[$i]))); //"Total Space:"
			}
		}

		$aComputerLevel['HardDrive'] = trim(str_replace("Total Space: ", "", $aHardDriveSpace[0]));
		$strHardDrive = "";
		for ($i = 0; $i < count($aHardDriveSpace); $i++)
		{
			$strHardDrive .= stripDSpace(" " . $aHardDriveLetter[$i] . " @ " . $aHardDriveSpace[$i] . " GB | ");
		}
		$strHardDrive = str_replace(" |"," | ",substr(trim($strHardDrive), 0, -2));

		$strGraphic = "";
		$strDisplay = "";
		for ($i = 0; $i < count($aDisplayMemory); $i++)
		{
			//Generate Graphics string for DB
			$strGraphic .= " " . $aDisplayCardName[$i] . " @ " . $aDisplayMemory[$i] . " MB |";
			$strDisplay .= stripDSpace(str_replace("Current Mode: ", " ", " " . ($aDisplayCurrentMode[$i]) . " | "));
		}
		//Clean some strings up
		$strGraphic = str_replace(" 0 MB", " n/a", substr(trim($strGraphic), 0, -2));
		$strDisplay = str_replace(" |"," | ",substr($strDisplay, 0, -2));
		
		//sometimes the variables have whitespace, this causes problems
		$strComputerName = trim($strComputerName);
		$strDomain = trim($strDomain);
			
		//Check if duplicate record exists by ComputerName, Domain and Current Year
		$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . $strComputerName . "' AND Domain='" . $strDomain . "' AND WindowsVersion='" . $strWindows . "'";
		echo "WIN!" . $query_string . "!<br>";
		//echo $query_string . "<br>";
		$LastId=0;
		$query = mysql_query($query_string);	
		
			while($row = mysql_fetch_array($query))
			{
				$LastId = $row[0];
			}
			mysql_query("UPDATE ReviewData SET Processor='" . $aComputerLevel["Processor"] . "', HardDrives='" . $strHardDrive . "', OpticalDrive='" . $strOptical . "', RamSize='" . $strRam . "', Graphics='" . $strGraphic . "', DisplayResolution='" . $strDisplay . "' WHERE ReviewID ='" . $LastId . "'");
			echo mysql_error();
		case 2:
		$strOptical = "";
		$strComputerName = "";
		$OSXVer = "0";
		$strOS = "";
		
		if (strripos($aMainLines[0],"10.7")) //Fun with Lion
		{
			//Get specific OSX version
			$VerIndex = strripos($aMainLines[0],"10.7");
			$OSXVer = substr($aMainLines[0], $VerIndex, 7);
			$OSXVer = trim($OSXVer);
			$strOS = "10.7 Lion (" . $OSXVer . ")";
			
			//Get System info
			$aComputerLevel["Processor"] = GetProc_OSX_5($aMainLines);
			$strRam = GetMemory_OSX_3($aMainLines);
			$strHardDrive = GetHardDrive_OSX_3($aMainLines);
			$aComputerLevel["Optical"] = GetOptical_OSX_3($aMainLines);
			$strComputerName = GetComputerName_OSX_5($aMainLines);	
		}
		else if (strripos($aMainLines[0],"10.6")) //Fun with Snow Leopard
		{
			//Get specific OSX version
			$VerIndex = strripos($aMainLines[0],"10.6");
			$OSXVer = substr($aMainLines[0], $VerIndex, 7);
			$OSXVer = trim($OSXVer);
			$strOS = "10.6 Snow Leopard (" . $OSXVer . ")";
			
			//Get System info
			$aComputerLevel["Processor"] = GetProc_OSX_5($aMainLines);
			$strRam = GetMemory_OSX_3($aMainLines);
			$strHardDrive = GetHardDrive_OSX_3($aMainLines);
			$aComputerLevel["Optical"] = GetOptical_OSX_3($aMainLines);
			$strComputerName = GetComputerName_OSX_5($aMainLines);
		}
		else if (strripos($aMainLines[0],"10.5")) //Fun with Leopard (no longer supported)			
		{
			//Get detailed OS version
			$VerIndex = strripos($aMainLines[0],"10.5");
			$OSXVer = substr($aMainLines[0], $VerIndex, 7);
			$OSXVer = trim($OSXVer);
			$strOS = "10.5 Leopard (" . $OSXVer . ")";
			
			//Get System info
			$aComputerLevel["Processor"] = GetProc_OSX_5($aMainLines);
			$strRam = GetMemory_OSX_3($aMainLines);
			$strHardDrive = GetHardDrive_OSX_3($aMainLines);
			$aComputerLevel["Optical"] = GetOptical_OSX_3($aMainLines);
			$strComputerName = GetComputerName_OSX_5($aMainLines);

		}
		else if (strripos($aMainLines[0],"10.4")) //Fun with Tiger (no longer supported)					
		{
			//Get detailed OS version
			$VerIndex = strripos($aMainLines[0],"10.4");
			$OSXVer = substr($aMainLines[0], $VerIndex, 7);
			$OSXVer = trim($OSXVer);
			$strOS = "10.4 Tiger (" . $OSXVer . ")";
			
			//Get System info
			$aComputerLevel["Processor"] = GetProc_OSX_3($aMainLines);
			$strRam = GetMemory_OSX_3($aMainLines);
			$strHardDrive = GetHardDrive_OSX_3($aMainLines);
			$aComputerLevel["Optical"] = GetOptical_OSX_3($aMainLines);
			$strComputerName = GetComputerName_OSX_3($aMainLines);
		}
		else if (strripos($aMainLines[0],"10.3")) //Fun with Panther (no longer supported)	
		{
			//Get detailed OS version
			$VerIndex = strripos($aMainLines[0],"10.3");
			$OSXVer = substr($aMainLines[0], $VerIndex, 7);
			$aComputerLevel["OSX"] = trim($OSXVer);
			$strOS = "10.3 Panther (" . $OSXVer . ")";
			
			//Get System info
			$aComputerLevel["Processor"] = GetProc_OSX_3($aMainLines);
			$strRam = GetMemory_OSX_3($aMainLines);
			$strHardDrive = GetHardDrive_OSX_3($aMainLines);
			$aComputerLevel["Optical"] = GetOptical_OSX_3($aMainLines);
			$strComputerName = GetComputerName_OSX_3($aMainLines);
		}
		
		$aComputerLevel["OSX"] = $OSXVer;
		$aComputerLevel["RAM"] = $strRam;
		$strRam .= " MB";
		
		$aComputerLevel["HardDrive"] = $strHardDrive;
		$strHardDrive .= " GB";
		
		$strGraphic = "unavailable"; //Not in client file output
		$strDisplay = "unavailable"; //Not in client file output
		
		//Check if duplicate record exists by ComputerName, Domain and current year
		echo $strComputerName . "<br>";
		$strComputerName = trim($strComputerName);
		$strDomain = "None";
		
		$query_string = "SELECT ReviewId FROM ReviewData WHERE ComputerName='" . addslashes($strComputerName) . "' AND WindowsVersion='" . $strOS . "'";
		
		$query = mysql_query($query_string);	
		//This will stay '0' if nothing matches as ReviewId should never be 0.
		
		echo "MAC!" . $query_string . "!<br>";
		//echo $query_string . "<br>";
		$LastId=0;
		$query = mysql_query($query_string);	
		while($row = mysql_fetch_array($query))
		{
			$LastId = $row[0];
		}
		$mysql_string = "UPDATE ReviewData SET Processor='" . $aComputerLevel["Processor"] . "', HardDrives='" . $strHardDrive . "', OpticalDrive='" . $aComputerLevel["Optical"] . "', RamSize='" . $strRam . "', Graphics='" . $strGraphic . "', DisplayResolution='" . $strDisplay . "' WHERE ReviewID ='" . $LastId . "'";
		mysql_query($mysql_string);
		echo mysql_error();
	}
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

?> 