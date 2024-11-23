<?php
include_once('../db/db_conn.php');
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

    if (!in_array($file['type'], $allowedTypes)) {
        header("Location: accountservices.php?error=Invalid+file+type.+Only+.xls+and+.xlsx+files+are+allowed.");
        exit();
    }

    try {
        $spreadsheet = IOFactory::load($file['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        foreach ($data as $index => $row) {
            if ($index === 0) continue; // Skip the header row

            $resident_id = $row[0];
            $first_name = $row[1];
            $middle_name = $row[2];
            $last_name = $row[3];
            $suffix = $row[4];
            $date_of_birth = $row[5];
            $mobile_number = $row[6];
            $email_address = $row[7];
            $house_lot_number = $row[8];
            $street_subdivision_name = $row[9];
            $barangay = $row[10] ?? 'Bagbag';
            $municipality = $row[11] ?? 'Quezon City';
            $account_status_id = $row[12] ?? 1;
            $civil_status_id = $row[13];
            $health_status_id = $row[14];
            $sex_id = $row[15];
            $socioeconomic_category_id = $row[16];

            // Validate mobile number
            if (!preg_match('/^09\d{9}$/', $mobile_number)) {
                header("Location: accountservices.php?error=Invalid+mobile+number.+Must+start+with+09+and+be+11+digits.");
                exit();
            }

            // Validate email address
            if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email_address)) {
                header("Location: accountservices.php?error=Invalid+email+address.+Must+end+with+@gmail.com.");
                exit();
            }

            // Validate age
            $dob = new DateTime($date_of_birth);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
            if ($age < 18) {
                header("Location: accountservices.php?error=Invalid+age.+Resident+must+be+at+least+18+years+old.");
                exit();
            }

            // Validate foreign key columns
            $fkColumns = [
                'civil_status_id' => $civil_status_id,
                'health_status_id' => $health_status_id,
                'sex_id' => $sex_id,
                'socioeconomic_category_id' => $socioeconomic_category_id
            ];

            foreach ($fkColumns as $columnName => $value) {
                if (!empty($value)) {
                    $checkFKQuery = $conn->prepare("SELECT 1 FROM categories WHERE category_id = ?");
                    $checkFKQuery->bind_param("i", $value);
                    $checkFKQuery->execute();
                    $fkResult = $checkFKQuery->get_result();

                    if ($fkResult->num_rows === 0) {
                        header("Location: accountservices.php?error=Invalid+$columnName+value+$value.");
                        exit();
                    }
                }
            }

            // Check for duplicate resident_id
            $checkQuery = $conn->prepare("SELECT 1 FROM residents WHERE resident_id = ?");
            $checkQuery->bind_param("s", $resident_id);
            $checkQuery->execute();
            $result = $checkQuery->get_result();

            if ($result->num_rows === 0) {
                $insertQuery = $conn->prepare("
                    INSERT INTO residents (
                        resident_id, first_name, middle_name, last_name, suffix, date_of_birth, mobile_number, email_address,
                        house_lot_number, street_subdivision_name, barangay, municipality, account_status_id, civil_status_id,
                        health_status_id, sex_id, socioeconomic_category_id
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $insertQuery->bind_param(
                    "ssssssssssssiiiii",
                    $resident_id, $first_name, $middle_name, $last_name, $suffix, $date_of_birth, $mobile_number,
                    $email_address, $house_lot_number, $street_subdivision_name, $barangay, $municipality,
                    $account_status_id, $civil_status_id, $health_status_id, $sex_id, $socioeconomic_category_id
                );
                $insertQuery->execute();
            }
        }

        header("Location: accountservices.php?message=File+imported+successfully");
        exit();
    } catch (Exception $e) {
        header("Location: accountservices.php?error=Error+loading+file:+".urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: accountservices.php?error=No+file+uploaded.");
    exit();
}
