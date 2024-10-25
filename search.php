<?php
// Koneksi ke database
include 'database.php';

// Ambil query dari form pencarian
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Query SQL untuk mencari event berdasarkan kata kunci
$sql = "SELECT * FROM events WHERE title LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_query . "%";
$stmt->bind_param('ss', $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon tanda panah -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: url('images/background_login.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
            overflow-x: hidden;
            position: relative;
        }

        /* Efek blur pada background */
        .blur-background {
            backdrop-filter: blur(8px); /* Efek blur */
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
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
            z-index: 3;
            border: none; /* Menghilangkan garis biru */
            outline: none; /* Menghilangkan outline saat elemen di-klik */
            text-decoration: none;
        }

        .back-arrow:focus,
        .back-arrow:active {
            outline: none; /* Menghilangkan outline saat elemen mendapat fokus */
        }

        .back-arrow i {
            color: white;
            font-size: 24px;
        }

        .container-wrap {
            width: 100%;
            padding-top: 100px;
            text-align: center;
            position: relative;
            z-index: 2; /* Agar konten berada di atas efek blur */
        }

        h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 40px;
            background-color: rgba(0, 0, 0, 0.5);
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
        }

        .event-card {
            background-color: rgba(44, 44, 44, 0.9);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .event-card img {
            width: 100%;
            height: auto;
        }

        .event-info {
            padding: 20px;
        }

        .event-info h3 {
            color: #fff;
        }

        .event-info p {
            color: #bbb;
        }

        /* Pusatkan elemen */
        .row {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

    <!-- Back Arrow -->
    <a href="index.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Efek blur -->
    <div class="blur-background"></div>

    <div class="container-wrap">
        <h1>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h1>

        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-6 col-lg-5">';
                    echo '<div class="event-card" onclick="showRegisterAlert(\'' . addslashes($row["title"]) . '\')">'; // Ganti dengan fungsi SweetAlert
                    echo '<img src="' . $row["image"] . '" class="d-block w-100" alt="' . $row["title"] . '">';
                    echo '<div class="event-info">';
                    echo '<h3>' . $row["title"] . '</h3>';
                    echo '<p>' . date('D, M d', strtotime($row["date"])) . ' | $' . number_format($row["price"], 2) . '</p>';
                    echo '<p>' . $row["description"] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No events found.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showRegisterAlert(eventTitle) {
            Swal.fire({
                icon: 'warning',
                title: 'You need to register first!',
                text: 'To register, you need to sign up first for the event: ' + eventTitle,
                confirmButtonText: 'Register Now',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
                willClose: () => {
                    window.location.href = 'sign-up.php';
                }
            });
        }
    </script>

</body>
</html>
