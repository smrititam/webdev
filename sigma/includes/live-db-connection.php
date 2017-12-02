<?php
$con = mysqli_connect('earth.cs.utep.edu', 'cs4342team5sp15','team5', 'cs4342team5sp15');

if(mysqli_errno($con))	
{
	die("Could not connect :". mysqli_error());
}
else
{
	echo "<br />Live Connection worked!!";
	echo "<br />";
}
?>