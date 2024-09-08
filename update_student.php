<?php

$conn = new mysqli("localhost", "user", "password", "school");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $city_code = $_POST['city'];
    $telephone = $_POST['telephone'];

    $sql = "UPDATE student SET fname = ?, lname = ?, city_code = ?,telephone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $fname, $lname, $city_code, $telephone, $student_id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();

    // Redirect back to the main page
    header("Location: index.php");
    exit();
}

$conn->close();
?>