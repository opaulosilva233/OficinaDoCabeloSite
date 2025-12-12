<?php
// Mock $_GET and $_POST
$_GET['barber'] = 'Bruno Martins';
$_GET['date'] = date('Y-m-d');
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once 'includes/config.php';
require_once 'includes/Router.php';
require_once 'includes/controllers/AppointmentController.php';

echo "Testing AppointmentController::getSlots...\n";
$controller = new AppointmentController();
ob_start();
$controller->getSlots();
$output = ob_get_clean();

echo "Output: " . $output . "\n";
$json = json_decode($output, true);

if ($json['success']) {
    echo "SUCCESS: Slots retrieved.\n";
    print_r($json['slots']);
} else {
    echo "FAILED: " . ($json['message'] ?? 'Unknown error') . "\n";
}
?>
