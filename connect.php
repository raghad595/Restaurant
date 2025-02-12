<?php
$servername = "localhost";
$username = "root";  // replace with your database username
$password = "";      // replace with your database password
$dbname = "restaurant"; // replace with your database name

// Create connection
$connect = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
return $connect;
echo "Connected successfully";
// Example query
$sql = "SELECT * FROM users"; // Replace with your actual table
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
    }
} else {
    echo "0 results";
}
$connect->close();
?>
