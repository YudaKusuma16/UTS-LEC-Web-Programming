<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mendapatkan data pengguna
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan data pengguna ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Cek apakah email pengguna menggunakan domain @admin.com
            if (strpos($user['email'], '@admin.com') !== false) {
                // Jika admin, redirect ke dashboard admin
                header("Location: dashboard_admin.php");
            } else {
                // Jika bukan admin, redirect ke dashboard user biasa
                header("Location: dashboard_user.php");
            }
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon tanda panah -->
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

        .login-container {
            background-color: rgba(44, 44, 44, 0.9); /* Transparansi background form */
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 400px;
            z-index: 1;
            position: relative;
        }

        .login-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .login-container p {
            font-size: 14px;
            color: #bbb;
        }

        .login-container input {
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .login-container .btn-primary {
            width: 100%;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .login-container .btn-primary:hover {
            background-color: #0056b3;
        }

        .login-container a {
            color: #007BFF;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        /* Hapus garis bawah dan outline untuk semua tautan */
        a {
            text-decoration: none; /* Menghilangkan garis bawah */
        }

        a:focus, a:active {
            outline: none; /* Menghilangkan garis biru saat fokus */
        }

        .login-container .text-white {
            color: #fff !important;
        }

        .login-container .forgot-password,
        .login-container .sign-up {
            font-size: 14px;
            margin-top: 10px;
        }

        /* Error message styling */
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <a href="index.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-wrap">
        <!-- Login Box -->
        <div class="login-container">
            <h2 class="text-center">LOGIN</h2>
            <p class="text-center">Please enter your login and password!</p>

            <!-- Tampilkan pesan kesalahan jika ada -->
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-primary mt-1">LOGIN</button>

                <div class="text-center forgot-password">
                <a href="forgot_password.php">Forgot password?</a>
                </div>
            </form>

            <div class="text-center sign-up">
                <p>Don't have an account? <a href="sign-up.php">Sign Up</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
