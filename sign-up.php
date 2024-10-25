<?php
session_start();
include 'database.php'; // Include your database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Check if email is already registered
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Set session variables for the new user
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['email'] = $email;

                // Redirect to the login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error = "Error during registration: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        /* Back Arrow */
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
            z-index: 3;
            border: none;
            outline: none;
            text-decoration: none;
        }

        .back-arrow:focus,
        .back-arrow:active {
            outline: none;
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
            backdrop-filter: blur(8px);
        }
        .sign-up-container {
            background-color: rgba(44, 44, 44, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 400px;
            z-index: 1;
        }

        /* Modifikasi warna teks */
.sign-up-container p {
    color: #bbb; /* Warna abu-abu yang sama dengan di login.php */
}

/* Menghilangkan garis bawah pada tulisan Login */
.sign-up-container a {
    color: #007BFF; /* Warna untuk tulisan "Login" tetap */
    text-decoration: none; /* Menghilangkan garis bawah */
}

.sign-up-container a:hover {
    text-decoration: underline; /* Tambahkan underline saat di-hover */
}


        .sign-up-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .sign-up-container input {
            margin-bottom: 1rem;
            border-radius: 8px;
        }
        .sign-up-container .btn-primary {
            width: 100%;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }
        .sign-up-container .btn-primary:hover {
            background-color: #0056b3;
        }
        .sign-up-container .text-white {
            color: #fff !important;
        }
        .sign-up-container .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Back Arrow -->
    <a href="index.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-wrap">
        <div class="sign-up-container">
            <h2 class="text-center">Sign Up</h2>
            <p class="text-center">Create your account to join us!</p>

            <!-- Display error message if there's any -->
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="sign-up.php" method="POST">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php" class="text-primary">Login</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
