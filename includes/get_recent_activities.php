<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

try {
    // Get recent activities (last 10 additions)
    $sql = "SELECT 
                m.name,
                m.quantity,
                m.created_at,
                DATE_FORMAT(m.created_at, '%b %d, %Y') as formatted_date,
                COALESCE(u.username, 'System') as username
            FROM medicines m
            LEFT JOIN users u ON m.added_by = u.id
            ORDER BY m.created_at DESC 
            LIMIT 10";
    
    $result = $conn->query($sql);
    
    $activities = array();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Calculate how recent the activity is
            $created = strtotime($row['created_at']);
            $now = time();
            $diff = $now - $created;
            
            // Determine status based on how recent it is
            $status = 'Completed';
            if ($diff < 300) { // If less than 5 minutes old
                $status = 'Recent';
            }
            
            $activity = array(
                'date' => $row['formatted_date'],
                'action' => 'Added',
                'medicine_name' => $row['name'] . ' (' . $row['quantity'] . ' units)',
                'user' => $row['username'],
                'status' => $status
            );
            $activities[] = $activity;
        }
        
        $response = array(
            'status' => 'success',
            'data' => $activities
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