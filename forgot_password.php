<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "event_management");

// Variable untuk menyimpan pesan error
$error_message = "";

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Cek apakah email ada di database
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Buat token unik
        $token = bin2hex(random_bytes(50));

        // Set waktu kedaluwarsa token (1 jam dari sekarang)
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Hapus token lama yang mungkin ada untuk email yang sama
        $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();

        // Simpan token ke database (tabel password_resets)
        $stmt = $mysqli->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $email, $token, $expires_at);
        $stmt->execute();

        // Kirim email berisi link reset password
        $to = $email;
        $subject = 'Reset Your Password';
        $message = 'Here is your password reset link: http://localhost/your_project/token.php?token=' . $token;
        $headers = 'From: no-reply@yourdomain.com';

        mail($to, $subject, $message, $headers);

        // Redirect ke halaman token.php setelah mengirimkan email
        header("Location: token.php?token=$token");
        exit();
    } else {
        // Set pesan error jika email tidak ditemukan
        $error_message = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            background: url('images/background_login.png') no-repeat center center fixed;
            background-size: cover;
            position: relative; /* Ensure body can hold absolute elements */
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

        .forgot-password-container {
            background-color: rgba(44, 44, 44, 0.9); /* Transparansi background form */
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 400px;
            z-index: 1;
            position: relative;
        }

        .forgot-password-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .forgot-password-container p {
            font-size: 14px;
            color: #bbb;
        }

        .forgot-password-container input {
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .forgot-password-container .btn-primary {
            width: 100%;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .forgot-password-container .btn-primary:hover {
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

        .forgot-password-container .back-login {
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    
    <a href="login.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>
    
    <div class="container-wrap">
        <!-- Forgot Password Box -->
        <div class="forgot-password-container">
            <h2 class="text-center">Forgot Password</h2>
            <p class="text-center">Please enter your email to reset your password.</p>

            <!-- Tampilkan pesan error jika ada -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Tampilkan pesan sukses jika ada -->
            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form action="forgot_password.php" method="POST">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </form>

            <div class="text-center back-login">
                <p>Already have an account? <a href="login.php">Sign In</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
