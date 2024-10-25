<?php
session_start();
// Database connection configuration
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Admin form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate that the email contains @admin.com
    if (!str_ends_with($email, '@admin.com')) {
        $error = "Email must end with @admin.com!";
    } elseif ($password !== $confirmPassword) {
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

            // Insert the new admin into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "Admin added successfully!";
            } else {
                $error = "Error during registration: " . $stmt->error;
            }
        }
    }
}

// Fetch admin data
$sql = "SELECT id, name, email FROM users WHERE email LIKE '%@admin.com%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            font-family: 'Poppins', sans-serif;
            background: url('images/background_login.png') no-repeat center center fixed;
            background-size: cover;
        }

        .container-wrap {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 20px;
            backdrop-filter: blur(8px);
            box-sizing: border-box;
        }

        .manage-admin-container {
            background-color: rgba(44, 44, 44, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 700px;
            z-index: 1;
            position: relative;
        }

        .manage-admin-container .card-header {
            background-color: #444; /* Abu-abu gelap */
            border-radius: 8px 8px 0 0; /* Menjaga sudut membulat */
            padding: 15px;
            color: #fff; /* Warna teks putih */
        }

        .manage-admin-container .card-body {
            background-color: #333; /* Abu-abu gelap selaras */
            border-radius: 8px;
            padding: 20px;
        }

        .manage-admin-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .manage-admin-container input,
        .manage-admin-container select,
        .manage-admin-container textarea {
            margin-bottom: 1rem;
            border-radius: 8px;
            background-color: #fff; /* Latar belakang putih */
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #555; /* Menambahkan border untuk kontras */
        }

        .manage-admin-container table {
            width: 100%;
            background-color: #333;
            border-radius: 8px;
            overflow: hidden;
        }

        .manage-admin-container th, .manage-admin-container td {
            padding: 10px;
            text-align: left;
            color: #fff;
        }

        .manage-admin-container th {
            background-color: #444;
        }

        .manage-admin-container .btn-danger {
            background-color: #ff4d4d;
            border: none;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .manage-admin-container .btn-danger:hover {
            background-color: #e60000;
        }

        .manage-admin-container .btn-primary {
            width: 100%;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
        }

        .manage-admin-container .btn-primary:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>

<!-- Tambahkan back arrow di sini -->
<a href="dashboard_admin.php" class="back-arrow">
    <i class="fas fa-arrow-left"></i>
</a>

<div class="container-wrap">
    <div class="manage-admin-container">
        <h2>Manage Admins</h2>

        <!-- Add Admin Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Admin</h4>
            </div>
            <div class="card-body">
                <form action="manage_admin.php" method="POST">
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
                    <button type="submit" class="btn btn-primary">Add Admin</button>
                </form>
            </div>
        </div>

        <!-- Display Admins Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo '<td><button class="btn btn-danger" onclick="confirmDelete(' . $row['id'] . ')">Delete</button></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No admins found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

<script>
    <?php if (isset($error)): ?>
        // Display error alert
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $error; ?>'
        });
    <?php endif; ?>

    <?php if (isset($success)): ?>
        // Display success alert
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $success; ?>'
        });
    <?php endif; ?>

    // Function to show confirmation dialog
    function confirmDelete(adminId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete_admin.php with the admin ID
                window.location.href = "delete_admin.php?id=" + adminId;
            }
        });
    }
</script>

</body>
</html>
