<?php

echo "Hello, World from Docker! <br>";
echo "Hello ECS! <br>";
echo '<img src="https://www.docker.com/sites/default/files/horizontal.png">';
print_r($_ENV);
$host = $_ENV["DB_HOST"];

// Database use name
$user = $_ENV["DB_USER"];

//database user password
$pass = $_ENV["DB_PASSWORD"];

// check the MySQL connection status
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to MySQL server successfully!";
}

?>