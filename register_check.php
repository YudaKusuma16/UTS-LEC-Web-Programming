<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman register atau login
    header("Location: register.php");
    exit();
} else {
    // Jika sudah login, arahkan ke halaman register event
    header("Location: register_event.php");
    exit();
}
?>
