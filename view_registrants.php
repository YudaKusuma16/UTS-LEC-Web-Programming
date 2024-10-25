<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'database.php';

// Cek apakah ID event diberikan
if (!isset($_GET['event_id'])) {
    echo "Event ID not provided!";
    exit();
}

$event_id = $_GET['event_id'];

// Ambil registrants untuk event dari database
$sql = "SELECT u.name, u.email, ut.num_tickets, ut.total_payment, ut.payment_method, ut.phone_number 
        FROM user_tickets ut 
        JOIN users u ON ut.user_id = u.id 
        WHERE ut.event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrants for Event ID <?php echo $event_id; ?></title>
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

        .registrants-container {
            background-color: rgba(44, 44, 44, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            width: 90%;
            z-index: 1;
            position: relative;
        }

        .registrants-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .table {
            width: 100%;
            background-color: #333;
            color: #fff;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #444;
        }

        .btn {
            margin: 10px 5px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #007BFF;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
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
    <div class="registrants-container">
        <h2>Registrants for Event ID <?php echo $event_id; ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Jumlah Tiket</th>
                    <th>Total Pembayaran (IDR)</th>
                    <th>Payment Method</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['num_tickets']); ?></td>
                            <td><?php echo number_format($row['total_payment'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No registrants found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="export_registrants.php?event_id=<?php echo $event_id; ?>" class="btn btn-success">Export to Excel</a>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
