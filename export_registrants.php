<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Cek apakah ID event diberikan
if (!isset($_GET['event_id'])) {
    echo "Event ID not provided!";
    exit();
}

$event_id = $_GET['event_id'];

// Ambil registrants untuk event dari database
$sql = "SELECT u.name, u.email, ut.num_tickets, ut.total_payment, ut.payment_method, ut.phone_number 
        FROM user_tickets ut 
        JOIN users u ON ut.user_id = u.id 
        WHERE ut.event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Set header untuk file CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=registrants_event_' . $event_id . '.csv');

// Buat file output
$output = fopen('php://output', 'w');

// Tulis header kolom
fputcsv($output, ['Name', 'Email', 'Jumlah Tiket', 'Total Pembayaran (IDR)', 'Payment Method', 'Phone Number'], ';');

// Tulis data registrants
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row, ';'); // Pastikan untuk menyertakan pemisah koma
}

fclose($output);
$conn->close();
exit();
?>
