<?php
include('./db.php');
include('./bookingUtils.php');

if (isset($_GET['barber']) && isset($_GET['date'])) {
    $barber = filter_var($_GET['barber'], FILTER_SANITIZE_STRING);
    $date = filter_var($_GET['date'], FILTER_SANITIZE_STRING);

    error_log("Parâmetros recebidos: barber=$barber, date=$date");

    $bookingUtils = new BookingUtils($pdo);
    $availableTimes = $bookingUtils->getAvailableTimes($barber, $date);

    if (is_array($availableTimes)) {
        echo json_encode(['success' => true, 'slots' => $availableTimes]);
    } else {
        echo json_encode($availableTimes);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos.']);
}