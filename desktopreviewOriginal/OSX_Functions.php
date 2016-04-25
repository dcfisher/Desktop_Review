<?php
include_once('TextFunctions.php');

function GetProc_OSX_5($aMainLines)
{
	if (isset($aMatches))
	{
		unset($aMatches);	
	}
	
	$iProcessorLine = Search_File("Processor Name:", $aMainLines);
	if ($iProcessorLine ==-1)
	{
		$iProcessorLine = Search_File("CPU Type:", $aMainLines);
		preg_match("/Type: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
	}
	else
	{
		preg_match("/Name: (.*)/",$aMainLines[$iProcessorLine],$aMatches);
	}
	return StripDSpace($aMatches[1]);
}

function GetOptical_OSX_3($aMainLines)
{
	$strOptical = "CD";
	if (Search_File("DVD",$aMainLines) != -1)
	{
		$strOptical = "DVD";
	}
	return $strOptical;
}


function GetHardDrive_OSX_3($aMainLines)
{
	if (isset($aMatches))
	{
		unset($aMatches);	
	}	
	
	$iDriveLine = Search_File("/dev/",$aMainLines);
	preg_match("/.*?(\s+[0-9][0-9]+).*/",$aMainLines[$iDriveLine], $aMatches);
	return StripDSpace($aMatches[1]);
}

function GetMemory_OSX_3($aMainLines)
{	
	if (isset($aMatches))
	{
		unset($aMatches);	
	}
	
	$iMemoryLine = Search_File(" Memory:", $aMainLines);
	preg_match("/Memory: ([0-9]+).*(GB|MB)/", $aMainLines[$iMemoryLine], $aMatches);
	$iMemory = $aMatches[1];
	if ($aMatches[2] == "GB")
	{
		$iMemory = $iMemory * 1000;
	}
	return $iMemory;
}

function GetComputerName_OSX_5($aMainLines)
{
	if (isset($aMatches))
	{
		unset($aMatches);	
	}
	
	$strComputerName = "";
	$iNameLine  = Search_File("Computer Name:" , $aMainLines);
	if ($iNameLine > 0)
	{
		preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
		$strComputerName = $aMatches[1];
	}
	return $strComputerName;
}
function GetProc_OSX_3($aMainLines)
{
	if (isset($aMatches))
	{
		unset($aMatches);	
	}
	
	$iProcessorLine = Search_File("CPU Type:", $aMainLines);
	if ($iProcessorLine ==-1)
	{
		$iProcessorLine = Search_File("Processor Name:", $aMainLines);
		preg_match("/Name: ([^\(\)]+)/",$aMainLines[$iProcessorLine],$aMatches);
	}
	else
	{
		preg_match("/Type: ([^\(\)]+)/",$aMainLines[$iProcessorLine],$aMatches);
	}
	return StripDSpace($aMatches[1]);
}
function GetComputerName_OSX_3($aMainLines)
{
	if (isset($aMatches))
	{
		unset($aMatches);	
	}
	
	$strComputerName = "";
	$iNameLine  = Search_File("Machine Name:" , $aMainLines);
	unset($aMatches);
	if ($iNameLine > 0)
	{
		preg_match("/Name: (.*)/", $aMainLines[$iNameLine],$aMatches);
		$strComputerName = $aMatches[1];
	}
	return $strComputerName;
}
?>