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
    <title>User Dashboard | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Tambahkan script untuk Bootstrap dropdown -->
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

        header .profile-dropdown .dropdown-menu {
            background-color: #3a3a3a; /* Warna abu-abu yang sesuai */
            border: none;
            border-radius: 10px;
        }

        header .profile-dropdown .dropdown-menu a {
            color: #fff; /* Warna teks putih */
        }

        header .profile-dropdown .dropdown-menu a:hover {
            background-color: #555555; /* Warna hover yang sedikit lebih terang */
        }

        header .profile-dropdown img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            margin-left: 10px;
        }

        .sec {
            position: relative;
            padding: 100px;
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

        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
        }

        .event-actions a {
            flex: 1;
            text-align: center;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .event-actions a:hover {
            background-color: #0056b3;
        }

        .status {
            color: #28a745;
            font-weight: bold;
        }

        /* Styling for TICKETS link */
        .tickets-link {
            text-decoration: none;
            color: white;
            margin-right: 20px;
            padding: 6px 15px;
            border-radius: 20px;
            transition: background 0.3s ease;
        }

        .tickets-link:hover {
            background: white;
            color: #2b1055;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php" class="logo">EVENTIFY</a>

    <!-- Profile Dropdown -->
    <div class="profile-dropdown dropdown">
        <a href="your_tickets.php" class="tickets-link">T I C K E T S</a>
        <span class="text-white"><?php echo $user_name; ?></span>
        <img src="<?php echo $profile_image; ?>" alt="Profile" class="rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
            <li><a class="dropdown-item" href="your_tickets.php">Your Tickets</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="sec" id="sec">
    <h2>All On Going Events</h2>
    <p class="welcome-message">Welcome, <?php echo $user_name; ?>!</p>

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

                    // Tampilkan status event berdasarkan jumlah partisipan
                    if ($total_participants >= $row['max_participants']) {
                        echo '<p class="status"><strong>Status:</strong> CLOSED</p>';
                        echo '<button class="btn btn-secondary" disabled>Event Full</button>';
                    } else {
                        echo '<p class="status"><strong>Status:</strong> OPEN</p>';
                        echo '<div class="event-actions d-flex justify-content-between">';
                        echo '<a href="javascript:void(0);" onclick="registerEvent(' . $row["id"] . ')" class="btn btn-primary">Register</a>';
                        echo '<a href="event_details.php?event_id=' . $row["id"] . '" class="btn btn-primary">Event Details</a>';
                        echo '</div>';
                    }

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
function registerEvent(eventId) {
    Swal.fire({
        title: 'Buy Tickets',
        html: `
            <label for="numTickets" style="display: block; text-align: left;">Number of Tickets (Max 3)</label>
            <input type="number" id="numTickets" class="swal2-input" placeholder="Number of Tickets (Max 3)" max="3">
            
            <label for="paymentMethod" style="display: block; text-align: left;">Payment Method</label>
            <select id="paymentMethod" class="swal2-input">
                <option value="OVO">OVO</option>
                <option value="GoPay">GoPay</option>
                <option value="Dana">Dana</option>
            </select>
            
            <label for="phoneNumber" style="display: block; text-align: left;">Phone Number</label>
            <input type="text" id="phoneNumber" class="swal2-input" placeholder="Phone Number">
            
            <label for="paymentAmount" style="display: block; text-align: left;">Payment Amount</label>
            <input type="text" id="paymentAmount" class="swal2-input" placeholder="Payment Amount">`,
        confirmButtonText: 'Confirm Purchase',
        focusConfirm: false,
        preConfirm: () => {
            const numTickets = document.getElementById('numTickets').value;
            const paymentMethod = document.getElementById('paymentMethod').value;
            const phoneNumber = document.getElementById('phoneNumber').value;
            const paymentAmount = document.getElementById('paymentAmount').value;

            if (!numTickets || !paymentMethod || !phoneNumber || !paymentAmount) {
                Swal.showValidationMessage(`Please enter all fields`);
            }

            return { numTickets: numTickets, paymentMethod: paymentMethod, phoneNumber: phoneNumber, paymentAmount: paymentAmount }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Simulate processing
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we process your payment.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                // Kirim data pendaftaran ke server menggunakan AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'register_ticket.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Jika pendaftaran sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful',
                            text: 'You have successfully registered for the event!',
                            confirmButtonText: 'Awesome!'
                        }).then(() => {
                            // Redirect ke halaman your_tickets.php
                            window.location.href = 'your_tickets.php';
                        });
                    }
                };
                xhr.send(`event_id=${eventId}&num_tickets=${result.value.numTickets}&payment_method=${result.value.paymentMethod}&phone_number=${result.value.phoneNumber}&payment_amount=${result.value.paymentAmount}`);
            }, 1000); // Simulasi delay 1 detik
        }
    });
}
</script>

</body>
</html>
