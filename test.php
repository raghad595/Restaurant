<?php
include 'connect.php';

// Example query to check if the connection works
$sql = "SELECT * FROM users"; // replace with a valid table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["fullname"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
