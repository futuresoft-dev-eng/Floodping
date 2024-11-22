<?php
include_once('../db/db_conn.php');
require '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT * FROM residents";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = [
                'Resident ID', 'First Name', 'Middle Name', 'Last Name', 'Suffix', 
                'Date of Birth', 'Mobile Number', 'Email Address', 'House Lot Number', 
                'Street/Subdivision Name', 'Barangay', 'Municipality', 'Account Status ID', 
                'Civil Status ID', 'Health Status ID', 'Sex ID', 'Socioeconomic Category ID'
            ];
            
            $sheet->fromArray($headers, NULL, 'A1');

            $rowIndex = 2; 
            while ($row = $result->fetch_assoc()) {
                $sheet->setCellValue("A$rowIndex", $row['resident_id']);
                $sheet->setCellValue("B$rowIndex", $row['first_name']);
                $sheet->setCellValue("C$rowIndex", $row['middle_name']);
                $sheet->setCellValue("D$rowIndex", $row['last_name']);
                $sheet->setCellValue("E$rowIndex", $row['suffix']);
                $sheet->setCellValue("F$rowIndex", $row['date_of_birth']);
                $sheet->setCellValue("G$rowIndex", $row['mobile_number']);
                $sheet->setCellValue("H$rowIndex", $row['email_address']);
                $sheet->setCellValue("I$rowIndex", $row['house_lot_number']);
                $sheet->setCellValue("J$rowIndex", $row['street_subdivision_name']);
                $sheet->setCellValue("K$rowIndex", $row['barangay']);
                $sheet->setCellValue("L$rowIndex", $row['municipality']);
                $sheet->setCellValue("M$rowIndex", $row['account_status_id']);
                $sheet->setCellValue("N$rowIndex", $row['civil_status_id']);
                $sheet->setCellValue("O$rowIndex", $row['health_status_id']);
                $sheet->setCellValue("P$rowIndex", $row['sex_id']);
                $sheet->setCellValue("Q$rowIndex", $row['socioeconomic_category_id']);
                $rowIndex++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="residents_export.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } else {
            echo "No data found in the residents table.";
        }
    } catch (Exception $e) {
        die("Error exporting data: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
