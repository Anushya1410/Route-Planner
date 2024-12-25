<?php

$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "mydatabase"; // Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if any field is empty
    if (empty($name) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    // Sanitize form data
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to index.html after successful registration
        header("Location: index.html");
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
