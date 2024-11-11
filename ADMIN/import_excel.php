<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require '../db/connection.php';

if (isset($_POST['import'])) {
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['xls', 'xlsx'];

        if (in_array($file_extension, $allowed_extensions)) {
            $file = $_FILES['file']['tmp_name'];

            try {
                $spreadsheet = IOFactory::load($file);
                $worksheet = $spreadsheet->getActiveSheet();
                $conn->begin_transaction();

                $stmt = $conn->prepare("INSERT INTO residents (first_name, middle_name, last_name, suffix, mobile_number) VALUES (?, ?, ?, ?, ?)");
                $checkStmt = $conn->prepare("SELECT resident_id FROM residents WHERE first_name = ? AND last_name = ?");

                foreach ($worksheet->getRowIterator(2) as $row) {
                    $data = array_map('trim', array_slice(array_column(iterator_to_array($row->getCellIterator()), 'value'), 0, 5));

                    list($first_name, $middle_name, $last_name, $suffix, $mobile_number) = $data;

                    $checkStmt->bind_param("ss", $first_name, $last_name);
                    $checkStmt->execute();
                    if ($checkStmt->get_result()->num_rows > 0) {
                        continue;
                    }

                    $stmt->bind_param("sssss", $first_name, $middle_name, $last_name, $suffix, $mobile_number);
                    $stmt->execute();
                }

                $conn->commit();
                echo "Residents imported successfully!";
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            }
            $stmt->close();
            $checkStmt->close();
        } else {
            echo "Invalid file type. Upload an Excel file (.xls or .xlsx).";
        }
    } else {
        echo "Please upload a file.";
    }
}

$conn->close();
?>
