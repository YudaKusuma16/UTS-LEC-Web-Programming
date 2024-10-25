<?php
session_start();
session_destroy(); // Menghapus semua data session
header("Location: index.php"); // Mengarahkan kembali ke halaman utama setelah logout
exit;
?>
