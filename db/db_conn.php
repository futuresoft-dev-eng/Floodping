<?php
// AWS RDS MySQL connection details
$servername = "floodping.cfime6cu440g.us-east-1.rds.amazonaws.com"; // Replace with your RDS endpoint
$username = "floodping";  // Replace with your RDS username
$password = "floodpingaccount";  // Replace with your RDS password
$dbname = "floodping";        // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>
