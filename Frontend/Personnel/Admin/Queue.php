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
$stalledQuery = "SELECT q.*, GROUP_CONCAT(qs.service_name SEPARATOR ', ') as services FROM queues q LEFT JOIN queue_services qs ON q.id = qs.queue_id WHERE q.status = 'stalled' AND DATE(q.created_at) = CURDATE() GROUP BY q.id ORDER BY q.created_at ASC";
$stalledResult = $conn->query($stalledQuery);
$stalledQueues = $stalledResult->fetch_all(MYSQLI_ASSOC);

$skippedQuery = "SELECT q.*, GROUP_CONCAT(qs.service_name SEPARATOR ', ') as services FROM queues q LEFT JOIN queue_services qs ON q.id = qs.queue_id WHERE q.status = 'skipped' AND DATE(q.created_at) = CURDATE() GROUP BY q.id ORDER BY q.created_at ASC";
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
    <title>Queue Management - SeQueueR</title>\
    <link rel="icon" type="image/png" href="/Frontend/favicon.php">
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
                    <?php 
                        $isPriorityColor = ($currentQueue && ($currentQueue['queue_type'] ?? '') === 'priority');
                        $accentColor = $isPriorityColor ? '#DAA520' : '#003366';
                    ?>
                    <div class="bg-white border-2 rounded-lg p-8 text-center shadow-sm" style="border-color: <?php echo $accentColor; ?>;">
                        <div class="text-6xl font-bold mb-3" style="color: <?php echo $accentColor; ?>;">
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
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold" style="background-color:#DAA520;color:#111;">
                                                <i class="fas fa-star mr-2" style="color:#111;"></i>
                                                Priority
                                            </span>
                                            <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold" style="background-color:#003366;color:#fff;">
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
                            <!-- Tabs Header -->
                            <div class="flex flex-wrap gap-2 mb-4" role="tablist">
                                <?php foreach ($currentServices as $index => $service): ?>
                                    <?php $tabId = 'svc-tab-' . $index; $panelId = 'svc-panel-' . $index; ?>
                                    <button
                                        id="<?php echo $tabId; ?>"
                                        class="px-4 py-2 rounded-lg border text-sm font-semibold <?php echo $index === 0 ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-blue-900 border-blue-200 hover:bg-blue-50'; ?>"
                                        onclick="showServiceTab(<?php echo $index; ?>)"
                                        type="button"
                                        role="tab"
                                        aria-controls="<?php echo $panelId; ?>"
                                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                                    >
                                        <?php echo htmlspecialchars(trim($service)); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <!-- Tabs Panels -->
                            <div class="mt-2">
                                <?php foreach ($currentServices as $index => $service): ?>
                                    <?php $panelId = 'svc-panel-' . $index; ?>
                                    <div id="<?php echo $panelId; ?>" class="<?php echo $index === 0 ? '' : 'hidden'; ?>">
                                        <!-- Service Header -->
                                        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-4">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" checked class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                                <span class="font-semibold text-gray-900"><?php echo htmlspecialchars(trim($service)); ?></span>
                                            </div>
                                        </div>

                                        <!-- Required Documents -->
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Required Documents</h4>
                        <div class="space-y-3">
                                                <label class="flex items-center gap-2 text-gray-800">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300" checked>
                                                    <span>Valid Student ID</span>
                                                </label>
                                                <label class="flex items-center gap-2 text-gray-800">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300" checked>
                                                    <span>Certificate of Registration</span>
                                                </label>
                                                <label class="flex items-center gap-2 text-gray-800">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                                    <span>Payment Receipt</span>
                                                </label>
                                                <label class="flex items-center gap-2 text-gray-800">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300" checked>
                                                    <span>2x2 ID Picture</span>
                                                </label>
                                            </div>
                                            <!-- Verification Status Panel -->
                                            <div class="mt-3 rounded-md bg-[#DCFCE7] border-l-4 border-l-[#28A745] flex items-center gap-3 px-4 py-2">
                                                <div class="flex-shrink-0 w-6 h-6 bg-[#28A745] rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </div>
                                                <span class="text-sm font-medium text-[#28A745]">3 of 4 documents verified</span>
                                            </div>
                                        </div>

                                        <!-- Service Notes (only shown if there's a special note) -->
                                        <?php 
                                        // Get special note from queue details (check multiple possible field names)
                                        $serviceNote = '';
                                        if ($currentQueueDetails) {
                                            $serviceNote = $currentQueueDetails['special_note'] ?? 
                                                          $currentQueueDetails['notes'] ?? 
                                                          $currentQueueDetails['stall_note'] ?? 
                                                          $currentQueueDetails['service_note'] ?? '';
                                        }
                                        ?>
                                        <?php if (!empty($serviceNote)): ?>
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-[#111827] mb-2">Service Notes</h4>
                                            <div class="rounded-lg bg-[#F9FAFB] border-l-4 border-l-[#D1D5DB] shadow-sm">
                                                <div class="w-full px-3 py-2 bg-[#F9FAFB] rounded-lg text-[#111827] min-h-[60px]">
                                                    <?php echo htmlspecialchars($serviceNote); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Special Notes -->
                                        <div class="mb-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-exclamation-circle text-yellow-500"></i>
                                                <h4 class="text-sm font-semibold text-gray-700">Special Notes</h4>
                                    </div>
                                            <div class="border rounded-lg bg-gray-50">
                                                <textarea class="w-full px-3 py-2 bg-gray-50 rounded-lg focus:outline-none text-gray-500" rows="2" placeholder="Student needs to submit additional documents for verification."></textarea>
                                </div>
                                        </div>

                                        <button type="button" class="inline-flex items-center gap-2 border-2 border-dashed border-blue-300 text-blue-900 px-4 py-2 rounded-lg hover:bg-blue-50">
                                            <span class="text-xl leading-none">+</span>
                                            Add Note
                                        </button>
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
                            <div id="queueListTotalCount" class="bg-blue-900 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-semibold">
                                <?php echo count($waitingQueues) + count($stalledQueues) + count($skippedQueues); ?>
                            </div>
                        </div>
                        
                        <!-- Active Queue -->
                        <div class="border-b border-gray-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-blue-50 focus:outline-none" onclick="toggleQueueSection('activeQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-users text-blue-600 w-4 h-4"></i>
                                    <h4 class="font-semibold text-blue-900 text-sm">Active Queue</h4>
                                    <div id="activeQueueCount" class="bg-blue-900 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">
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
                                <?php
                                    $isPriority = ($queue['queue_type'] === 'priority');
                                    $numColor = $isPriority ? '#DAA520' : '#003366';
                                    
                                    // Handle services display
                                    $serviceName = 'Service Request';
                                    $additionalServices = 0;
                                    if (isset($queue['services']) && !empty($queue['services'])) {
                                        $servicesArray = explode(', ', $queue['services']);
                                        $serviceName = trim($servicesArray[0]);
                                        $additionalServices = count($servicesArray) - 1;
                                    } elseif (isset($queue['service_name'])) {
                                        $serviceName = $queue['service_name'];
                                    }
                                ?>
                                <div class="px-5 py-4 hover:bg-gray-50 border-b border-gray-100" data-created-at="<?php echo $queue['created_at']; ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <?php if ($isPriority): ?><i class="fas fa-star text-yellow-500 text-xs"></i><?php endif; ?>
                                                <span class="font-bold" style="color: <?php echo $numColor; ?>; "><?php echo htmlspecialchars($queue['queue_number']); ?></span>
                                            </div>
                                            <div class="text-sm text-gray-900 leading-tight"><?php echo htmlspecialchars($queue['student_name']); ?></div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo htmlspecialchars($serviceName); ?>
                                                <?php if ($additionalServices > 0): ?>
                                                    <span class="text-blue-600 font-semibold"> +<?php echo $additionalServices; ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mt-2 text-xs text-blue-900 flex items-center gap-2">
                                                <span class="w-2 h-2 bg-blue-600 rounded-full inline-block"></span>
                                                <span>Waiting in queue</span>
                                            </div>
                                        </div>
                                        <div class="shrink-0">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-full active-queue-timer" style="background-color: #EFF6FF; color: #003366;">
                                                <i class="fas fa-clock"></i>
                                                <span class="active-timer-display">0 min</span>
                                        </span>
                                        </div>
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
                                    <div id="stalledQueueCount" class="bg-yellow-400 text-yellow-900 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">
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
                                <?php
                                    $isPriority = (($queue['queue_type'] ?? 'regular') === 'priority');
                                    $numColor = $isPriority ? '#DAA520' : '#003366';
                                    
                                    // Handle services display
                                    $serviceName = 'Service Request';
                                    $additionalServices = 0;
                                    if (isset($queue['services']) && !empty($queue['services'])) {
                                        $servicesArray = explode(', ', $queue['services']);
                                        $serviceName = trim($servicesArray[0]);
                                        $additionalServices = count($servicesArray) - 1;
                                    } elseif (isset($queue['service_name'])) {
                                        $serviceName = $queue['service_name'];
                                    }
                                ?>
                                <div class="px-5 py-4 hover:bg-gray-50 border-b border-gray-100" data-created-at="<?php echo $queue['created_at']; ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <?php if ($isPriority): ?><i class="fas fa-star text-yellow-500 text-xs"></i><?php endif; ?>
                                                <span class="font-bold" style="color: <?php echo $numColor; ?>; "><?php echo htmlspecialchars($queue['queue_number']); ?></span>
                                            </div>
                                            <div class="text-sm text-gray-900 leading-tight"><?php echo htmlspecialchars($queue['student_name']); ?></div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo htmlspecialchars($serviceName); ?>
                                                <?php if ($additionalServices > 0): ?>
                                                    <span class="text-blue-600 font-semibold"> +<?php echo $additionalServices; ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mt-2">
                                                <span class="inline-flex items-center gap-2 text-[11px] font-medium bg-yellow-50 text-yellow-800 px-3 py-1 rounded">
                                                    <i class="fas fa-file text-yellow-600"></i>
                                                    Missing Documents
                                                </span>
                                            </div>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            <div class="mb-2">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-rose-100 text-rose-600 px-2.5 py-1 rounded-full stalled-queue-timer">
                                                    <i class="fas fa-clock"></i>
                                                    <span class="stalled-timer-display">0 min</span>
                                                </span>
                                            </div>
                                            <button class="px-4 py-2 bg-[#003366] hover:opacity-90 text-white rounded w-28 text-sm">
                                                Resume
                                            </button>
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
                                    <div id="skippedQueueCount" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">
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
                                <?php
                                    $isPriority = (($queue['queue_type'] ?? 'regular') === 'priority');
                                    $numColor = $isPriority ? '#DAA520' : '#003366';
                                    
                                    // Handle services display
                                    $serviceName = 'Service Request';
                                    $additionalServices = 0;
                                    if (isset($queue['services']) && !empty($queue['services'])) {
                                        $servicesArray = explode(', ', $queue['services']);
                                        $serviceName = trim($servicesArray[0]);
                                        $additionalServices = count($servicesArray) - 1;
                                    } elseif (isset($queue['service_name'])) {
                                        $serviceName = $queue['service_name'];
                                    }
                                    $queueId = $queue['id'] ?? 0;
                                ?>
                                <div class="px-5 py-4 hover:bg-gray-50 border-b border-gray-100" data-queue-id="<?php echo $queueId; ?>" data-created-at="<?php echo $queue['created_at']; ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <?php if ($isPriority): ?><i class="fas fa-star text-yellow-500 text-xs"></i><?php endif; ?>
                                                <span class="font-bold" style="color: <?php echo $numColor; ?>;"><?php echo htmlspecialchars($queue['queue_number']); ?></span>
                                            </div>
                                            <div class="text-sm text-gray-900 leading-tight"><?php echo htmlspecialchars($queue['student_name']); ?></div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo htmlspecialchars($serviceName); ?>
                                                <?php if ($additionalServices > 0): ?>
                                                    <span class="text-blue-600 font-semibold"> +<?php echo $additionalServices; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            <div class="mb-2">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-red-100 text-red-600 px-2.5 py-1 rounded-full">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    <span class="timer-display">0:00 left</span>
                                                </span>
                                            </div>
                                            <button onclick="resumeSkippedQueue(<?php echo $queueId; ?>)" class="px-4 py-2 bg-[#003366] hover:opacity-90 text-white rounded w-28 text-sm font-medium">
                                                Resume
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Transaction Status -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6" style="background-color: #DBEAFE;">
                        <h3 class="text-lg font-semibold mb-4" style="color: #003366;">Today's Transaction Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Average Service Time -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #003366; box-shadow: 0 0 0 8px #EFF6FF;">
                                    <i class="fas fa-clock text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;"><?php echo $avgServiceTime; ?> min</p>
                                <p class="text-sm" style="color: #485563;">Avg Service Time</p>
                            </div>
                            <!-- Completed -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #28A745; box-shadow: 0 0 0 8px #DCFCE7;">
                                    <i class="fas fa-check text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;"><?php echo $completedCount; ?></p>
                                <p class="text-sm" style="color: #485563;">Completed</p>
                            </div>
                            <!-- Stalled -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #FFD700; box-shadow: 0 0 0 8px rgba(255, 215, 0, 0.1);">
                                    <i class="fas fa-pause text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;"><?php echo count($stalledQueues); ?></p>
                                <p class="text-sm" style="color: #485563;">Stalled</p>
                            </div>
                            <!-- Cancelled -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #DC3545; box-shadow: 0 0 0 8px #FEE2E2;">
                                    <i class="fas fa-times text-white text-2xl"></i>
                                </div>
                                <p id="statCancelled" class="text-2xl font-bold mb-1" style="color: #003366;"><?php echo count($skippedQueues); ?></p>
                                <p class="text-sm" style="color: #485563;">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../../Footer.php'; ?>
    
    <script>
        // Service tabs controller
        function showServiceTab(index) {
            const panels = document.querySelectorAll('[id^="svc-panel-"]');
            const tabs = document.querySelectorAll('[id^="svc-tab-"]');
            for (let i = 0; i < panels.length; i++) {
                const isActive = i === index;
                if (isActive) {
                    panels[i].classList.remove('hidden');
                    tabs[i].classList.remove('bg-white', 'text-blue-900', 'border-blue-200');
                    tabs[i].classList.add('bg-blue-900', 'text-white', 'border-blue-900');
                    tabs[i].setAttribute('aria-selected', 'true');
                } else {
                    panels[i].classList.add('hidden');
                    tabs[i].classList.remove('bg-blue-900', 'text-white', 'border-blue-900');
                    tabs[i].classList.add('bg-white', 'text-blue-900', 'border-blue-200');
                    tabs[i].setAttribute('aria-selected', 'false');
                }
            }
        }

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
        
        // Resume skipped queue
        function resumeSkippedQueue(queueId) {
            if (confirm('Resume this skipped queue and put it back in the active queue?')) {
                window.location.href = `queue_actions.php?action=resume&id=${queueId}`;
            }
        }
        
        // Get skip timeout from backend settings (backend-ready)
        async function getSkipTimeout() {
            try {
                // TODO: Replace with actual API call
                const response = await fetch('/api/admin/settings');
                if (response.ok) {
                    const settings = await response.json();
                    const timeoutValue = settings.skipTimeout || 1;
                    const timeoutUnit = settings.skipTimeoutUnit || 'hours';
                    
                    // Convert to seconds
                    if (timeoutUnit === 'minutes') {
                        return timeoutValue * 60;
                    } else {
                        return timeoutValue * 60 * 60; // hours to seconds
                    }
                }
            } catch (error) {
                console.log('Could not fetch timeout settings, using default');
            }
            // Default: 1 hour
            return 60 * 60;
        }
        
        // Cancel skipped queue via backend (backend-ready)
        async function cancelSkippedQueue(queueId) {
            try {
                // TODO: Replace with actual API call
                const response = await fetch(`queue_actions.php?action=cancel&id=${queueId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                if (response.ok) {
                    const result = await response.json();
                    return result.success || true;
                }
            } catch (error) {
                console.log('Could not cancel queue via backend, removing from UI only');
            }
            // Fallback: Return true to allow UI update
            return true;
        }
        
        // Timer functionality for skipped queues (backend-ready)
        async function startSkippedQueueTimers() {
            const skippedQueueItems = document.querySelectorAll('#skippedQueue-content [data-queue-id]');
            
            // Get timeout from backend (or use default)
            const totalSeconds = await getSkipTimeout();
            
            skippedQueueItems.forEach(item => {
                const createdAt = item.getAttribute('data-created-at');
                const queueId = item.getAttribute('data-queue-id');
                if (!createdAt) return;
                
                const timerDisplay = item.querySelector('.timer-display');
                if (!timerDisplay) return;
                
                // Calculate initial remaining time
                const createdAtTime = new Date(createdAt).getTime();
                
                // Function to update timer display
                const updateTimer = () => {
                    const now = Date.now();
                    const diff = now - createdAtTime;
                    const remainingSeconds = Math.max(0, totalSeconds - Math.floor(diff / 1000));
                    
                    if (remainingSeconds <= 0) {
                        // Timer expired - auto-cancel
                        timerDisplay.textContent = 'Expired';
                        timerDisplay.closest('.inline-flex').classList.remove('bg-red-100', 'text-red-600');
                        timerDisplay.closest('.inline-flex').classList.add('bg-gray-300', 'text-gray-600');
                        
                        // Cancel queue via backend (backend-ready)
                        cancelSkippedQueue(queueId).then(success => {
                            // Remove from UI after a short delay
                            setTimeout(() => {
                                item.remove();
                                // Update counts after removal
                                updateQueueCounts();
                            }, 2000);
                        });
                        
                        clearInterval(timerInterval);
                    } else {
                        const minutes = Math.floor(remainingSeconds / 60);
                        const seconds = remainingSeconds % 60;
                        timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')} left`;
                    }
                };
                
                // Set initial time immediately
                updateTimer();
                
                // Update timer every second
                const timerInterval = setInterval(updateTimer, 1000);
            });
        }
        
        // Update queue counts after queue removal (backend-ready)
        function updateQueueCounts() {
            const skippedCount = document.getElementById('skippedQueueCount');
            if (skippedCount) {
                const skippedItems = document.querySelectorAll('#skippedQueue-content [data-queue-id]');
                skippedCount.textContent = skippedItems.length;
                
                // Update cancelled count in statistics (backend-ready)
                // TODO: When backend is connected, this should fetch actual cancelled count from API
                const cancelledStat = document.getElementById('statCancelled');
                if (cancelledStat) {
                    cancelledStat.textContent = skippedItems.length;
                }
            }
            
            const totalCount = document.getElementById('queueListTotalCount');
            if (totalCount) {
                const activeItems = document.querySelectorAll('#activeQueue-content [data-created-at]').length;
                const stalledItems = document.querySelectorAll('#stalledQueue-content [data-created-at]').length;
                const skippedItems = document.querySelectorAll('#skippedQueue-content [data-queue-id]').length;
                totalCount.textContent = activeItems + stalledItems + skippedItems;
            }
        }
        
        // Timer functionality for active queues
        function startActiveQueueTimers() {
            const activeQueueItems = document.querySelectorAll('#activeQueue-content [data-created-at]');
            
            activeQueueItems.forEach(item => {
                const createdAt = item.getAttribute('data-created-at');
                if (!createdAt) return;
                
                const timerDisplay = item.querySelector('.active-timer-display');
                if (!timerDisplay) return;
                
                // Set initial time
                const createdAtTime = new Date(createdAt).getTime();
                const now = Date.now();
                const diff = now - createdAtTime;
                let elapsedMinutes = Math.max(0, Math.floor(diff / 60000));
                timerDisplay.textContent = `${elapsedMinutes} min`;
                
                // Update timer every minute
                const timerInterval = setInterval(() => {
                    elapsedMinutes++;
                    timerDisplay.textContent = `${elapsedMinutes} min`;
                }, 60000); // Update every minute
            });
        }
        
        // Timer functionality for stalled queues
        function startStalledQueueTimers() {
            const stalledQueueItems = document.querySelectorAll('#stalledQueue-content [data-created-at]');
            
            stalledQueueItems.forEach(item => {
                const createdAt = item.getAttribute('data-created-at');
                if (!createdAt) return;
                
                const timerDisplay = item.querySelector('.stalled-timer-display');
                if (!timerDisplay) return;
                
                // Set initial time
                const createdAtTime = new Date(createdAt).getTime();
                const now = Date.now();
                const diff = now - createdAtTime;
                let elapsedMinutes = Math.max(0, Math.floor(diff / 60000));
                timerDisplay.textContent = `${elapsedMinutes} min`;
                
                // Update timer every minute
                const timerInterval = setInterval(() => {
                    elapsedMinutes++;
                    timerDisplay.textContent = `${elapsedMinutes} min`;
                }, 60000); // Update every minute
            });
        }
        
        // Initialize timers when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startSkippedQueueTimers();
            startActiveQueueTimers();
            startStalledQueueTimers();
        });
    </script>
</body>
</html>