<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Ambil informasi user berdasarkan session
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Gunakan nama user dari database, jika tidak ada gunakan default
$user_name = isset($user['name']) ? $user['name'] : 'User';

// Cek apakah ada gambar profil yang diunggah, jika tidak gunakan gambar default
$profile_image = !empty($user['profile_image']) ? 'uploads/' . basename($user['profile_image']) : 'images/fotoguest.jpeg';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        body {
            min-height: 100vh;
            overflow-x: hidden;
            background: linear-gradient(#2b1055, #7598de);
        }
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
        }
        header .logo {
            color: #fff;
            font-weight: 900 !important;
            text-decoration: none;
            font-size: 2em;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        header .profile-dropdown {
            position: relative;
            display: flex;
            align-items: center;
        }
        header .profile-dropdown img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            margin-left: 10px;
        }
        header .profile-dropdown .dropdown-menu {
            background-color: rgba(44, 44, 44, 0.9);
            border: none;
            border-radius: 10px;
        }
        header .profile-dropdown .dropdown-menu a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            transition: background 0.3s ease;
        }
        header .profile-dropdown .dropdown-menu a:hover {
            background-color: #007BFF;
        }

        .profile-dropdown a {
    font-weight: 600;
    color: white;
    text-decoration: none;
    margin-right: 15px;
    border: 2px solid transparent; /* Border default transparan */
    padding: 5px 10px;
    border-radius: 25px; /* Memberikan efek rounded pada hover */
    transition: all 0.3s ease; /* Animasi pada hover */
}

.profile-dropdown a:hover {
    background-color: rgba(255, 255, 255, 0.2); /* Warna latar putih transparan */
    border: 2px solid white; /* Border putih saat hover */
}


        .sec {
            position: relative;
            padding: 50px 50px;
            background: linear-gradient(to bottom, #1c0522, #2b1055);
        }
        .sec h2 {
            font-size: 3.5em;
            margin-bottom: 20px;
            margin-top: 20px;
            color: #fff;
            text-align: center;
        }
        .welcome-message {
            font-size: 1.5em;
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
        }
        .event-card {
            background-color: rgba(44, 44, 44, 0.9);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            margin-bottom: 15.5px;
        }
        .event-card img {
            width: 100%;
            height: auto;
        }
        .event-card .event-info {
            padding: 20px;
            text-align: left;
        }
        .event-card .event-info h3 {
            font-size: 1.5em;
            color: #fff;
            margin-bottom: 10px;
        }
        .event-card .event-info p {
            color: #bbb;
            font-size: 1em;
        }
        .event-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        /* Cancel button style */
        .cancel-btn {
            background-color: #d9534f;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .cancel-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php" class="logo">EVENTIFY</a>

    <!-- Tambahkan tautan dashboard di sini -->
    <div class="profile-dropdown">
        <!-- Tautan Dashboard -->
        <a href="dashboard_user.php" class="text-white mx-3" style="text-decoration: none;">D A S H B O A R D</a>
        <span class="text-white"><?php echo $user_name; ?></span>
        <img src="<?php echo $profile_image; ?>" alt="Profile" class="rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>


    <div class="sec" id="sec">
        <h2>Your Registered Events</h2>
        <p class="welcome-message">Here are the events you have registered for, <?php echo $user_name; ?>!</p>

        <div class="container">
            <div class="row">
                <?php
                // Koneksi ke database
                include 'database.php';

                // Query untuk mengambil event yang sudah didaftarkan oleh user
                $sql = "SELECT events.* FROM events 
                        JOIN user_tickets ON events.id = user_tickets.event_id 
                        WHERE user_tickets.user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Loop untuk menampilkan event yang terdaftar
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4">';
                        echo '<div class="event-card">';
                        echo '<img src="' . $row["image"] . '" class="d-block w-100" alt="' . $row["title"] . '">';
                        echo '<div class="event-info">';
                        echo '<h3>' . $row["title"] . '</h3>';
                        echo '<p><strong>Date:</strong> ' . date('D, M d', strtotime($row["date"])) . '</p>';
                        echo '<p><strong>Location:</strong> ' . $row["location"] . '</p>';
                        echo '<p><strong>Time:</strong> ' . $row["time"] . '</p>';
                        echo '<p><strong>Price:</strong> ' . number_format($row["price"], 2) . ' IDR</p>';
                        echo '<p class="status"><strong>Status:</strong> OPEN</p>';
                        echo '<div class="event-actions">';
                        echo '<a href="javascript:void(0);" onclick="confirmCancel(' . $row["id"] . ');" class="cancel-btn">Cancel Registration</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No registered events found</p>';
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

    <script>
        // Function to show confirmation dialog
        function confirmCancel(eventId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to cancel registration PHP script
                    window.location.href = "cancel_registration.php?event_id=" + eventId;
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
