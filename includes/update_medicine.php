<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('This endpoint only accepts POST requests');
}

$id = (int)$_POST['medicineId'];
$medicineName = $conn->real_escape_string($_POST['medicineName']);
$genericName = $conn->real_escape_string($_POST['genericName']);
$category = $conn->real_escape_string($_POST['category']);
$quantity = (int)$_POST['quantity'];
$price = (float)$_POST['price'];
$expiryDate = $conn->real_escape_string($_POST['expiryDate']);
$description = $conn->real_escape_string($_POST['description']);

$sql = "UPDATE medicines SET 
        name = '$medicineName',
        generic_name = '$genericName',
        category = '$category',
        quantity = $quantity,
        price = $price,
        expiry_date = '$expiryDate',
        description = '$description'
        WHERE id = $id";

$response = array();

if ($conn->query($sql)) {
    $response['status'] = 'success';
    $response['message'] = 'Medicine updated successfully';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $conn->error;
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>