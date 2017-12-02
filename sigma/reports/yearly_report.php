<?php
// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

session_start();
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != '')) 
{
	header("Location: index.php");
}
else{
	
	//$con = mysqli_connect('localhost', 'root','', 'cs4342team5sp15');
	$con = mysqli_connect('earth.cs.utep.edu', 'cs4342team5sp15','team5', 'cs4342team5sp15');

	require ("FPDF/fpdf.php");
	// List all fpdf functions
	//var_dump(get_class_methods($pdf));
	class PDF extends FPDF
	{
		// Colored table
		function FancyTable($header, $data)
		{
			// Colors, line width and bold font
			$this->SetFillColor(0,165,165);
			$this->SetTextColor(255);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->SetFont('','B');
			// Header
			$w = array(20, 55, 60, 50);
			for($i=0;$i<count($header);$i++)
				$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
			$this->Ln();
			// Color and font restoration
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('');
			// Data
			$fill = false;
			foreach($data as $row)
			{
				//Number format
				//$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
				$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
				$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
				$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
				$this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
				$this->Ln();
				$fill = !$fill;
			}
			// Closing line
			$this->Cell(array_sum($w),0,'','T');
		}
	}

	$school_year = $_POST['school_year'];
	// Redirect if no school year selected
	if ($school_year== '') 
	{
		header("Location: yearly_report_form.php");
	}

	//Assign school year query
	$DateTime_From = $school_year . "/8/1";
	$DateTime_To = ($school_year+1) . "/7/31";
	$sql_DateTime =  " WHERE DateTime > '" . $DateTime_From . "'" . " AND DateTime < '" . $DateTime_To . "'";	

	// Set base query from school year						
	$sql = "SELECT t1.Id_person, t1.Full_name, t1.Email, t2.Start_date, t2.End_date, t2.Education_level,t2.Ethnicity, t2.Gender, t2.Date_of_birth, t2.Education_level, t3.Name as Institution
			FROM PERSON t1, PARTICIPANT t2, INSTITUTION t3
			WHERE t1.Id_person = t2.Id_participant
			AND t1.Id_institution = t3.Id_institution
			AND t2.Id_participant IN(
					SELECT Id_participant
					FROM PARTICIPANT_ATTENDS_EVENT
					WHERE Id_event  IN(
						SELECT Id_event
						FROM CAHSI_EVENT "
						. $sql_DateTime . 
					")									
			)";
			
	// Create new pdf
	$pdf = new PDF();
	
	//------------------------------------------
	//////////////////COVER PAGE////////////////
	//------------------------------------------

	// Add cover page
	$pdf->AddPage();
	
	//Set yearly report title
	$pdf->SetFont("Arial", "B", "20");
	$pdf->Cell(0, 10, "", 0, 1, "C");
	$pdf->Cell(0, 10, "", 0, 1, "C");
	$pdf->Cell(0, 10, "", 0, 1, "C");
	$pdf->Cell(0, 10, "", 0, 1, "C");
	$pdf->Cell(0, 10, "", 0, 1, "C");
	$pdf->Image('cahsilogo1.PNG', 60);
	
	//width, height, string, bar, new line , alignment
	$pdf->Cell(0, 10, "Yearly Report", 0, 1, "C");

	//Set yearly report school year range
	$pdf->SetFont("Arial", "", "10");
	$title = $school_year . " - " . ($school_year + 1);
	$pdf->Cell(0, 10, $title, 0, 1, "C");
	$pdf->Image('TeamLogo.JPG', 93, 250);
	

	//------------------------------------------
	//////////////////PARTICIPANTS//////////////
	//------------------------------------------
	
	// Add pdf page
	$pdf->AddPage();
	// Insert header image
	$pdf->Image('CAHSI_HEADER.jpg');
	$pdf->Cell(0, 10, "", 0, 1, "C");
	$pdf->SetFont("Arial", "B", "14");
	$pdf->Cell(0, 10, "By Participant", 0, 1, "L");
	$pdf->SetFont("Arial", "", "10");	
		
	//////////////////All Participants//////////////
	// No append to base query	
			
	// Run query	
	$result = mysqli_query($con, $sql);	
	
	if($result == false)
	{
		$pdf->Cell(0, 10, "ERROR in result!!", 0, 1, "C");		
	}	
	else 
	{	
		//display query for testing
		//$pdf->Write(10, $sql);
		//$pdf->Cell(0, 10, "", 0, 1, "C");

		// Table name
		$pdf->Cell(0, 10, "All Participants", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{					
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display data in a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}

	//------------------------------------
	//////////////////GENDER//////////////
	//------------------------------------
	
	// Add pdf page
	$pdf->AddPage();
	// Insert header image
	$pdf->Image('CAHSI_HEADER.jpg');
	$pdf->Cell(0, 10, "", 0, 1, "C");	
	$pdf->SetFont("Arial", "B", "14");
	$pdf->Cell(0, 10, "By Gender", 0, 1, "L");
	$pdf->SetFont("Arial", "", "10");
		
	//////////////////All Male Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Gender = 'Male'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Male", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Female Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Gender = 'Female'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Female", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//------------------------------------
	///////////////ETHNICITY//////////////
	//------------------------------------

	// Add pdf page
	$pdf->AddPage();
	// Insert header image
	$pdf->Image('CAHSI_HEADER.jpg');
	$pdf->Cell(0, 10, "", 0, 1, "C");	
	$pdf->SetFont("Arial", "B", "14");
	$pdf->Cell(0, 10, "By Ethnicity", 0, 1, "L");
	$pdf->SetFont("Arial", "", "10");
	
	//////////////////All Black Participants//////////////
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Ethnicity = 'Black'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "African American", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All American Indian Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Ethnicity = 'American Indian'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "American Indian", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Asian/Pacific Islander Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Ethnicity = 'Asian/Pacific Islander'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Asian/Pacific Islander", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Hispanic Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Ethnicity = 'Hispanic'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Hispanic", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All White Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Ethnicity = 'White/Non-Hispanic'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Caucasian", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Undetermined Ethnicity Participants//////////////	
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Ethnicity = 'Race/Ethnicity Unknown'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Unspecified Ethnicity", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//------------------------------------------
	///////////////EDUCATION LEVEL//////////////
	//------------------------------------------

	// Add pdf page
	$pdf->AddPage();
	// Insert header image
	$pdf->Image('CAHSI_HEADER.jpg');
	$pdf->Cell(0, 10, "", 0, 1, "C");	
	$pdf->SetFont("Arial", "B", "14");
	$pdf->Cell(0, 10, "By Education Level", 0, 1, "L");
	$pdf->SetFont("Arial", "", "10");
	
	//////////////////All K-12 Participants//////////////
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Education_level = 'K-12'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "K-12", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Undergraduate Participants//////////////
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Education_level = 'Undergraduate'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Undergraduate", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Graduate Participants//////////////
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Education_level = 'Graduate'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Graduate", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}
	
	//////////////////All Doctoral Participants//////////////
	// Append to base query	
	$sql_Gender =  $sql . " AND t2.Education_level = 'Doctoral'";
	
	// Run query
	$result = mysqli_query($con, $sql_Gender);						
	if($result == false)
	{
		die("There was an error running the query" . mysqli_error($con));		
	}
	else 
	{	
		// Table name
		$pdf->Cell(0, 10, "Doctoral", 0, 0, "L");
		// Column headings
		$header = array('Count', 'Name', 'Institution', 'Category');
		$data1 = array();
		
		// Keep track of number of results
		$count = 0;
		$results[] = array();
		
		// For each query result, add to data
		while($row = mysqli_fetch_assoc($result))
		{
			$results = $row;
			$name = $row["Full_name"];
			$institution = $row["Institution"];
			$education_level = $row["Education_level"];
			$displayCount = $count +1;

			// Clear temp data
			unset($temp);
			
			// Set temp to current row data
			$temp[]=$count + 1;
			$temp[]=$name;
			$temp[]=$institution;
			$temp[]=$education_level;
			
			// Push row into table
			$data1[]=$temp;
			
			// Keep track of row number
			$count++;
		}
		
		// Display datain a table
		$pdf->Cell(0, 10, "Count: " . $count, 0, 1, "R");
		$pdf->FancyTable($header,$data1);
		$pdf->Cell(0, 10, "", 0, 1, "C");
	}

	//Output the constructed pdf
	$pdf->Output();
}?>