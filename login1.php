<?php
$servername = "localhost";
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
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if any field is empty
    if (empty($email) || empty($password)) {
        die("Both fields are required.");
    }

    // Sanitize email
    $email = $conn->real_escape_string($email);

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Start a session and store user information
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['user_email'] = $email;

            // Redirect to index.html
            header("Location: index.html");
            exit();
        } else {
            die("Invalid password.");
        }
    } else {
        die("No account found with that email.");
    }

    $stmt->close();
}

$conn->close();
?>
