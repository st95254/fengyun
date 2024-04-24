<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $newPrice = mysqli_real_escape_string($conn, $_POST['price']);

    $sql = "UPDATE product SET price = '$newPrice' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
