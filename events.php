<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events | Eventify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Penggabungan CSS dari style.css */
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
            font-weight: 900 !important; /* Memaksa lebih tebal */
            text-decoration: none;
            font-size: 2em;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        header ul {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        header ul li {
            list-style: none;
            margin-left: 20px;
        }
        header ul li a {
            text-decoration: none;
            padding: 6px 15px;
            color: #fff;
            border-radius: 20px;
            font-weight: 600 !important; /* Memaksa lebih tebal */
        }
        header ul li a:hover,
        header ul li a.active {
            background: #fff;
            color: #2b1055;
        }

        .sec {
            position: relative;
            padding: 50px 50px;
            background: linear-gradient(to bottom, #1c0522, #2b1055);
        }

        .sec h2 {
            font-size: 3.5em;
            margin-bottom: 40px;
            margin-top: 20px;
            color: #fff;
            text-align: center;
        }

        .event-card {
            background-color: rgba(44, 44, 44, 0.9);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            margin-bottom: 15.5px; /* Tambahkan margin bawah untuk spasi antar card */
            cursor: pointer; /* Ubah cursor menjadi pointer */
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

    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo">EVENTIFY</a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="events.php" class="active">Events</a></li>
            <form class="d-flex" action="search.php" method="GET" style="margin-left: 20px;">
                <input class="form-control me-2" type="search" name="query" placeholder="Search Events" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
            <li><a href="login.php" class="login-btn">L O G I N</a></li>
        </ul>
    </header>

    <div class="sec" id="sec">
        <h2>All On Going Events</h2>

        <!-- Menampilkan semua event dalam format 3 kolom per baris -->
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
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4">';
                        echo '<div class="event-card" onclick="showRegisterAlert()">'; // Panggil fungsi JavaScript ketika card diklik
                        echo '<img src="' . $row["image"] . '" class="d-block w-100" alt="' . $row["title"] . '">';
                        echo '<div class="event-info">';
                        echo '<h3>' . $row["title"] . '</h3>';
                        echo '<p>' . date('D, M d', strtotime($row["date"])) . ' | ' . number_format($row["price"], 2) . ' IDR</p>';
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

    <!-- Tambahkan script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        function showRegisterAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'You need to register first!',
                text: 'To register, you need to sign up first.',
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
