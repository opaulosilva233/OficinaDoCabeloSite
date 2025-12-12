<?php
// Simulate an API call to api/slots
$_GET['route'] = 'api/slots';
$_GET['barber'] = 'Bruno Martins'; // Example barber
$_GET['date'] = date('Y-m-d'); // Today

// We need to capture output to see if there are any warnings/errors before JSON
ob_start();

// Include index.php which handles routing
// Note: This might be tricky if index.php expects to run in global scope, 
// but let's try to emulate the Request.

// Better approach: Test the Controller directly to avoid routing complexity for now
require_once 'includes/controllers/AppointmentController.php';

try {
    $controller = new AppointmentController();
    $controller->getSlots();
} catch (Throwable $e) {
    echo "\nEXCEPTION: " . $e->getMessage();
    echo "\nTrace: " . $e->getTraceAsString();
}

$output = ob_get_clean();
echo "--- RAW OUTPUT START ---\n";
echo $output;
echo "\n--- RAW OUTPUT END ---\n";

// Check if it's valid JSON
$json = json_decode($output, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "JSON IS VALID.\n";
    print_r($json);
} else {
    echo "JSON IS INVALID. Error: " . json_last_error_msg() . "\n";
}
?>
