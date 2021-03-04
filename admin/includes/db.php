<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Povezivanje s bazon neuspješno: " . $conn->connect_error);
}
