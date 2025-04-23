<?php
include('./db.php');
include('./bookingUtils.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lockId = filter_var($_POST['lock_id'], FILTER_SANITIZE_NUMBER_INT);

    $bookingUtils = new BookingUtils($pdo);
    $bookingUtils->releaseTimeSlot($lockId);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}