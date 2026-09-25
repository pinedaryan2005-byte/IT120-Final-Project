<?php
// Set headers for JSON response
header("Content-Type: application/json");

// 1. Database Connection Settings (XAMPP Defaults)
$host = "localhost";
$db_user = "root";
$db_password = ""; // Default XAMPP MySQL password is blank
$db_name = "sun_son_solar";

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// 2. Get JSON data sent from JavaScript fetch()
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // 3. Hash the password for security
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

    // 4. Prepare SQL statement (Prevents SQL Injection)
    $stmt = $conn->prepare("INSERT INTO users (firstName, middleName, lastName, birthdate, gender, telNo, email, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "ssssssssss", 
        $data['firstName'], 
        $data['middleName'], 
        $data['lastName'], 
        $data['birthdate'], 
        $data['gender'], 
        $data['telNo'], 
        $data['email'], 
        $data['address'], 
        $data['username'], 
        $hashed_password
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No data received"]);
}

$conn->close();
?>