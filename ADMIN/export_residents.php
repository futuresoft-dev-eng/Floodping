<?php
require '../vendor/autoload.php'; 
include_once('../db/connection.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Resident ID');
$sheet->setCellValue('B1', 'First Name');
$sheet->setCellValue('C1', 'Middle Name');
$sheet->setCellValue('D1', 'Last Name');
$sheet->setCellValue('E1', 'Suffix');
$sheet->setCellValue('F1', 'Mobile Number');
$sheet->setCellValue('G1', 'Status');

$query = "
SELECT r.resident_id, r.first_name, r.middle_name, r.last_name, r.suffix, r.mobile_number, 
c.category_value AS account_status 
FROM residents r
LEFT JOIN categories c ON r.account_status_id = c.category_id";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $rowIndex = 2; 
    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $rowIndex, $row['resident_id']);
        $sheet->setCellValue('B' . $rowIndex, $row['first_name']);
        $sheet->setCellValue('C' . $rowIndex, $row['middle_name']);
        $sheet->setCellValue('D' . $rowIndex, $row['last_name']);
        $sheet->setCellValue('E' . $rowIndex, $row['suffix']);
        $sheet->setCellValue('F' . $rowIndex, $row['mobile_number']);
        $sheet->setCellValue('G' . $rowIndex, $row['account_status']);
        $rowIndex++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Residents_List.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>
