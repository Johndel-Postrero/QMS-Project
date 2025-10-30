<?php
session_start();
require_once __DIR__ . '/../../Student/db_config.php';
require_once __DIR__ . '/../admin_functions.php';

// Get database connection
$conn = getDBConnection();

// Get current serving queue
$currentlyServing = getCurrentlyServing($conn);
$currentQueue = !empty($currentlyServing) ? $currentlyServing[0] : null;

// Get queue details if there's a current queue
$currentQueueDetails = null;
$currentServices = [];
if ($currentQueue) {
    $currentQueueDetails = getQueueDetails($conn, $currentQueue['id']);
    if ($currentQueueDetails && $currentQueueDetails['services']) {
        $currentServices = explode(', ', $currentQueueDetails['services']);
    }
}

// Get waiting queues (active)
$waitingQueues = getWaitingQueuesList($conn, 50);

// Get statistics
$stats = getQueueStatistics($conn);

// Get stalled and skipped queues
$stalledQuery = "SELECT * FROM queues WHERE status = 'stalled' AND DATE(created_at) = CURDATE() ORDER BY created_at ASC";
$stalledResult = $conn->query($stalledQuery);
$stalledQueues = $stalledResult->fetch_all(MYSQLI_ASSOC);

$skippedQuery = "SELECT * FROM queues WHERE status = 'skipped' AND DATE(created_at) = CURDATE() ORDER BY created_at ASC";
$skippedResult = $conn->query($skippedQuery);
$skippedQueues = $skippedResult->fetch_all(MYSQLI_ASSOC);

// Get completed count
$completedCount = $stats['completed'];

// Calculate average service time
$avgTimeQuery = "
    SELECT AVG(TIMESTAMPDIFF(MINUTE, served_at, completed_at)) as avg_time
    FROM queues
    WHERE status = 'completed' 
    AND DATE(created_at) = CURDATE()
    AND served_at IS NOT NULL 
    AND completed_at IS NOT NULL
