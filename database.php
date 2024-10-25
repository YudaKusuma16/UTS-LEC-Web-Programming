<?php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL kamu
$password = ""; // Sesuaikan dengan password MySQL kamu
$dbname = "event_management"; // Sesuaikan dengan nama database kamu

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
