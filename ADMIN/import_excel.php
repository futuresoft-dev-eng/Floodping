<?php
echo "Script started<br>"; // Add this at the very top

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

require '../db/connection.php';

if (isset($_POST['import'])) {
    echo "Form submission detected<br>";

    if (!empty($_FILES['file']['name'])) {
        echo "File upload detected: " . $_FILES['file']['name'] . "<br>";

        $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['xls', 'xlsx'];

        if (in_array($file_extension, $allowed_extensions)) {
            $file = $_FILES['file']['tmp_name'];

            try {
                $spreadsheet = IOFactory::load($file);
                $worksheet = $spreadsheet->getActiveSheet();
                $conn->begin_transaction();

                // Prepare the insert and check statements
                $stmt = $conn->prepare("INSERT INTO residents (
                    first_name, middle_name, last_name, suffix, sex, date_of_birth,
                    mobile_number, email_address, civil_status, socioeconomic_category,
                    health_status, house_lot_number, street_subdivision_name, barangay,
                    municipality, account_status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $checkStmt = $conn->prepare("SELECT resident_id FROM residents WHERE first_name = ? AND last_name = ?");

                $rowCount = 0;
                foreach ($worksheet->getRowIterator(2) as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $data = [];
                    
                    foreach ($cellIterator as $cell) {
                        $data[] = trim($cell->getValue());
                    }

                    // Ensure row has exactly 16 columns to match database fields
                    if (count($data) < 16) {
                        echo "Skipping row due to insufficient columns: ";
                        print_r($data);
                        echo "<br>";
                        continue;
                    }

                    // Map each column in the row to variables
                    list(
                        $first_name, $middle_name, $last_name, $suffix, $sex, $date_of_birth,
                        $mobile_number, $email_address, $civil_status, $socioeconomic_category,
                        $health_status, $house_lot_number, $street_subdivision_name, $barangay,
                        $municipality, $account_status
                    ) = $data;

                    // Check for duplicates based on first and last name
                    $checkStmt->bind_param("ss", $first_name, $last_name);
                    $checkStmt->execute();
                    $result = $checkStmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "Duplicate entry found for: $first_name $last_name<br>";
                        continue; // Skip duplicate entries
                    }

                    // Insert new record
                    $stmt->bind_param(
                        "ssssssssssssssss",
                        $first_name, $middle_name, $last_name, $suffix, $sex, $date_of_birth,
                        $mobile_number, $email_address, $civil_status, $socioeconomic_category,
                        $health_status, $house_lot_number, $street_subdivision_name, $barangay,
                        $municipality, $account_status
                    );
                    
                    if ($stmt->execute()) {
                        $rowCount++;
                    } else {
                        echo "Failed to insert row: ";
                        print_r($data);
                        echo "<br> Error: " . $stmt->error . "<br>";
                    }
                }

                $conn->commit();
                echo $rowCount > 0 ? "$rowCount residents imported successfully!" : "No new residents were imported (all may be duplicates).";
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            } finally {
                $stmt->close();
                $checkStmt->close();
            }
        } else {
            echo "Invalid file type. Please upload an Excel file (.xls or .xlsx).<br>";
        }
    } else {
        echo "Please upload a file.<br>";
    }
} else {
    echo "Form not submitted.<br>";
}

$conn->close();
?>
