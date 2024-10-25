<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "event_management");

// Variable untuk menyimpan pesan error atau sukses
$error_message = "";
$success_message = "";

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Cek apakah ada token dalam URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek apakah token valid dan belum kedaluwarsa
    $stmt = $mysqli->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token valid, pengguna dapat mengatur ulang password
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                // Hash password baru
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Dapatkan email dari tabel password_resets
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Perbarui password di tabel users
                $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param('ss', $hashed_password, $email);
                $stmt->execute();

                // Hapus token reset password dari tabel
                $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
                $stmt->bind_param('s', $email);
                $stmt->execute();

                $success_message = "Password has been successfully updated.";
            } else {
                $error_message = "Passwords do not match!";
            }
        }
    } else {
        $error_message = "Invalid or expired token.";
    }
} else {
    $error_message = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon tanda panah -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            background: url('images/background_login.png') no-repeat center center fixed;
            background-size: cover;
        }

        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            cursor: pointer;
            z-index: 2;
            text-decoration: none;
        }

        .back-arrow i {
            color: white;
            font-size: 24px;
        }

        .container-wrap {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(8px); /* Efek blur di background luar */
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .reset-password-container {
            background-color: rgba(44, 44, 44, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 400px;
            z-index: 1;
            position: relative;
        }

        .reset-password-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .reset-password-container input {
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .reset-password-container .btn-primary {
            width: 100%;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .reset-password-container .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Gaya untuk pesan sukses dan error */
        .error-message, .success-message {
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>

    <a href="forgot_password.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-wrap">
        <div class="reset-password-container">
            <h2 class="text-center">Reset Password</h2>

            <!-- Tampilkan pesan sukses atau error -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($success_message)): ?>
                <form action="token.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                    <div class="mb-3">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
