<?php
// Include database connection
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "flood_ping";

$conn = new mysqli($host, $user, $pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if data is received via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $level = $_POST['level'];
    $height = $_POST['height'];
    $speed = $_POST['speed'];

    // Insert data into the database
    $sql = "INSERT INTO flood_alerts (water_level, height, speed, timestamp)
            VALUES ('$level', '$height', '$speed', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Data saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No data received!";
}

$conn->close();
?>
