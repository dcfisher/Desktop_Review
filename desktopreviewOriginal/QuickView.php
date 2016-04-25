<?php
$strDepartmentName = $_POST['frmDepartment'];
$strBuildingName = $_POST['frmBuilding'];
$strTech = $_POST['frmTech'];
include_once('info.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Desktop Review: QuickView</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="./css/qstyle.css" />
</head>
<body>
	<div id="content">
		<div id="top"><h1>Desktop Review: QuickView</h1></div>
		<div id="basic">
			<div id="qselect">
				<table>
					<tr>
						<td>
							<form action="./QuickView.php" method="POST" enctype="multipart/form-data">
								<select name="frmDepartment" >
									<option value="Department" >Department</option>
<?php 				
									$sqlGetTableData = "select DepartmentName from Departments order by DepartmentName"; 
									$resultGetTableData = mysql_query($sqlGetTableData);
									$arrayGetTableData = mysql_fetch_array($resultGetTableData);
									while($arrayGetTableData)
									{
										$strDName = $arrayGetTableData['DepartmentName'];
										if ($strDName == str_replace("&", "&amp;", $strDepartmentName))
										{
											echo "<option value='" . $strDName . "' selected = \"selected\">" . $strDName . "</option>";
										}
										else 
										{
											echo "<option value='" . $strDName . "'>" . $strDName . "</option>";
										}
										$arrayGetTableData = mysql_fetch_array($resultGetTableData);
									}
?>								</select>
								<select name="frmBuilding" >
									<option value="Building" >Building</option>
<?php
									$sqlGetTableData = "select * from Buildings order by Bid"; 
									$resultGetTableData = mysql_query($sqlGetTableData);
									$arrayGetTableData = mysql_fetch_array($resultGetTableData);
									while($arrayGetTableData)
									{
										$strBName = $arrayGetTableData['Bname'];
										$strBId = $arrayGetTableData['Bid'];
										if ($strBId == str_replace("&", "&amp;", $strBuildingName))
										{
											echo "<option value='" . $strBId . "' selected = \"selected\">" . $strBName . "</option>";
										}
										else 
										{
											echo "<option value='" . $strBId . "'>" . $strBName . "</option>";
										}
										$arrayGetTableData = mysql_fetch_array($resultGetTableData);
									}
?>
								</select>
								<select name="frmTech" >
									<option value="Tech" >Tech</option>
<?php
									$sqlGetTableData = "select Tname from TechName order by Tname"; 
									$resultGetTableData = mysql_query($sqlGetTableData);
									$arrayGetTableData = mysql_fetch_array($resultGetTableData);
									while($arrayGetTableData)
									{
										$strTName = $arrayGetTableData['Tname'];
										if ($strTName == $strTech)
										{
											echo "<option value='" . $strTName . "' selected = \"selected\">" . $strTName . "</option>";
										}
										else 
										{
											echo "<option value='" . $strTName . "'>" . $strTName . "</option>";
										}
										$arrayGetTableData = mysql_fetch_array($resultGetTableData);
									}
?>
								</select>
								<input type="submit" value="Query" />
							</form>
						</td>
						<td>
							<form action="./QuickView.php" method="post" enctype="multipart/form-data">
								<input type="hidden" name="frmTech" value="Tech" />
								<input type="hidden" name="frmDepartment" value="Department" />
								<input type="hidden" name="frmBuilding" value="Building" />
								<input type="submit" value="Reset" />
							</form>
						</td>
					</tr>
				</table>
			</div>	
<?php
$bolBuilding = false;
$bolDepartment = false;
$bolTech = false;
if (($strBuildingName != "Building") && ($strBuildingName != ""))
{
	$bolBuilding = true;
}
if (($strDepartmentName != "Department") && ($strBuildingName != ""))
{
	$bolDepartment = true;
}
if (($strTech!= "Tech") && ($strTech != ""))
{
	$bolTech = true;
}
if (($bolBuilding) || ($bolDepartment) || ($bolTech))
{
	$strQuery = "SELECT ComputerName, FName, LName, Department, Location, WindowsVersion, Tech, Domain, DateCompleted FROM ReviewData WHERE";
	$strQueryArray = array();
	if ($bolBuilding)
	{
		array_push($strQueryArray, "Location LIKE '" . $strBuildingName . "%'");
	}
	if ($bolDepartment)
	{
		array_push($strQueryArray, "Department='" . $strDepartmentName . "'");
	}
	if ($bolTech)
	{
		array_push($strQueryArray, "Tech='" . $strTech . "'");
	}
	$intNumQuery = count($strQueryArray);
	$strQuery = "SELECT * FROM ReviewData WHERE " . $strQueryArray[0];
	if ($intNumQuery > 1)
	{
		$strQuery = $strQuery . " AND " . $strQueryArray[1];
	}
	if ($intNumQuery > 2)
	{
		$strQuery = $strQuery . " AND " . $strQueryArray[2];
	}
	$strQuery = $strQuery . " AND ReviewYear='" . date("Y") . "'" . "ORDER BY DateCompleted DESC";	
	$result = mysql_query($strQuery);
	if (mysql_num_rows($result)>0)
	{
		echo "<table border=\"1\">";
		echo "<tr>";
		echo "<th><p>Computer Name</p></th>";
		echo "<th><p>Employee Name</p></th>";
		echo "<th>Department</th>";
		echo "<th>Location</th>";
		echo "<th><p>OS Version</p></th>";
		echo "<th><p>Tech Name</p></th>";
		echo "<th><p>Domain</p></th>";
		echo "<th>Date</th>";
		echo "</tr>";
		while ($row = mysql_fetch_array($result))
		{
			echo "<tr>";
			echo "<td><p>" . stripslashes($row['ComputerName']) . "</p></td>";
			echo "<td><p>" . $row['FName'] . " " . $row['LName'] . "</p></td>";
			echo "<td><p>" . $row['Department'] . "</p></td>";
			echo "<td><p>" . $row['Location'] . "</p></td>";
			echo "<td><p>" . $row['WindowsVersion'] . "</p></td>";
			echo "<td><p>" . $row['Tech'] . "</p></td>";
			echo "<td><p>" . $row['Domain'] . "</p></td>";
			echo "<td><p>" . date("F d Y", $row['DateCompleted']) . " @ " . date("g:i A", $row['DateCompleted']) . "</p></td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	else
	{
		echo "<table border=\"1\">";
		echo "<tr>";
		echo "<th><p>No Results Found</p></th>";
		echo "</tr>";
		echo "</table>";
	}
}
else
{
	echo "<table border=\"1\">";
	echo "<tr>";
	echo "<th><p>Please select a Department and/or Building and/or Tech</p></th>";
	echo "</tr>";
	echo "</table>";
}
?>
</div>
</div>
</body>
</html>