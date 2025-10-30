<?php
session_start();
require_once 'Frontend/Student/db_config.php';
require_once 'Frontend/Personnel/admin_functions.php';

// Get action and queue ID
$action = $_GET['action'] ?? '';
$queueId = $_GET['id'] ?? null;

// Get database connection
$conn = getDBConnection();

try {
    switch ($action) {
        case 'complete':
            // Mark queue as completed
            if ($queueId) {
                updateQueueStatus($conn, $queueId, 'completed');
                
                // Get next queue and serve it
                $nextQueue = getNextQueueToServe($conn);
                if ($nextQueue) {
                    updateQueueStatus($conn, $nextQueue['id'], 'serving', 1);
                }
            }
            break;
            
        case 'stall':
            // Mark queue as stalled
            if ($queueId) {
                updateQueueStatus($conn, $queueId, 'stalled');
                
                // Get next queue and serve it
                $nextQueue = getNextQueueToServe($conn);
                if ($nextQueue) {
                    updateQueueStatus($conn, $nextQueue['id'], 'serving', 1);
                }
            }
            break;
            
        case 'skip':
            // Mark queue as skipped
            if ($queueId) {
                updateQueueStatus($conn, $queueId, 'skipped');
                
                // Get next queue and serve it
                $nextQueue = getNextQueueToServe($conn);
                if ($nextQueue) {
                    updateQueueStatus($conn, $nextQueue['id'], 'serving', 1);
                }
            }
            break;
            
        case 'next':
            // Call next queue without completing current
            $nextQueue = getNextQueueToServe($conn);
            if ($nextQueue) {
                updateQueueStatus($conn, $nextQueue['id'], 'serving', 1);
            }
            break;
            
        case 'resume':
            // Resume a stalled queue
            if ($queueId) {
                updateQueueStatus($conn, $queueId, 'serving', 1);
            }
            break;
            
        default:
            header('Location: Queue.php');
            exit;
    }
    
    $conn->close();
    
    // Redirect back to Queue.php
    header('Location: Queue.php');
    exit;
    
} catch (Exception $e) {
    error_log("Queue action error: " . $e->getMessage());
    $conn->close();
    header('Location: Queue.php?error=1');
    exit;
}
?>