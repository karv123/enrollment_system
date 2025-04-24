<?php
session_start();
require 'enrollment.php'; // Adjust to your actual DB connection file
require 'vendor/autoload.php'; // Ensure PHPExcel or PhpSpreadsheet is installed and loaded

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION['admin_name'])) 

// Fetch all student data for the export
$query = "SELECT grade_level, section, fullname, email, status FROM enroll";
$result = $conn->query($query);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column headers
$sheet->setCellValue('A1', 'Grade Level')
      ->setCellValue('B1', 'Section')
      ->setCellValue('C1', 'Full Name')
      ->setCellValue('D1', 'Email')
      ->setCellValue('E1', 'Status');

// Write data to spreadsheet
$row = 2; // Start from row 2 to leave space for headers
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['grade_level'])
          ->setCellValue('B' . $row, $data['section'])
          ->setCellValue('C' . $row, $data['fullname'])
          ->setCellValue('D' . $row, $data['email'])
          ->setCellValue('E' . $row, ucfirst($data['status']));
    $row++;
}

// Set headers to force the download of the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="enrollment_report.xlsx"');
header('Cache-Control: max-age=0');

// Create the writer and save the file to the output stream
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
