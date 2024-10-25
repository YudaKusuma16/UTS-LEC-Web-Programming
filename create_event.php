<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Proses formulir ketika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $max_participants = $_POST['max_participants'];
    $total_tickets = $_POST['total_tickets'];
    $ticket_price = $_POST['ticket_price'];
    $status = $_POST['status'];
    $image = $_FILES['event_image']['name'];

    // Menyimpan gambar ke folder uploads
    $target_dir = "images/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file);

    // Query untuk menyimpan data event ke database
    $sql = "INSERT INTO events (title, description, date, time, location, max_participants, total_tickets, price, image, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssiiisss', $title, $description, $date, $time, $location, $max_participants, $total_tickets, $ticket_price, $target_file, $status);

    if ($stmt->execute()) {
        // Menggunakan SweetAlert2 untuk notifikasi sukses dan mengarahkan ke dashboard_admin.php
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                  Swal.fire({
                    icon: 'success',
                    title: 'Event berhasil dibuat!',
                    showConfirmButton: false,
                    timer: 2000
                  }).then(function() {
                    window.location.href = 'dashboard_admin.php';
                  });
                });
              </script>";
    } else {
        // Notifikasi error menggunakan SweetAlert2 jika gagal membuat event
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal membuat event!',
                    text: 'Silakan coba lagi.',
                  });
                });
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        .create-event-container {
            background-color: rgba(44, 44, 44, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 500px;
            z-index: 1;
            position: relative;
        }

        .create-event-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .create-event-container label {
            font-weight: 500;
        }

        .create-event-container input,
        .create-event-container select,
        .create-event-container textarea {
            margin-bottom: 1rem;
            border-radius: 8px;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .create-event-container .btn-primary {
            width: 100%;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .create-event-container .btn-primary:hover {
            background-color: #0056b3;
        }

        .create-event-container .btn-secondary {
            width: 100%;
            background-color: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .create-event-container .btn-secondary:hover {
            background-color: #565e64;
        }

        /* Tambahkan gaya back arrow */
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
    <div class="create-event-container">
        <h2>Create Event</h2>
        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Event Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Event Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <div class="mb-3">
                <label for="max_participants" class="form-label">Maximum Participants</label>
                <input type="number" class="form-control" id="max_participants" name="max_participants" required>
            </div>

            <div class="mb-3">
                <label for="total_tickets" class="form-label">Total Tickets</label>
                <input type="number" class="form-control" id="total_tickets" name="total_tickets" required>
            </div>

            <div class="mb-3">
                <label for="ticket_price" class="form-label">Ticket Price</label>
                <input type="number" class="form-control" id="ticket_price" name="ticket_price" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="event_image" class="form-label">Event Image</label>
                <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
    </div>
</div>

</body>
</html>
