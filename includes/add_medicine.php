<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = array(
        'status' => 'error',
        'message' => 'Only POST requests are allowed'
    );
    echo json_encode($response);
    exit;
}

// Validate that all required fields are present
if (!isset($_POST['medicineName']) || !isset($_POST['genericName']) || !isset($_POST['category']) || 
    !isset($_POST['quantity']) || !isset($_POST['price']) || !isset($_POST['expiryDate'])) {
    $response = array(
        'status' => 'error',
        'message' => 'Missing required fields'
    );
    echo json_encode($response);
    exit;
}

try {
    // Get form data
    $medicineName = $conn->real_escape_string($_POST['medicineName']);
    $genericName = $conn->real_escape_string($_POST['genericName']);
    $category = $conn->real_escape_string($_POST['category']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $expiryDate = $conn->real_escape_string($_POST['expiryDate']);
    $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';

    // Insert data into database
    $sql = "INSERT INTO medicines (name, generic_name, category, quantity, price, expiry_date, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssiiss", $medicineName, $genericName, $category, $quantity, $price, $expiryDate, $description);
    
    if ($stmt->execute()) {
        $response = array(
            'status' => 'success',
            'message' => 'Medicine added successfully',
            'id' => $conn->insert_id
        );
    } else {
        throw new Exception("Error executing statement: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response = array(
        'status' => 'error',
        'message' => $e->getMessage()
    );
} finally {
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    
    if (isset($conn)) {
        $conn->close();
    }
}
?>