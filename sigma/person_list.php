<?php session_start();?>
<?php
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) 
{
header("Location: index.php");
}
else{
?>
<!DOCTYPE html>
<!--HTML 5 + IE HACK--><!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
	<title>CAHSI Reporting System</title>
	<link rel="shortcut icon" href="/css/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="css/forms.css" type="text/css" media="all" />
	<!--<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500,700' rel='stylesheet' type='text/css' />-->
	
	<script src="js/jquery-1.8.0.min.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
		<script src="js/modernizr.custom.js"></script>
	<![endif]-->
	<!--<script src="js/jquery.flexslider-min.js" type="text/javascript"></script>-->
	<script src="js/functions.js" type="text/javascript"></script>
	<script src="js/jquery-1.11.2.min.js"></script>	
</head>
<body>
<?php 
if(strstr($_SERVER["REQUEST_URI"], "sigma"))
	include_once "includes/local-db-connection.php";
if(strstr($_SERVER["REQUEST_URI"], "team5"))	
	include_once "includes/live-db-connection.php";
?>
	<div id="wrapper">
		
		<!-- top-nav -->
		<?php include_once "includes/top-nav.php";?>
		<!-- end of top-nav -->
			<!-- header -->
			<?php include_once "includes/header-logo.php";?>
			<!-- end of header -->
		<div class="main">
			<div class="shell">
			<!-- REMOVED EVERYTHING INSIDE OF MAIN -->
			<a href="person_list.php">View CAHSI Member List</a><br />
			<a href="system_user_list.php">View CAHSI System User List</a><br /><br />			
	<?php
	if(isset($_GET['editid']))
	{
			// Start of IF for UPDATE form
			?>
			<!-- TRYING HTML5 WITH CSS3 -->
			<section id="AddUserBox">
				<h2>Edit CAHSI Person</h2>
				<form method="post" class="minimal" action="#">
				<?php
				$eid = isset($_REQUEST['editid']) ? $_REQUEST['editid'] : 0;
				$sql1 = "SELECT * FROM PERSON WHERE Id_person ='$eid'"; 
				$result1 = mysqli_query($con, $sql1);						
				if($result1 == false)
				{
					die("There was an error running the query" . mysqli_error($con));			
				}
				else 
				{
					while($row1 = mysqli_fetch_assoc($result1))
					{
					?>
					<label for="fullname">
						Fullname:
						<input type="text" name="user_fullname" id="user_fullname" value="<?php echo $row1['Full_name']?>" required="required" />
					</label>
					<label for="email">
						Email:
						<input type="text" name="user_email" id="user_email" placeholder="Email" value="<?php echo $row1["Email"]?>" required="required" />
					</label>				
					<label for="phone">
						Phone:
						<input type="text" name="user_phone" id="user_phone" value="<?php echo $row1["Phone"]?>" />
					</label>							
					<label for="institution">
						Institution:
						<select name="user_institution">
						<option value="">--Select--</option>
						<?php
							$sql = "SELECT * FROM INSTITUTION"; 
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
								//$sql2 = "SELECT * FROM INSTITUTION WHERE Id_institution= '.$row["Id_institution"].'"; 
								$sql2="SELECT t1.Id_institution, t2.Name
											FROM PERSON t1, INSTITUTION t2
											WHERE t1.Id_institution = t2.Id_institution AND t1.Id_person ='$eid'";
								$result2 = mysqli_query($con, $sql2);	
									while($row2 = mysqli_fetch_assoc($result2))
									{
									?>							
		<option value='<?php echo $row["Id_institution"];?>' <?php echo (isset($row2['Id_institution']) && $row2['Id_institution']==$row["Id_institution"]) ? 'selected': ''?>><?php echo $row["Name"];?></option>;
									<?php
									}
								}
							}
							?>
						</select>
					</label>
					<label for="mentor">
						Mentor:
						<select name="user_mentor" id="user_mentor">
						<option value="">--Select--</option>
						<?php
							$sql = "SELECT * FROM user"; 
							$result = mysqli_query($con, $sql);						
							if($result == false)
							{
								die("There was an error running the query" . mysqli_error($con));			
							}
							else 
							{
 								while($row = mysqli_fetch_assoc($result))
								{
									$sql3 = "SELECT Id_person,Full_name FROM PERSON where Id_person =".$row['Id_user']; 
									$result3 = mysqli_query($con, $sql3);	
									if($result3 == false)
									{
										die("There was an error running the query" . mysqli_error($con));			
									}
									else 
									{		
									while($row3 = mysqli_fetch_assoc($result3))
										{
										?>							
										<option value='<?php echo $row3["Id_person"];?>' <?php echo (isset($row3['Id_person']) && $row3['Id_person']==$row["Id_user"]) ? 'selected': ''?>><?php echo $row3["Full_name"];?></option>;
									<?php
										}
									}
								} 
							}														
					?>							
						</select>
					</label>					

					<input type="hidden" id="pid" name="person_id" value="<?php echo (isset($last_id))? $last_id: ''?>" />
					<input type="submit" name="submit" class="btn-minimal" value="Edit Person">
		<?php   	}
				} 
	?>
				</form>
			</section>				
	<?php 
	// END OF IF for UPDATE FORM
	} 
	?>	
			<?php 
			// Things that get updated into 'person' table
			$eid = isset($_REQUEST['editid']) ? $_REQUEST['editid'] : 0;
			
			if (isset($_POST['submit']))
			{			
			$uemail = $_POST['user_email'];
			$uphone = $_POST['user_phone'];
			$ufullname = $_POST['user_fullname'];
			$uinstitution = $_POST['user_institution'];
			$umentor = $_POST['user_mentor'];
				if(isset($eid) && $eid != 0)
				{
					if(empty($umentor))
					{				
						$sql = "UPDATE PERSON SET Email='$uemail' , Phone='$uphone' , Full_name ='$ufullname', Id_institution='$uinstitution' WHERE Id_person='$eid'"; 
						$result = mysqli_query($con, $sql);
						if($result == false)
							{
							die("There was an error running the query" . mysqli_error($con));			
							}						
						if($result)	
							{
							echo "CAHSI member information updated!<br />";
							}
					}
					else
					{
						$umentor = $_POST['user_mentor'];
						$sql = "UPDATE PERSON SET Email='$uemail' , Phone='$uphone' , Full_name ='$ufullname', Id_institution='$uinstitution', Id_mentor='$umentor' WHERE Id_person='$eid'"; 
						$result = mysqli_query($con, $sql);					
						if($result == false)
							{
							die("There was an error running the query" . mysqli_error($con));			
							}
						if($result)	
							{
							echo "CAHSI member information updated!<br />";
	/* 						$last_id = mysqli_insert_id($con);
							$_SESSION['person_id'] = $last_id;
							echo "<a href='addusers.php'>Add CAHSI system user</a>"; */			
							}
					}
				} // end of UPDATE code
				

			}// End of $POST SUBMIT section
			
			//start of delete code
			$did = isset($_REQUEST['delid']) ? $_REQUEST['delid'] : 0;
			if(isset($did) && $did != 0)
			{			
			$sql = "DELETE FROM PERSON WHERE Id_person='$did'"; 
			$result = mysqli_query($con, $sql);
				if($result == false)
				{
					die("There was an error running the query" . mysqli_error($con));			
				}						
				else
				{
					echo "CAHSI member information DELETED!<br />";
				}

			}//End of delete code			
			?>			
			<?php
			$sql = "SELECT * FROM PERSON"; 
			$result = mysqli_query($con, $sql);	
					
				if($result == false)
				{
					die("There was an error running the query" . mysqli_error($con));			
				}
				else 
				{	$row_cnt = mysqli_num_rows($result);
					if($row_cnt>0)
					{
					echo '<table border="1">';
					while($row = mysqli_fetch_assoc($result)) {
						echo '<tr>';
						echo '<td>'.$row["Full_name"].'</td>';
						echo '<td>'.$row["Email"].'</td>';
						echo '<td><a href="person_list.php?editid='.$row["Id_person"].'">Edit</a></td>';
						echo '<td><a href="person_list.php?delid='.$row["Id_person"].'">Delete</a></td>';
						echo '</tr>';
					}  
					echo '</table>';
					}
					else
					{
					echo "No person data available.";
					}
				
				}
			//}
			?>
			<?php include_once "includes/right-nav.php"; ?>
			</div>
		</div>				
		</div>	
		<!-- footer-push -->
		<div id="footer-push"></div>
		<!-- end of footer-push -->
	
	<!-- end of wrapper -->
	<!-- footer -->
	<?php include_once "includes/footer.php"?>
	<!-- end of footer -->
</body>
</html>
<?php }?>