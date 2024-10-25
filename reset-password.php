<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "signup");

// Cek koneksi
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    
    // Cek apakah token valid dan belum kadaluarsa
    $stmt = $mysqli->prepare("SELECT * FROM password_resets WHERE token = ? AND expires >= ?");
    $current_time = date('U');
    $stmt->bind_param('si', $token, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ambil email dari token
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Update password di tabel `data`
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("UPDATE data SET password = ? WHERE email = ?");
        $stmt->bind_param('ss', $hashed_password, $email);
        $stmt->execute();

        // Hapus token setelah digunakan
        $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();

        echo "Your password has been updated successfully!";
    } else {
        echo "Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <form action="reset-password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="mb-3">
                <label for="password">New Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
