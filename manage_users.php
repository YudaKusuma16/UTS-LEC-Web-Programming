<?php
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

// Fetch user data excluding admin emails from the users table
$sql = "SELECT id, name, email FROM users WHERE email NOT LIKE '%@admin.com%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
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

        .manage-users-container {
            background-color: rgba(44, 44, 44, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 700px;
            z-index: 1;
            position: relative;
        }

        .manage-users-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .manage-users-container table {
            width: 100%;
            background-color: #333;
            border-radius: 8px;
            overflow: hidden;
        }

        .manage-users-container th, .manage-users-container td {
            padding: 10px;
            text-align: left;
            color: #fff;
        }

        .manage-users-container th {
            background-color: #444;
        }

        .manage-users-container .btn-danger {
            background-color: #ff4d4d;
            border: none;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .manage-users-container .btn-danger:hover {
            background-color: #e60000;
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
    <div class="manage-users-container">
        <h2>Manage Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>REGISTERED EVENTS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>No events registered</td>"; // Placeholder for registered events
                        echo '<td><button class="btn btn-danger" onclick="confirmDelete(' . $row['id'] . ')">Delete</button></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

<script>
    // Function to show confirmation dialog
    function confirmDelete(userId) {
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
                // Redirect to delete_user.php with the user ID
                window.location.href = "delete_user.php?id=" + userId;
            }
        });
    }
</script>

</body>
</html>
