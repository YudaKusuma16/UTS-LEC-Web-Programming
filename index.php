<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify | Vanilla Javascript</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-alert {
            background-color: #2b1055;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            color: #fff;
            box-shadow: none;
        }

        .modal-content {
            background-color: #2b1055;
            border-radius: 10px;
            border: none;
            box-shadow: none;
        }

        .modal-dialog {
            box-shadow: none;
        }
    </style>
</head>
<body>
    <header>
        <a href="#" class="logo">EVENTIFY</a>
        <ul>
            <li><a href="#" class="active">Home</a></li>
            <li><a href="events.php">Events</a></li>
            <form class="d-flex" action="search.php" method="GET" style="margin-left: 20px;">
                <input class="form-control me-2" type="search" name="query" placeholder="Search Events" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
            <li><a href="login.php" class="login-btn">L O G I N</a></li>
        </ul>
    </header>

    <section>
        <img src="images/stars.png" id="stars">
        <img src="images/moon.png" id="moon">
        <img src="images/mountains_behind.png" id="mountains_behind">
        <h2 id="text">E V E N T I F Y</h2>
        <a href="#" id="btn" class="register-btn">Register Events</a>
        <img src="images/mountains_front.png" id="mountains_front">
    </section>

    <!-- Section On Going Events -->
    <div class="sec" id="sec">
        <h2>On Going Events</h2>

        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="event-card">
                                <img src="images/event4.jpeg" class="d-block w-100" alt="Event 1">
                                <div class="event-info">
                                    <h3>Marketing Mastery</h3>
                                    <p>Sat, Oct 19 | 0 IDR</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="event-card">
                                <img src="images/event1.jpeg" class="d-block w-100" alt="Event 2">
                                <div class="event-info">
                                    <h3>Waste to Energy Summit</h3>
                                    <p>Wed, Oct 30 | 0 IDR</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="event-card">
                                <img src="images/event2.jpeg" class="d-block w-100" alt="Event 3">
                                <div class="event-info">
                                    <h3>Leadership School</h3>
                                    <p>Fri, Oct 25 | 0 IDR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="event-card">
                                <img src="images/event3.jpeg" class="d-block w-100" alt="Event 4">
                                <div class="event-info">
                                    <h3>AI Conference</h3>
                                    <p>Mon, Nov 15 | 0 IDR</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="event-card">
                                <img src="images/event5.jpeg" class="d-block w-100" alt="Event 5">
                                <div class="event-info">
                                    <h3>Business Growth Summit</h3>
                                    <p>Wed, Nov 20 | 0 IDR</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="event-card">
                                <img src="images/event6.jpeg" class="d-block w-100" alt="Event 6">
                                <div class="event-info">
                                    <h3>Marketing 101</h3>
                                    <p>Fri, Dec 1 | 0 IDR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol View All untuk menampilkan semua event -->
            <div class="view-all-container">
                <a href="events.php" class="view-all-btn">View All</a>
            </div>

        </div>
    </div>

    <!-- Script to Handle Register Button Behavior -->
    <script>
        // Script to Handle Register Button Behavior
        document.querySelector('.register-btn').addEventListener('click', function(event) {
            // Langsung arahkan ke sign-up.php
            window.location.href = "sign-up.php";
        });

        // Parallax Effect
        let stars = document.getElementById('stars');
        let moon = document.getElementById('moon');
        let mountains_behind = document.getElementById('mountains_behind');
        let text = document.getElementById('text');
        let btn = document.getElementById('btn');
        let mountains_front = document.getElementById('mountains_front');

        window.addEventListener('scroll', function() {
            let value = window.scrollY;
            stars.style.left = value * 0.25 + 'px';
            moon.style.top = value * 1.05 + 'px';
            mountains_behind.style.top = value * 0.5 + 'px';
            mountains_front.style.top = value * 0 + 'px';
            text.style.marginTop = value * 1.5 + 'px';
            btn.style.marginTop = value * 1.5 + 'px';
            text.style.opacity = 1 - value / 600;
            btn.style.opacity = 1 - value / 600;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
