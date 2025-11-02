<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

try {
    // First, let's see what categories we actually have
    $debug_sql = "SELECT DISTINCT category FROM medicines";
    $debug_result = $conn->query($debug_sql);
    $categories = [];
    if ($debug_result) {
        while ($row = $debug_result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
    }
    
    // Get counts for each category
    $sql = "SELECT 
        SUM(CASE WHEN LOWER(category) = LOWER('Antibiotics') THEN 1 ELSE 0 END) as antibiotics,
        SUM(CASE WHEN LOWER(category) = LOWER('Pain Relief') THEN 1 ELSE 0 END) as painRelief,
        SUM(CASE WHEN LOWER(category) = LOWER('Vitamins') THEN 1 ELSE 0 END) as vitamins,
        SUM(CASE WHEN LOWER(category) NOT IN (LOWER('Antibiotics'), LOWER('Pain Relief'), LOWER('Vitamins')) THEN 1 ELSE 0 END) as others
    FROM medicines";
    
    $result = $conn->query($sql);
    
    if ($result) {
        $counts = $result->fetch_assoc();
        
        $response = array(
            'status' => 'success',
            'counts' => array(
                'antibiotics' => (int)$counts['antibiotics'],
                'painRelief' => (int)$counts['painRelief'],
                'vitamins' => (int)$counts['vitamins'],
                'others' => (int)$counts['others']
            )
        );
    } else {
        throw new Exception($conn->error);
    }
    
} catch (Exception $e) {
    $response = array(
        'status' => 'error',
        'message' => $e->getMessage()
    );
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);