<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Ambil event_id dari URL
$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

// Hapus tiket dari user_tickets
$sql = "DELETE FROM user_tickets WHERE user_id = ? AND event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $event_id);

if ($stmt->execute()) {
    // Berhasil menghapus, redirect ke halaman your_tickets.php
    header("Location: your_tickets.php");
    exit();
} else {
    echo "Error cancelling registration.";
}

$stmt->close();
$conn->close();
?>
