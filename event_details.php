<?php
// Koneksi ke database
include 'database.php';

// Ambil ID event dari URL
$event_id = $_GET['event_id'];

// Query untuk mendapatkan detail event berdasarkan ID
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ambil data event
    $row = $result->fetch_assoc();

    // Hitung jumlah peserta yang sudah mendaftar (dengan memperhitungkan jumlah tiket)
    $sql_participants = "SELECT SUM(num_tickets) AS total_participants FROM user_tickets WHERE event_id = $event_id";
    $result_participants = $conn->query($sql_participants);
    $total_participants = $result_participants->fetch_assoc()['total_participants'];

    // Tentukan status event berdasarkan jumlah peserta dan kapasitas maksimal
    if ($total_participants >= $row['max_participants']) {
        $status = 'CLOSED';
    } else {
        $status = 'OPEN';
    }

    // Jika event sudah selesai, bisa di-set jadi 'CANCELED' atau status lainnya, misal:
    $current_date = date('Y-m-d');
    if ($row['date'] < $current_date) {
        $status = 'CLOSED'; // Contoh status jika event sudah lewat
    }
} else {
    echo "Event not found!";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon tanda panah -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            text-decoration: none; /* Hapus garis bawah */
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

        .event-details-card {
            background-color: rgba(44, 44, 44, 0.9); /* Warna yang sama dengan dashboard */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 500px;
            text-align: left;
            color: #fff;
            z-index: 1;
            position: relative;
        }

        .event-details-card img {
            width: 100%;
            height: auto;
        }

        .event-details-card .event-info {
            padding: 20px;
        }

        .event-details-card h3 {
            font-size: 1.8em;
            color: #fff; /* Ubah warna judul menjadi putih */
            margin-bottom: 10px;
        }

        .event-details-card p {
            color: #bbb;
            font-size: 1em;
        }

    </style>
</head>
<body>

    <!-- Tombol Kembali -->
    <a href="dashboard_user.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container-wrap">
        <div class="event-details-card">
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
            <div class="event-info">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
                <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <p><strong>Status:</strong> <?php echo $status; ?></p>
                <p><strong>Max Participants:</strong> <?php echo $row['max_participants']; ?></p>
                <p><strong>Current Participants:</strong> <?php echo ($total_participants ? $total_participants : 0); ?></p>
                <p><strong>Ticket Price:</strong> <?php echo number_format($row['price'], 2) . " IDR"; ?></p>
            </div>
        </div>
    </div>

</body>
</html>
