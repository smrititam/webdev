<?php
require ("FPDF/fpdf.php");

class PDF extends FPDF
{
	// Load data
	function LoadData(/*$file*/)
	{
		// Read file lines
		/*$lines = file($file);
		$data = array();
		foreach($lines as $line)
			$data[] = explode(';',trim($line));		
		return $data;
		*/
		$data = array();
		$data[] = ["1", "2", "3", "4", "10"];
		$data[] = ["5", "6", "7", "8", "20"];
		
		return $data;
	}

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
		$w = array(35, 35, 35, 35, 35);
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
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			//$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
			//$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
			$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
			$this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
			$this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
			$this->Ln();
			$fill = !$fill;
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
}
//var_dump(get_class_methods($pdf));

$pdf = new PDF();

// Default font
$pdf->SetFont('Arial','',14);

// Add cover page
$pdf->AddPage();

$pdf->SetFont("Arial", "B", "20");
//width, height, string, bar, new line , alignment
$pdf->Cell(0, 10, "Yearly Report", 0, 1, "C");

$pdf->SetFont("Arial", "", "10");
$pdf->Cell(0, 10, "year range", 0, 1, "C");



// Add pdf page
$pdf->AddPage();
// Insert header image
$pdf->Image('CAHSI_HEADER.jpg');
$pdf->Cell(0, 10, "", 0, 1, "C");


// Table name
$pdf->Cell(0, 10, "Test Table 1", 0, 1, "L");
// Column headings
$header = array('column1', 'Column2', 'Column3', 'Column4', 'Column5');
// Data loading
//$data = $pdf->LoadData();
$data1 = array();
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["1", "2", "3", "4", "100"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		$data1[] = ["5", "6", "7", "8", "200"];
		
$pdf->FancyTable($header,$data1);

$pdf->Cell(0, 10, "", 0, 1, "C");

// Table name
$pdf->Cell(0, 10, "Test Table 2", 0, 1, "L");
// Column headings
$header = array('column1', 'Column2', 'Column3', 'Column4', 'Column5');
// Data loading
//$data = $pdf->LoadData();
$data2 = array();
		$data2[] = ["a", "c", "3", "4", "1001"];
		$data2[] = ["b", "6", "7", "8", "200"];
		
$pdf->FancyTable($header,$data2);

$pdf->Cell(0, 10, "", 0, 1, "C");

$pdf->Output();

?>