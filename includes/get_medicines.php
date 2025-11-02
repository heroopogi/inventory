<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

try {
    // Get all medicines
    $sql = "SELECT * FROM medicines ORDER BY name";
    $result = $conn->query($sql);

    $medicines = array();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Calculate status
            $status = 'In Stock';
            $quantity = (int)$row['quantity'];
            $expiryDate = strtotime($row['expiry_date']);
            $thirtyDaysFromNow = strtotime('+30 days');

            if ($quantity <= 10 && $quantity > 0) {
                $status = 'Low Stock';
            }
            if ($expiryDate < $thirtyDaysFromNow) {
                $status = 'Expiring Soon';
            }
            if ($quantity == 0) {
                $status = 'Out of Stock';
            }

            // Format the date
            $row['expiry_date'] = date('M d, Y', strtotime($row['expiry_date']));
            
            // Format the price
            $row['price'] = number_format($row['price'], 2);
            
            $row['status'] = $status;
            $medicines[] = $row;
        }

        $response = array(
            'status' => 'success',
            'data' => $medicines
        );
    } else {
        throw new Exception($conn->error);
    }

} catch (Exception $e) {
    $response = array(
        'status' => 'error',
        'message' => $e->getMessage(),
        'data' => []
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