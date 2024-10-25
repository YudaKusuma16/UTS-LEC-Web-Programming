<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Ambil informasi admin berdasarkan session
$user_id = $_SESSION['user_id'];
$sql = "SELECT email, name, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Cek apakah email admin menggunakan @admin.com
if (!str_ends_with($admin['email'], '@admin.com')) {
    echo "Access denied. Only admins can access this page.";
    exit();
}

// Cek apakah admin berhasil ditemukan berdasarkan ID
if ($admin) {
    // Gunakan nama admin dari database, jika tidak ada gunakan default
    $admin_name = isset($admin['name']) ? $admin['name'] : 'Admin';
} else {
    // Jika tidak ditemukan, inisialisasi dengan default
    $admin_name = 'Admin';
}

// Cek apakah ada gambar profil yang diunggah, jika tidak gunakan gambar default
$profile_image = !empty($admin['profile_image']) ? 'uploads/' . basename($admin['profile_image']) : 'images/fotoguest.jpeg';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

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
            padding: 30px 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
            background: linear-gradient(#2b1055, #1c0522);
        }

        header .logo {
            color: #fff;
            font-weight: 900 !important;
            text-decoration: none;
            font-size: 2em;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        header .admin-nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 1.1em;
        }

        .sec {
            position: relative;
            padding: 100px;
            background: linear-gradient(to bottom, #1c0522, #2b1055);
        }

        .sec h2 {
            font-size: 3em;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .sec .welcome-message {
            text-align: center;
            font-size: 1.5em;
            color: white;
            margin-bottom: 30px;
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

        .admin-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            gap: 10px;
        }

        .admin-actions a {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            flex: 1;
            text-align: center;
        }

        .admin-actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <a href="dashboard_admin.php" class="logo">EVENTIFY</a>

    <!-- Admin Navigation -->
    <div class="admin-nav">
        <a href="create_event.php">Create Event</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_admin.php">Manage Admin</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="sec" id="sec">
    <h2>Manage Events</h2>
    <p class="welcome-message">Welcome, Admin <?php echo $admin_name; ?>!</p> <!-- Tambahkan ini untuk menyambut admin -->

    <div class="container">
        <div class="row">
            <?php
            // Koneksi ke database
            include 'database.php';

            // Query untuk mengambil semua event dari database
            $sql = "SELECT * FROM events";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop untuk menampilkan setiap event
                while ($row = $result->fetch_assoc()) {
                    // Hitung jumlah partisipan saat ini
                    $event_id = $row['id'];
                    $sql_participants = "SELECT SUM(num_tickets) AS total_participants FROM user_tickets WHERE event_id = $event_id";
                    $result_participants = $conn->query($sql_participants);
                    $total_participants = $result_participants->fetch_assoc()['total_participants'];

                    echo '<div class="col-md-4">';
                    echo '<div class="event-card">';
                    echo '<img src="' . $row["image"] . '" class="d-block w-100" alt="' . $row["title"] . '">';
                    echo '<div class="event-info">';
                    echo '<h3>' . $row["title"] . '</h3>';
                    echo '<p><strong>Date:</strong> ' . date('D, M d', strtotime($row["date"])) . '</p>';
                    echo '<p><strong>Location:</strong> ' . $row["location"] . '</p>';
                    echo '<p><strong>Time:</strong> ' . $row["time"] . '</p>';
                    echo '<p><strong>Price:</strong> ' . number_format($row["price"], 2) . ' IDR</p>';
                    echo '<p><strong>Current Participants:</strong> ' . ($total_participants ? $total_participants : 0) . '</p>';
                    echo '<p><strong>Max Participants:</strong> ' . $row['max_participants'] . '</p>';

                    // Tampilkan status event
                    if ($total_participants >= $row['max_participants']) {
                        echo '<p class="status"><strong>Status:</strong> CLOSED</p>';
                    } else {
                        echo '<p class="status"><strong>Status:</strong> OPEN</p>';
                    }

                    // Admin Action buttons
                    echo '<div class="admin-actions">';
                    echo '<a href="edit_event.php?event_id=' . $row["id"] . '">Edit</a>';
                    echo '<a href="javascript:void(0);" onclick="deleteEvent(' . $row["id"] . ')" class="btn btn-danger">Delete</a>';
                    echo '<a href="view_registrants.php?event_id=' . $row["id"] . '">Registrants</a>';
                    echo '</div>';
                    
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No events found</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

<script>
function deleteEvent(eventId) {
    Swal.fire({
        title: 'Apakah anda yakin ingin menghapusnya?',
        text: 'Anda tidak akan dapat mengembalikannya!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to delete_event.php to handle deletion
            window.location.href = 'delete_event.php?event_id=' + eventId;
        }
    })
}
</script>

</body>
</html>
