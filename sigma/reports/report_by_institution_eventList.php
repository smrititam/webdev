<?php session_start();?>
<?php
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) 
{
header("Location: index.php");
}
else{
?>
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
	<title>CAHSI Reporting System</title>
	<link rel="shortcut icon" href="../css/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="../css/forms.css" type="text/css" media="all" />	
	<script src="js/jquery-1.8.0.min.js" type="text/javascript"></script>
	<script src="js/functions.js" type="text/javascript"></script>
	<script src="js/jquery-1.11.2.min.js"></script>	
</head>
<body>
<?php 
if(strstr($_SERVER["REQUEST_URI"], "sigma"))
	include_once "../includes/local-db-connection.php";
if(strstr($_SERVER["REQUEST_URI"], "team5"))	
	include_once "../includes/live-db-connection.php";
?>
	<div id="wrapper">
		<?php include_once "includes/top-nav.php";?>
		<?php include_once "../includes/header-logo.php";?>
		<div class="main">
			<div class="shell">
			<!-- REMOVED EVERYTHING INSIDE OF MAIN -->			
<?php
				$Id_institution = $_POST['Id_institution'];
				$sql = "SELECT * FROM INSTITUTION WHERE Id_institution =" . $Id_institution; 
				$result = mysqli_query($con, $sql);						
				if($result == false)
				{
					die("There was an error running the query" . mysqli_error($con));		
				}
				else 
				{
					//$row = mysqli_fetch_array($result);
					while($row = mysqli_fetch_assoc($result))
					{
					echo $row["Name"].'<br/>';
					}
				}
?>
				<table border = 1>
				 <thead>
					<tr>
					  <th>Id_event</th>
					  <th>Type</th>
					  <th>Name</th>
					  <th>Location</th>
					  <th>Datetime</th>
					  <th>Id_host</th>
					</tr>
				 </thead>
				 <tbody>					
<?php
					$sql = "SELECT * FROM CAHSI_EVENT WHERE Id_host =" . $Id_institution;
					$result = mysqli_query($con, $sql);						
					if($result == false)
					{
						die("There was an error running the query" . mysqli_error($con));		
					}
					else 
					{
						//$row = mysqli_fetch_array($result);
						while($row = mysqli_fetch_assoc($result))
						{
?>	
					<tr>
						<td><?php echo $row["Id_event"];?> </td>
						<td><?php echo $row["Type"];?> </td>
						<td><a href='report_by_institution_participationList.php?Id_event=<?php echo $row["Id_event"];?>'/><?php echo $row["Name"];?></td>
						<td><?php echo $row["Location"];?> </td>
						<td><?php echo $row["Datetime"];?> </td>
						<td><?php echo $row["Id_host"];?> </td>
					</tr>
<?php		
						}
					}
?>	
				</tbody>
			</table>
<!--<?php 
//$name1 = $_POST['name1'];
//	echo $name1.'<br/><br/>'; 
//	echo $institution.'<br/><br/>'; 
?>-->
			</div>
		</div>				
	</div>	
	<div id="footer-push"></div>
	<?php include_once "../includes/footer.php"?>
</body>
</html>
<?php }?>