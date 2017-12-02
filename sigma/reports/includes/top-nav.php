<?php //error_reporting(E_ALL & ~E_NOTICE)?>
<nav class="top-nav">
	<div class="shell">
		<a href="#" class="nav-btn">HOMEPAGE<span></span></a>
		<span class="top-nav-shadow"></span>
		<ul>
			<li class="active"><span><a href="../home.php">home</a></span></li>
			<li><span><a href="../profiles.php">profiles</a></span></li>
			<li><span><a href="../reports.php">reports</a></span></li>
			<li><span><a href="../add_cahsi_event.php">events</a></span></li>
			<?php
			$username = $_SESSION['username'];
			$sql = "SELECT * FROM USER WHERE Username='$username'";
			$result = mysqli_query($con, $sql);		
			while($row = mysqli_fetch_assoc($result))
			{
				if($row['Type'] == "CAHSI Manager")
				{?>
			<li><span><a href="../add_cahsi_person.php">add CAHSI member</a></span></li>
			<?php }?>	
			<div class="logged-info">
				<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==1)
				{ ?>				
					Logged in as: <?php echo $_SESSION['username'];?>
				<a href="../logout.php" title="Log out">Log out.</a>
				<?php 
				}
			}?>
			</div>
		</ul>
	</div>
</nav>