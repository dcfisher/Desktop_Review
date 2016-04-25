<?php
function StripDSpace($StrStrip)
{
	while (strpos($StrStrip,"  ") !== false){
		$StrStrip = str_replace("  ", " ", $StrStrip);
	}
	$StrStrip = str_replace("\n", "", $StrStrip);
	return trim($StrStrip);
}
?>