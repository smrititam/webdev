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
<?php
				// Retrieve posted variables from the form
				$Id_institution = $_POST['Id_institution'];			
				$Full_name = $_POST['Full_name'];			
				$Email = $_POST['Email'];			
				$Start_date_from = $_POST['Start_date_from'];			
				$Start_date_to = $_POST['Start_date_to'];
				$End_date_from = $_POST['End_date_from'];			
				$End_date_to = $_POST['End_date_to'];												
				$Education_level = $_POST['Education_level'];			
				$Ethnicity = $_POST['Ethnicity'];			
				$Gender = $_POST['Gender'];			
				$Date_of_birth_from = $_POST['Date_of_birth_from'];			
				$Date_of_birth_to = $_POST['Date_of_birth_to'];				
				$Research ="";
				if(isset($_POST['Research'])) 
				{
    				$Research = $_POST['Research'];
				}					
?>
				<table border = 1>
					<thead>
						<tr>
						  <th>Full_name</th>
						  <th>Email</th>
						  <th>Start_date</th>
						  <th>End_date</th>
						  <th>Education_level</th>
						  <th>Ethnicity</th>
						  <th>Gender</th>
						  <th>Date_of_birth</th>
						</tr>
					 </thead>
					 <tbody>					
<?php
						// Query builder initial construction
						$sql = "SELECT t1.Id_person, t1.Full_name, t1.Email, t2.Start_date, t2.End_date, t2.Education_level,t2.Ethnicity, t2.Gender, t2.Date_of_birth
								FROM PERSON t1, PARTICIPANT t2
								WHERE t1.Id_person = t2.Id_participant";
						$sql_Id_institution = "";
						$sql_Full_name = "";
						$sql_Email = "";
						$sql_Start_date = "";
						$sql_End_date = "";
						$sql_Education_level = "";
						$sql_Ethnicity = "";
						$sql_Gender = "";
						$sql_Date_of_birth = "";
						$sql_Research = "";

						// Query builder conditional statements
						if($Id_institution!=""){
							$sql_Id_institution =  " AND t2.Id_participant IN (
													SELECT Id_participant
													FROM PERSON t1 INNER JOIN PARTICIPANT t2 ON t1.Id_person = t2.Id_participant
													WHERE Id_institution = " . $Id_institution . ")";
						}
						if($Full_name!=""){
							$sql_Full_name =  " AND t1.Full_name LIKE '%" . $Full_name . "%'";
						}
						if($Email!=""){
							$sql_Email =  " AND t1.Email LIKE '%" . $Email . "%'";
						}
						if($Start_date_from!="" && $Start_date_to!=""){						
							$sql_Start_date =  " AND t2.Start_date >= '" . $Start_date_from . "'" . " AND t2.Start_date <= '" . $Start_date_to . "'";					
						}
						if($End_date_from!="" && $End_date_to!=""){						
							$sql_End_date =  " AND t2.End_date >= '" . $End_date_from . "'" . " AND t2.End_date <= '" . $End_date_to . "'";					
						}
						if($Education_level!=""){
							$sql_Education_level =  " AND t2.Education_level LIKE '%" . $Education_level . "%'";
						}
						if($Ethnicity!=""){
							$sql_Ethnicity =  " AND t2.Ethnicity LIKE '%" . $Ethnicity . "%'";
						}
						if($Gender!=""){
							$sql_Gender =  " AND t2.Gender = '" . $Gender . "'";
						}
						if($Date_of_birth_from!="" && $Date_of_birth_to!=""){						
							$sql_Date_of_birth =  " AND t2.Date_of_birth >= '" . $Date_of_birth_from . "'" . " AND t2.Date_of_birth <= '" . $Date_of_birth_to . "'";					
						}
						if($Research=="checked"){
							$sql_Research = " AND t1.Id_person IN (
												SELECT Id_person
												FROM RESEARCH)";
						}
						
						// Append query
						$sql = $sql . $sql_Id_institution . $sql_Full_name . $sql_Email . $sql_Start_date . $sql_End_date . $sql_Education_level . $sql_Ethnicity . $sql_Gender . $sql_Date_of_birth . $sql_Research;	

						// Perform query
						$result = mysqli_query($con, $sql);						
						if($result == false)
						{
							die("There was an error running the query" . mysqli_error($con));		
						}
						else 
						{		
							// Keep track of number of results
							$count = 0;
							
							// For each query result, display in a table
							while($row = mysqli_fetch_assoc($result))
							{
?>	
						<tr>
							<td><a href='report_eventList.php?Id_person=<?php echo $row["Id_person"];?>'/><?php echo $row["Full_name"];?></td>											
							<td><?php echo $row["Email"];?> </td>
							<td><?php echo $row["Start_date"];?> </td>
							<td><?php echo $row["End_date"];?> </td>
							<td><?php echo $row["Education_level"];?> </td>
							<td><?php echo $row["Ethnicity"];?> </td>
							<td><?php echo $row["Gender"];?> </td>
							<td><?php echo $row["Date_of_birth"];?> </td>
						</tr>
<?php		
							$count++;
							}
						// Display number of results
						echo 'Results: ' . $count;
						}					
?>	
					</tbody>
				</table>
			</div>
		</div>		
	</div>	
	<div id="footer-push"></div>
	<?php include_once "../includes/footer.php"?>
</body>
</html>
<?php }?>