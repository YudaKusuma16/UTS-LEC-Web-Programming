<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Ambil data dari AJAX
$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];
$num_tickets = $_POST['num_tickets'];
$payment_method = $_POST['payment_method'];
$phone_number = $_POST['phone_number'];
$payment_amount = $_POST['payment_amount'];

// Simpan data ke tabel user_tickets (simpan jumlah tiket yang dipesan)
$sql = "INSERT INTO user_tickets (user_id, event_id, num_tickets, created_at, payment_method, phone_number) VALUES (?, ?, ?, NOW(), ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iiiss', $user_id, $event_id, $num_tickets, $payment_method, $phone_number);

if ($stmt->execute()) {
    // Update jumlah partisipan pada tabel events
    $update_sql = "UPDATE events SET current_participants = current_participants + ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ii', $num_tickets, $event_id);
    $update_stmt->execute();
    $update_stmt->close();

    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
