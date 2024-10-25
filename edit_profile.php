<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$sql = "SELECT name, email, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $profile_image = $user['profile_image']; // Default to current image
    
    // Check if a new image was uploaded
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $image_name = $_FILES['profile_image']['name'];
        $image_tmp = $_FILES['profile_image']['tmp_name'];
        $image_folder = "uploads/";
        
        // Move uploaded file to the uploads directory
        if (move_uploaded_file($image_tmp, $image_folder . $image_name)) {
            $profile_image = $image_folder . $image_name;
        }
    }

    // Update user profile in the database
    $update_sql = "UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssi', $name, $email, $profile_image, $user_id);
    $update_stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('images/background_login.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
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
            border: none; /* Menghilangkan garis biru */
            outline: none; /* Menghilangkan outline saat elemen di-klik */
            text-decoration: none;
        }

        .back-arrow i {
            color: white;
            font-size: 24px;
        }

        .container-wrap {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(8px); /* Menambahkan efek blur */
            z-index: 0; /* Membuat background blur di belakang */
        }

        .profile-form {
            background-color: rgba(44, 44, 44, 0.9); /* Transparansi untuk kartu */
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            z-index: 1;
        }

        .profile-form h3 {
            margin-bottom: 20px;
            color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .text-white {
            color: white !important;
        }
    </style>
</head>
<body>

    <a href="dashboard_user.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-wrap"></div> <!-- Tambahkan elemen ini untuk blur -->

    <div class="profile-form">
        <h3>Edit Profile</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group text-center">
                <img src="<?php echo $user['profile_image'] ? $user['profile_image'] : 'images/fotoguest.jpg'; ?>" alt="Profile Image" class="profile-img">
            </div>
            <div class="form-group">
                <label for="profile_image" class="text-white">Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
            </div>
            <div class="form-group">
                <label for="name" class="text-white">Username</label>
                <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email" class="text-white">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
