<?php
include_once('info.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
	<meta http-equiv="content-type" content="text/html; charset=windows-1250">
	<meta name="generator" content="PSPad editor, www.pspad.com">
	<link rel="stylesheet" type="text/css" href="./css/style.css" />
	<title>Submitting Desktop Review Data</title>
  </head>
  <body>
	<div id="content">
		<div id="top">
			<h1>Desktop Review Data Submission</h1>
		</div>
		<div id="basic">
			<form action="./Process.php" method="POST" enctype="multipart/form-data">
				<!-- Old Form before dropdown menu-->
				<!-- <label>Tech Name:</label><input type="text" name="frmTech" /><br />
				<label>Employee Name:</label><input type="text" name="frmEmployee" /><br />
				<label>Department:</label><input type="text" name="frmDepartment" /><br />
				<label>Location:</label><input type="text" name="frmLocation" /><br /> -->
				<!-- Dynamicly creates the dropdown menu items for Technician-->
				<label>Tech Name:</label>
				<select name="frmTech" >
<?php 
					$sqlGetTableData = "select Tname from TechName order by Tname"; 
					$resultGetTableData = mysql_query($sqlGetTableData);
					$arrayGetTableData = mysql_fetch_array($resultGetTableData);
					while($arrayGetTableData)
					{
						echo '<option value="'.$arrayGetTableData['Tname'].'" >'.$arrayGetTableData['Tname'].'</option>';
						$arrayGetTableData = mysql_fetch_array($resultGetTableData);
					}
?>
				</select>
				<br />
				<label>Employee Name:</label>
				<input type="text" name="frmEmployee" />
				<br />
				<!-- Dynamicly creates the dropdown menu items for Departments-->
				<label>Department:</label>
				<select name="frmDepartment" >
<?php 
					$sqlGetTableData = "select DepartmentName from Departments"; 
					$resultGetTableData = mysql_query($sqlGetTableData);
					$arrayGetTableData = mysql_fetch_array($resultGetTableData);
					while($arrayGetTableData)
					{
						echo '<option value="'.$arrayGetTableData['DepartmentName'].'" >'.$arrayGetTableData['DepartmentName'].'</option>';
						$arrayGetTableData = mysql_fetch_array($resultGetTableData);
					}
?>
				</select>
				<br />
				<!-- Dynamicly creates the dropdown menu items for Building Location-->
				<label>Building:</label>
				<select name="frmLocation" >
<?php 
					$sqlGetTableData = "select * from Buildings"; 
					$resultGetTableData = mysql_query($sqlGetTableData);
					$arrayGetTableData = mysql_fetch_array($resultGetTableData);
					while($arrayGetTableData)
					{
						echo '<option value="'.$arrayGetTableData['Bid'].'" >'.$arrayGetTableData['Bname'].'</option>';
						$arrayGetTableData = mysql_fetch_array($resultGetTableData);
					}
?>
  </select><br/>
  <label>Room Number:</label><input type="text" name="frmRoom" size=4 maxlength=4 /><br />
  </div>
  <div id="notes">
  <span class="FormLabel">Software Install Notes:</span><br />
  <textarea name="frmInstallNotes" style ="width:100%;height:150px"></textarea>
  <br />
  <br /><span class="FormLabel">Desktop Review Notes:</span><br />
  <textarea name="frmNotes" style ="width:100%;height:150px"></textarea><br />
  </div>
  

  <!-- <textarea name="frmReviewData" id="frmReviewData"></textarea> -->
  <div id="attach">
  <input type="file" name="frmDataFile" id="frmDataFile"  />
  <input type="hidden" name="MAX_FILE_SIZE" value="50000" /><br />
  </div>

  <div id="submit">
  <input type="submit" value="Post Results" />
  </div>

  </form>
  </div>
  <!--<a href="javascript:PullData()">Pull Data</a>-->
  
  
  <script type="text/javascript">
    function PullData()
    {
      var frmFile = document.getElementById('frmDataFile');
      if (frmFile)
      {
        alert(frmFile.value);
        frmFile.value = "main.txt";
        alert(frmFile.value);
      }
    }
    //here we want to open up the main.txt file and place it in the form
  </script>
  </body>
</html>
