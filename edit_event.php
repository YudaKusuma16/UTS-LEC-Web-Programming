<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'database.php';

// Check if event ID is provided
if (!isset($_GET['event_id'])) {
    echo "Event ID not provided!";
    exit();
}

$event_id = $_GET['event_id'];

// Fetch event data from the database
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Event not found!";
    exit();
}

$event = $result->fetch_assoc();

// Buffering the output to avoid premature output issues
ob_start();

// Handle form submission for updating the event
$updateSuccess = false;  // To track if the update is successful

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $max_participants = $_POST['max_participants'];
    $status = $_POST['status'];

    // Check if an image is uploaded
    $image = $event['image']; // Default image
    if (!empty($_FILES['image']['name'])) {
        $image = 'images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Update event in the database
    $sql = "UPDATE events SET title = ?, description = ?, date = ?, time = ?, location = ?, price = ?, image = ?, max_participants = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssiis", $title, $description, $date, $time, $location, $price, $image, $max_participants, $status, $event_id);

    if ($stmt->execute()) {
        $updateSuccess = true;
    } else {
        $updateSuccess = false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    .edit-event-container {
        background-color: rgba(44, 44, 44, 0.9);
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        color: #fff;
        width: 500px;
        z-index: 1;
        position: relative;
    }

    .edit-event-container h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
    }

    .edit-event-container label {
        font-weight: 500;
    }

    .edit-event-container input,
    .edit-event-container select,
    .edit-event-container textarea {
        margin-bottom: 1rem;
        border-radius: 8px;
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }

    .edit-event-container .btn-primary {
        width: 100%;
        background-color: #007BFF;
        border: none;
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
        font-weight: 600;
    }

    .edit-event-container .btn-primary:hover {
        background-color: #0056b3;
    }

    .current-image {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .current-image img {
        width: 100px;
        border-radius: 8px;
        margin-left: 15px;
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
    <div class="edit-event-container">
        <h2>Edit Event</h2>
        <form action="edit_event.php?event_id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Event Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo $event['date']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Event Time</label>
                <input type="time" class="form-control" id="time" name="time" value="<?php echo $event['time']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Ticket Price (IDR)</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $event['price']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="max_participants" class="form-label">Maximum Participants</label>
                <input type="number" class="form-control" id="max_participants" name="max_participants" value="<?php echo $event['max_participants']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Open" <?php echo ($event['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
                    <option value="Closed" <?php echo ($event['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Event Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <div class="current-image">
                    <span>Current image:</span>
                    <img src="<?php echo $event['image']; ?>" alt="Event Image">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
</div>

<!-- SweetAlert2 Notifications -->
<?php if ($updateSuccess): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Event anda sukses ter-update!!!',
        showConfirmButton: false,
        timer: 2000
    }).then(function() {
        window.location.href = 'dashboard_admin.php';
    });
</script>
<?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Ada masalah saat mengupdate event!',
        showConfirmButton: true,
    });
</script>
<?php endif; ?>

</body>
</html>

<?php
// Flush the buffer to send the content
ob_end_flush();
?>
