<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
    exit;
}

// Accept both AJAX and form requests
if (isset($_POST['medicineId'])) {
    $id = (int)$_POST['medicineId'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'No medicine ID provided']);
    exit;
}





$sql = "DELETE FROM medicines WHERE id = $id";
$response = array();

if ($conn->query($sql)) {
    $response['status'] = 'success';
    $response['message'] = 'Medicine deleted successfully';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $conn->error;
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>