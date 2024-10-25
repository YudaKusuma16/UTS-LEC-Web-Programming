<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'database.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Delete the event from the database
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        // Redirect back to dashboard_admin.php after successful deletion
        header("Location: dashboard_admin.php");
        exit();
    } else {
        echo "Error deleting event!";
    }
} else {
    echo "Event ID not provided!";
}
?>
