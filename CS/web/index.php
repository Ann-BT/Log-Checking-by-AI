<?php
$host = 'db';
$user = 'appuser';
$pass = 'apppassword';
$dbname = 'vulnapp';

$conn = new mysqli($host, $user, $pass, $dbname);
$max_attempts = 10;
$attempt = 0;
while ($conn->connect_error && $attempt < $max_attempts) {
    usleep(500000); // Wait 0.5 seconds
    $conn = new mysqli($host, $user, $pass, $dbname);
    $attempt++;
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vulnerable query - no input sanitization
    $query = "SELECT * FROM users WHERE username = '$username'";
    echo "<p>Query: $query</p>";
    $result = $conn->query($query);

    if ($result === false) {
        echo "<p>Query Error: " . $conn->error . "</p>";
    } elseif ($result->num_rows > 0) {
        echo "<h2>Welcome, $username!</h2>";
    } else {
        echo "<h2>Invalid credentials!</h2>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Test</title>
</head>
<body>
    <h1>Login Page</h1>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Password:</label>
        <input type="text" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>