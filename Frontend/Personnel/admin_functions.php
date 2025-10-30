<?php
// Admin Queue Functions
// Add these functions to your db_config.php or create a new admin_functions.php file

// Get total queues for today
function getTodayTotalQueues($conn) {
    try {
        $today = date('Y-m-d');
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE DATE(created_at) = ?
        ");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        error_log("Error getting total queues: " . $e->getMessage());
        return 0;
    }
}

// Get waiting queues (status = 'waiting')
function getWaitingQueues($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE status = 'waiting'
            AND DATE(created_at) = CURDATE()
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        error_log("Error getting waiting queues: " . $e->getMessage());
        return 0;
    }
}

// Get currently serving queue (status = 'serving')
function getCurrentlyServing($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT queue_number, student_name, queue_type, window_number 
            FROM queues 
            WHERE status = 'serving'
            AND DATE(created_at) = CURDATE()
            ORDER BY updated_at DESC
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting currently serving: " . $e->getMessage());
        return [];
    }
}

// Get pending queues count (status = 'waiting')
function getPendingQueuesCount($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE status = 'waiting'
            AND DATE(created_at) = CURDATE()
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        error_log("Error getting pending queues: " . $e->getMessage());
        return 0;
    }
}

// Get completed queues count (status = 'completed')
function getCompletedQueuesCount($conn) {
    try {
        $today = date('Y-m-d');
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE status = 'completed'
            AND DATE(created_at) = ?
        ");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        error_log("Error getting completed queues: " . $e->getMessage());
        return 0;
    }
}

// Get priority queues count
function getPriorityQueuesCount($conn) {
    try {
        $today = date('Y-m-d');
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE queue_type = 'priority'
            AND DATE(created_at) = ?
        ");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        error_log("Error getting priority queues: " . $e->getMessage());
        return 0;
    }
}

// Get regular queues count
function getRegularQueuesCount($conn) {
    try {
        $today = date('Y-m-d');
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE queue_type = 'regular'
            AND DATE(created_at) = ?
        ");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        error_log("Error getting regular queues: " . $e->getMessage());
        return 0;
    }
}

// Get all waiting queues with details
function getWaitingQueuesList($conn, $limit = 10) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                q.id,
                q.queue_number,
                q.queue_type,
                q.student_name,
                q.student_id,
                q.year_level,
                q.course_program,
                q.services_count,
                q.created_at,
                q.status
            FROM queues q
            WHERE q.status = 'waiting'
            AND DATE(q.created_at) = CURDATE()
            ORDER BY 
                CASE WHEN q.queue_type = 'priority' THEN 0 ELSE 1 END,
                q.created_at ASC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting waiting queues list: " . $e->getMessage());
        return [];
    }
}

// Get queue details by ID
function getQueueDetails($conn, $queueId) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                q.*,
                GROUP_CONCAT(qs.service_name SEPARATOR ', ') as services
            FROM queues q
            LEFT JOIN queue_services qs ON q.id = qs.queue_id
            WHERE q.id = ?
            GROUP BY q.id
        ");
        $stmt->bind_param("i", $queueId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } catch (Exception $e) {
        error_log("Error getting queue details: " . $e->getMessage());
        return null;
    }
}

// Get average waiting time (in minutes)
function getAverageWaitingTime($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                AVG(TIMESTAMPDIFF(MINUTE, created_at, served_at)) as avg_wait
            FROM queues
            WHERE status = 'completed'
            AND DATE(created_at) = CURDATE()
            AND served_at IS NOT NULL
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return round($row['avg_wait'] ?? 0, 1);
    } catch (Exception $e) {
        error_log("Error getting average waiting time: " . $e->getMessage());
        return 0;
    }
}

// Get queue statistics for dashboard
function getQueueStatistics($conn) {
    return [
        'total_today' => getTodayTotalQueues($conn),
        'waiting' => getWaitingQueues($conn),
        'serving' => count(getCurrentlyServing($conn)),
        'pending' => getPendingQueuesCount($conn),
        'completed' => getCompletedQueuesCount($conn),
        'priority' => getPriorityQueuesCount($conn),
        'regular' => getRegularQueuesCount($conn),
        'avg_wait_time' => getAverageWaitingTime($conn)
    ];
}

// Update queue status
function updateQueueStatus($conn, $queueId, $newStatus, $windowNumber = null) {
    try {
        $conn->begin_transaction();
        
        // Update queue status
        if ($windowNumber) {
            $stmt = $conn->prepare("
                UPDATE queues 
                SET status = ?, 
                    window_number = ?,
                    served_at = CASE WHEN ? = 'serving' THEN NOW() ELSE served_at END,
                    completed_at = CASE WHEN ? = 'completed' THEN NOW() ELSE completed_at END,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("sissi", $newStatus, $windowNumber, $newStatus, $newStatus, $queueId);
        } else {
            $stmt = $conn->prepare("
                UPDATE queues 
                SET status = ?,
                    served_at = CASE WHEN ? = 'serving' THEN NOW() ELSE served_at END,
                    completed_at = CASE WHEN ? = 'completed' THEN NOW() ELSE completed_at END,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("sssi", $newStatus, $newStatus, $newStatus, $queueId);
        }
        
        $stmt->execute();
        
        // Log the action
        logQueueAction($conn, $queueId, 'status_changed', "Status changed to: $newStatus");
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error updating queue status: " . $e->getMessage());
        return false;
    }
}

// Get next queue to serve (priority first, then regular by time)
function getNextQueueToServe($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                id,
                queue_number,
                queue_type,
                student_name,
                services_count
            FROM queues
            WHERE status = 'waiting'
            AND DATE(created_at) = CURDATE()
            ORDER BY 
                CASE WHEN queue_type = 'priority' THEN 0 ELSE 1 END,
                created_at ASC
            LIMIT 1
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } catch (Exception $e) {
        error_log("Error getting next queue: " . $e->getMessage());
        return null;
    }
}
?>