";
$avgTimeResult = $conn->query($avgTimeQuery);
$avgTimeRow = $avgTimeResult->fetch_assoc();
$avgServiceTime = round($avgTimeRow['avg_time'] ?? 0);

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Management - SeQueueR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include 'Header.php'; ?>
    
    <main class="bg-gray-100 min-h-screen">
        <div class="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Left Panel -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Currently Serving Card -->
                    <div class="bg-white border-2 border-yellow-600 rounded-lg p-8 text-center shadow-sm">
                        <div class="text-6xl font-bold text-yellow-600 mb-3">
                            <?php echo $currentQueue ? htmlspecialchars($currentQueue['queue_number']) : '--'; ?>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-3 h-3 <?php echo $currentQueue ? 'bg-green-500' : 'bg-gray-300'; ?> rounded-full"></div>
                            <span class="<?php echo $currentQueue ? 'text-green-600' : 'text-gray-500'; ?> font-medium">
                                <?php echo $currentQueue ? 'Currently Serving' : 'No Queue Serving'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Student Information & Queue Details -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Student Information -->
                            <div>
                                <h3 class="text-lg font-bold text-blue-800 mb-6 pb-2 border-b border-gray-200">Student Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Full Name</span>
                                        <p class="font-bold text-gray-800 text-base">
                                            <?php echo $currentQueueDetails ? htmlspecialchars($currentQueueDetails['student_name']) : '--'; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Student ID</span>
                                        <p class="font-bold text-gray-800 text-base">
                                            <?php echo $currentQueueDetails ? htmlspecialchars($currentQueueDetails['student_id']) : '--'; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Course</span>
                                        <p class="font-bold text-gray-800 text-base">
                                            <?php echo $currentQueueDetails ? htmlspecialchars($currentQueueDetails['course_program']) : '--'; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Year Level</span>
                                        <p class="font-bold text-gray-800 text-base">
                                            <?php echo $currentQueueDetails ? htmlspecialchars($currentQueueDetails['year_level']) : '--'; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Queue Details -->
                            <div>
                                <h3 class="text-lg font-bold text-blue-800 mb-6 pb-2 border-b border-gray-200">Queue Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-2">Priority Type</span>
                                        <div>
                                            <?php if ($currentQueueDetails && $currentQueueDetails['queue_type'] === 'priority'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-200 text-gray-800">
                                                <i class="fas fa-star mr-2 text-black"></i>
                                                Priority
                                            </span>
                                            <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-200 text-gray-800">
                                                Regular
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Time Requested</span>
                                        <p class="font-bold text-gray-800 text-base">
                                            <?php 
                                            if ($currentQueueDetails) {
                                                $time = new DateTime($currentQueueDetails['created_at']);
                                                echo $time->format('g:i A');
                                            } else {
                                                echo '--';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Total Wait Time</span>
                                        <p class="font-bold text-gray-800 text-base">
                                            <?php 
                                            if ($currentQueueDetails) {
                                                $created = new DateTime($currentQueueDetails['created_at']);
                                                $now = new DateTime();
                                                $diff = $now->diff($created);
                                                echo $diff->h . 'h ' . $diff->i . 'm';
                                            } else {
                                                echo '--';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requested Services -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-blue-800 mb-6">Requested Services</h3>
                        
                        <?php if (empty($currentServices)): ?>
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                            <p class="text-lg font-medium">No services requested</p>
                            <p class="text-sm">Services will appear here when a student requests them</p>
                        </div>
                        <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($currentServices as $index => $service): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                        <span class="font-medium text-gray-800"><?php echo htmlspecialchars($service); ?></span>
                                    </div>
                                    <span class="text-sm text-gray-500">#<?php echo $index + 1; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="completeQueue()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md">
                            <i class="fas fa-arrow-right"></i>
                            <span>COMPLETE & NEXT</span>
                        </button>
                        <button onclick="stallQueue()" class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md">
                            <i class="fas fa-pause"></i>
                            <span>MARK AS STALLED</span>
                        </button>
                        <button onclick="skipQueue()" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md">
                            <i class="fas fa-forward"></i>
                            <span>SKIP QUEUE</span>
                        </button>
                        <button onclick="callNextQueue()" class="bg-white border-2 border-yellow-400 text-black font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md hover:bg-yellow-50 transition">
                            <i class="fas fa-phone"></i>
                            <span>Call Next Queue</span>
                        </button>
                    </div>
                </div>

                <!-- Right Panel - Queue Lists -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Queue List -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-blue-900">Queue List</h3>
                            <div class="bg-blue-900 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-semibold">
                                <?php echo count($waitingQueues) + count($stalledQueues) + count($skippedQueues); ?>
                            </div>
                        </div>
                        
                        <!-- Active Queue -->
                        <div class="border-b border-gray-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-blue-50 focus:outline-none" onclick="toggleQueueSection('activeQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-users text-blue-600 w-4 h-4"></i>
                                    <h4 class="font-semibold text-blue-900 text-sm">Active Queue</h4>
                                    <div class="bg-blue-900 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">
                                        <?php echo count($waitingQueues); ?>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-blue-900 w-4 h-4 transition-transform" id="activeQueue-arrow"></i>
                            </button>
                            <div id="activeQueue-content" class="divide-y divide-gray-200">
                                <?php if (empty($waitingQueues)): ?>
                                <div class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-3xl mb-2"></i>
                                    <p>No active queue items</p>
                                </div>
                                <?php else: ?>
                                <?php foreach ($waitingQueues as $queue): ?>
                                <div class="px-5 py-3 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <?php if ($queue['queue_type'] === 'priority'): ?>
                                                <i class="fas fa-star text-yellow-500 text-xs"></i>
                                                <?php endif; ?>
                                                <span class="font-bold text-gray-900"><?php echo htmlspecialchars($queue['queue_number']); ?></span>
                                            </div>
                                            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($queue['student_name']); ?></p>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            <?php 
                                            $time = new DateTime($queue['created_at']);
                                            echo $time->format('g:i A');
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Stalled Queue -->
                        <div class="border-b border-gray-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-yellow-50 focus:outline-none" onclick="toggleQueueSection('stalledQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 w-4 h-4"></i>
                                    <h4 class="font-semibold text-yellow-600 text-sm">Stalled Queue</h4>
                                    <div class="bg-yellow-400 text-yellow-900 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">
                                        <?php echo count($stalledQueues); ?>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-yellow-600 w-4 h-4 transition-transform" id="stalledQueue-arrow"></i>
                            </button>
                            <div id="stalledQueue-content" class="divide-y divide-gray-200 hidden">
                                <?php if (empty($stalledQueues)): ?>
                                <div class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
                                    <p>No stalled queue items</p>
                                </div>
                                <?php else: ?>
                                <?php foreach ($stalledQueues as $queue): ?>
                                <div class="px-5 py-3 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-bold text-gray-900"><?php echo htmlspecialchars($queue['queue_number']); ?></span>
                                            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($queue['student_name']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Skipped Queue -->
                        <div class="border-b border-red-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-red-50 focus:outline-none" onclick="toggleQueueSection('skippedQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-times-circle text-red-600 w-4 h-4"></i>
                                    <h4 class="font-semibold text-red-600 text-sm">Skipped Queue</h4>
                                    <div class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">
                                        <?php echo count($skippedQueues); ?>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-red-600 w-4 h-4 transition-transform" id="skippedQueue-arrow"></i>
                            </button>
                            <div id="skippedQueue-content" class="divide-y divide-gray-200 hidden">
                                <?php if (empty($skippedQueues)): ?>
                                <div class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-times-circle text-3xl mb-2"></i>
                                    <p>No skipped queue items</p>
                                </div>
                                <?php else: ?>
                                <?php foreach ($skippedQueues as $queue): ?>
                                <div class="px-5 py-3 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-bold text-gray-900"><?php echo htmlspecialchars($queue['queue_number']); ?></span>
                                            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($queue['student_name']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Transaction Status -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Transaction Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-clock text-blue-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $avgServiceTime; ?> min</p>
                                <p class="text-sm text-gray-600">Avg Service Time</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $completedCount; ?></p>
                                <p class="text-sm text-gray-600">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-pause-circle text-yellow-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900"><?php echo count($stalledQueues); ?></p>
                                <p class="text-sm text-gray-600">Stalled</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900"><?php echo count($skippedQueues); ?></p>
                                <p class="text-sm text-gray-600">Skipped</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../../Footer.php'; ?>
    
    <script>
        const currentQueueId = <?php echo $currentQueue ? $currentQueue['id'] : 'null'; ?>;
        
        // Toggle queue section
        function toggleQueueSection(sectionId) {
            const content = document.getElementById(sectionId + '-content');
            const arrow = document.getElementById(sectionId + '-arrow');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Complete queue and move to next
        function completeQueue() {
            if (!currentQueueId) {
                alert('No queue is currently being served');
                return;
            }
            
            if (confirm('Mark this queue as completed and call the next one?')) {
                window.location.href = `queue_actions.php?action=complete&id=${currentQueueId}`;
            }
        }
        
        // Stall queue
        function stallQueue() {
            if (!currentQueueId) {
                alert('No queue is currently being served');
                return;
            }
            
            if (confirm('Mark this queue as stalled?')) {
                window.location.href = `queue_actions.php?action=stall&id=${currentQueueId}`;
            }
        }
        
        // Skip queue
        function skipQueue() {
            if (!currentQueueId) {
                alert('No queue is currently being served');
                return;
            }
            
            if (confirm('Skip this queue?')) {
                window.location.href = `queue_actions.php?action=skip&id=${currentQueueId}`;
            }
        }
        
        // Call next queue
        function callNextQueue() {
            window.location.href = 'queue_actions.php?action=next';
        }
        
        // Auto-refresh every 15 seconds
        setInterval(() => location.reload(), 15000);
    </script>
</body>
</html>