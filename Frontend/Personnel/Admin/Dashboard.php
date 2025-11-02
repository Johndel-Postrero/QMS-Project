<?php
session_start();
require_once __DIR__ . '/../../Student/db_config.php';
require_once __DIR__ . '/../admin_functions.php';

// Get database connection
$conn = getDBConnection();

// Get all dashboard data
$stats = getQueueStatistics($conn);
$recentActivity = getWaitingQueuesList($conn, 5); // Get last 5 for recent activity
$currentlyServing = getCurrentlyServing($conn);

// Get service information for each queue in recent activity
foreach ($recentActivity as &$activity) {
    $servicesQuery = "SELECT service_name FROM queue_services WHERE queue_id = " . (int)$activity['id'] . " ORDER BY id LIMIT 5";
    $servicesResult = $conn->query($servicesQuery);
    $services = [];
    if ($servicesResult) {
        while ($serviceRow = $servicesResult->fetch_assoc()) {
            $services[] = $serviceRow['service_name'];
        }
    }
    $activity['services'] = $services;
    $activity['first_service'] = !empty($services) ? $services[0] : 'Service';
    $activity['additional_count'] = max(0, count($services) - 1);
}
unset($activity); // Break reference

// Get top services
$topServicesQuery = "
    SELECT qs.service_name, COUNT(*) as count
    FROM queue_services qs
    JOIN queues q ON qs.queue_id = q.id
    WHERE DATE(q.created_at) = CURDATE()
    GROUP BY qs.service_name
    ORDER BY count DESC
";
$topServicesResult = $conn->query($topServicesQuery);
$topServicesData = [];
while ($row = $topServicesResult->fetch_assoc()) {
    $topServicesData[$row['service_name']] = (int)$row['count'];
}

// Define all 6 services
$allServices = [
    'Good Moral Certificate',
    'Transcript Request',
    'Certificate Request',
    'ID Validation',
    'Request for Uniform Exemption',
    'Insurance Payment'
];

// Merge real data with all services (fill missing with 0)
$topServices = [];
foreach ($allServices as $serviceName) {
    $topServices[] = [
        'service_name' => $serviceName,
        'count' => isset($topServicesData[$serviceName]) ? $topServicesData[$serviceName] : 0
    ];
}

// Sort by count (highest first)
usort($topServices, function($a, $b) {
    return $b['count'] - $a['count'];
});

// Filter out services with count 0
$topServices = array_filter($topServices, function($service) {
    return $service['count'] > 0;
});

// Re-index array
$topServices = array_values($topServices);

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SeQueueR</title>
    <link rel="icon" type="image/png" href="/Frontend/favicon.php">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .stat-card {
            transition: transform 0.2s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .service-bar {
            transition: width 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include Admin Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        <div class="py-8 pb-4 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <!-- Summary Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Queues Today -->
                <div class="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_today']; ?></p>
                            <p class="text-sm text-gray-600">Total Queues Today</p>
                        </div>
                    </div>
                </div>

                <!-- Currently Serving -->
                <div class="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-3xl font-bold text-gray-900">
                                <?php 
                                echo $stats['serving'] > 0 
                                    ? (isset($currentlyServing[0]) ? $currentlyServing[0]['queue_number'] : '--')
                                    : '--';
                                ?>
                            </p>
                            <p class="text-sm text-gray-600">Currently Serving</p>
                            <p class="text-xs text-gray-500">
                                <?php 
                                echo $stats['serving'] > 0 && isset($currentlyServing[0]['window_number'])
                                    ? 'Counter ' . $currentlyServing[0]['window_number']
                                    : 'No active counter';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Completed Queues -->
                <div class="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-3xl font-bold text-gray-900"><?php echo $stats['completed']; ?></p>
                            <p class="text-sm text-gray-600">Completed Queues</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Queues -->
                <div class="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-3xl font-bold text-gray-900"><?php echo $stats['waiting']; ?></p>
                            <p class="text-sm text-gray-600">Pending Queues</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Queue Status Overview -->
                    <div class="bg-white rounded-lg shadow-sm border border-[#E5E7EB] p-6">
                        <h3 class="text-lg font-semibold text-[#1E3A8A] mb-4">Queue Status Overview</h3>
                        <div class="flex flex-col items-center gap-4" id="queueStatusContainer">
                            <!-- Pie Chart -->
                            <div class="w-48 h-48 mx-auto">
                                <canvas id="queueStatusChart" width="192" height="192"></canvas>
                            </div>
                            
                            <!-- Legend - Two Columns -->
                            <div class="w-full">
                                <div class="grid grid-cols-2 gap-x-8 gap-y-3" id="queueStatusLegend">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                            <a href="History.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Queue #</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody id="recentActivityBody" class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($recentActivity)): ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            No recent activity
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($recentActivity as $activity): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if ($activity['queue_type'] === 'priority'): ?>
                                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                                <?php endif; ?>
                                                <span class="text-sm font-medium text-blue-600"><?php echo htmlspecialchars($activity['queue_number']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['student_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($activity['student_id']); ?></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <?php 
                                            $firstServiceName = isset($activity['first_service']) ? $activity['first_service'] : 'Service';
                                            $additionalCount = isset($activity['additional_count']) ? $activity['additional_count'] : 0;
                                            
                                            // Determine icon based on service type
                                            $serviceIcon = 'fa-file-alt';
                                            $iconColor = 'text-blue-600';
                                            if (stripos($firstServiceName, 'good moral') !== false) {
                                                $serviceIcon = 'fa-star';
                                                $iconColor = 'text-yellow-500';
                                            } elseif (stripos($firstServiceName, 'transcript') !== false) {
                                                $serviceIcon = 'fa-file-alt';
                                                $iconColor = 'text-blue-600';
                                            } elseif (stripos($firstServiceName, 'certificate') !== false) {
                                                $serviceIcon = 'fa-id-card';
                                                $iconColor = 'text-green-600';
                                            }
                                            ?>
                                            <div class="flex items-center space-x-2">
                                                <i class="fas <?php echo $serviceIcon; ?> <?php echo $iconColor; ?> text-sm"></i>
                                                <span class="text-sm text-gray-900"><?php echo htmlspecialchars($firstServiceName); ?></span>
                                                <?php if ($additionalCount > 0): ?>
                                                <span class="text-sm text-blue-600 font-medium">+<?php echo $additionalCount; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Waiting
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-900"><?php 
                                                    $time = new DateTime($activity['created_at']);
                                                    echo $time->format('M d, Y');
                                                ?></span>
                                                <span class="text-sm text-gray-600"><?php 
                                                    echo $time->format('g:i A');
                                                ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-[#FFFFFF] rounded-lg shadow-sm border border-[#E5E7EB] p-6">
                        <h3 class="text-lg font-semibold text-[#1E3ABA] mb-6">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="Queue.php" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-[#FFFFFF] border border-[#1E3ABA] text-[#1E3ABA] rounded-lg hover:bg-[#E5E7EB] transition-colors">
                                <i class="fas fa-list text-[#1E3ABA]"></i>
                                <span>Manage Queues</span>
                            </a>
                            <a href="User.php" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-[#1E3ABA] text-[#FFFFFF] rounded-lg hover:opacity-90 transition-colors">
                                <i class="fas fa-plus text-[#FFFFFF]"></i>
                                <span>Add Account</span>
                            </a>
                            <button onclick="window.location.href='History.php'" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-[#FFFFFF] border border-[#1E3ABA] text-[#1E3ABA] rounded-lg hover:bg-[#E5E7EB] transition-colors">
                                <i class="fas fa-download text-[#1E3ABA]"></i>
                                <span>Generate Report</span>
                            </button>
                        </div>
                    </div>

                    <!-- Top Services Today -->
                    <div class="bg-[#FFFFFF] rounded-lg shadow-sm border border-[#E5E7EB] p-6">
                        <h3 class="text-lg font-semibold text-[#1E3ABA] mb-6">Top Services Today</h3>
                        <div class="space-y-4" id="topServicesContainer">
                            <?php if (empty($topServices)): ?>
                            <p class="text-[#000000] text-sm">No services data available</p>
                            <?php else: ?>
                            <?php 
                            $maxCount = max(array_column($topServices, 'count'));
                            foreach ($topServices as $service): 
                            ?>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-[#000000] mb-1"><?php echo htmlspecialchars($service['service_name']); ?></span>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 bg-[#E5E7EB] rounded-full h-2">
                                        <div class="service-bar bg-[#1E3ABA] h-2 rounded-full" style="width: <?php echo ($service['count'] / $maxCount) * 100; ?>%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-[#000000] flex-shrink-0"><?php echo $service['count']; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">System Status</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900">System Online</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900">Queue System Active</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-4">
                                Last updated: <span id="lastUpdatedTime"><?php echo date('g:i A'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Include Footer -->
    <?php include '../../Footer.php'; ?>
    
    <script>
        // Dashboard data from PHP
        const dashboardData = {
            queueStatus: {
                waiting: <?php echo $stats['waiting'] ?? 0; ?>,
                serving: <?php echo $stats['serving'] ?? 0; ?>,
                completed: <?php echo $stats['completed'] ?? 0; ?>,
                stalled: <?php echo $stats['stalled'] ?? 0; ?>,
                skipped: <?php echo $stats['skipped'] ?? 0; ?>,
                cancelled: <?php echo $stats['cancelled'] ?? 0; ?>
            }
        };
        
        let queueStatusChart = null;
        
        // Color scheme from design
        const statusColors = {
            completed: '#064E40',      // Dark teal/forest green
            waiting: '#2563EB',        // Vibrant blue (pie chart)
            waitingSwatch: '#004178',  // Deep blue (legend swatch)
            stalled: '#DAA520',       // Golden yellow
            skipped: '#687280',       // Medium gray
            inService: '#000000',      // Black
            cancelled: '#991B1B'       // Deep red
        };
        const textColor = '#374151';   // Dark muted blue-gray
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateQueueStatusChart();
            updateRecentActivity();
            updateTopServices();
            updateLastUpdatedTime();
            
            // Auto-refresh every 30 seconds
            setInterval(() => location.reload(), 30000);
            setInterval(updateLastUpdatedTime, 60000);
        });
        
        // Update Recent Activity with dummy data if empty
        function updateRecentActivity() {
            const tbody = document.getElementById('recentActivityBody');
            if (!tbody) return;
            
            // Check if table is empty (has only one row with "No recent activity" message)
            const isEmpty = tbody.querySelector('td[colspan]') !== null;
            
            // Dummy data generation disabled - only show real data
            /* if (isEmpty) {
                // Generate dummy data for recent activity
                const dummyActivity = generateDummyRecentActivity();
                tbody.innerHTML = dummyActivity.map(activity => {
                    const serviceIcon = getServiceIcon(activity.firstService);
                    const dateStr = activity.dateTime.toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric'
                    });
                    const timeStr = activity.dateTime.toLocaleTimeString('en-US', { 
                        hour: 'numeric', 
                        minute: '2-digit', 
                        hour12: true 
                    });
                    
                    return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                ${activity.isPriority ? '<i class="fas fa-star text-yellow-500 mr-2"></i>' : ''}
                                <span class="text-sm font-medium text-blue-600">${activity.queueNumber}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${activity.studentName}</div>
                            <div class="text-sm text-gray-500">${activity.studentId}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <i class="fas ${serviceIcon.icon} ${serviceIcon.color} text-sm"></i>
                                <span class="text-sm text-gray-900">${activity.firstService}</span>
                                ${activity.additionalCount > 0 ? `<span class="text-sm text-blue-600 font-medium">+${activity.additionalCount}</span>` : ''}
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${activity.statusClass}">
                                ${activity.status}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-900">${dateStr}</span>
                                <span class="text-sm text-gray-600">${timeStr}</span>
                            </div>
                        </td>
                    </tr>
                `;
                }).join('');
            } */
        }
        
        // Get service icon and color based on service name
        function getServiceIcon(serviceName) {
            const lowerName = serviceName.toLowerCase();
            if (lowerName.includes('good moral')) {
                return { icon: 'fa-star', color: 'text-yellow-500' };
            } else if (lowerName.includes('transcript')) {
                return { icon: 'fa-file-alt', color: 'text-blue-600' };
            } else if (lowerName.includes('certificate')) {
                return { icon: 'fa-id-card', color: 'text-green-600' };
            }
            return { icon: 'fa-file-alt', color: 'text-gray-600' };
        }
        
        // Generate dummy recent activity data
        function generateDummyRecentActivity() {
            // Match the image: Maria Santos, John Dela Cruz, Anna Rodriguez
            const activities = [
                {
                    queueNumber: 'R-001',
                    studentName: 'Maria Santos',
                    studentId: '2021-10001',
                    firstService: 'Good Moral Certificate',
                    additionalCount: 1,
                    status: 'Completed',
                    statusClass: 'bg-green-100 text-green-800',
                    isPriority: false,
                    dateTime: new Date(Date.now() - 13 * 60 * 1000) // 13 minutes ago
                },
                {
                    queueNumber: 'P-002',
                    studentName: 'John Dela Cruz',
                    studentId: '2022-10002',
                    firstService: 'Transcript Request',
                    additionalCount: 0,
                    status: 'Cancelled',
                    statusClass: 'bg-red-100 text-red-800',
                    isPriority: true,
                    dateTime: new Date(Date.now() - 21 * 60 * 1000) // 21 minutes ago
                },
                {
                    queueNumber: 'R-003',
                    studentName: 'Anna Rodriguez',
                    studentId: '2023-10003',
                    firstService: 'Certificate Request',
                    additionalCount: 0,
                    status: 'Completed',
                    statusClass: 'bg-green-100 text-green-800',
                    isPriority: false,
                    dateTime: new Date(Date.now() - 29 * 60 * 1000) // 29 minutes ago
                }
            ];
            
            return activities;
        }
        
        // Update queue status chart
        function updateQueueStatusChart() {
            const container = document.getElementById('queueStatusContainer');
            
            // Order: Completed, Waiting, Stalled, Skipped, In Service, Cancelled
            const data = [
                dashboardData.queueStatus.completed || 0,
                dashboardData.queueStatus.waiting || 0,
                dashboardData.queueStatus.stalled || 0,
                dashboardData.queueStatus.skipped || 0,
                dashboardData.queueStatus.serving || 0,
                dashboardData.queueStatus.cancelled || 0
            ];
            
            const total = data.reduce((sum, value) => sum + value, 0);
            
            // If no data, show empty state
            if (total === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12">
                        <i class="fas fa-chart-pie text-gray-300 text-6xl mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 mb-2">No Queue Data Available</p>
                        <p class="text-sm text-gray-500">Queue status will appear here once transactions are recorded.</p>
                    </div>
                `;
                return;
            }
            
            // If container was previously showing empty state, restore the original HTML structure
            const ctx = document.getElementById('queueStatusChart');
            if (!ctx) {
                // Recreate the structure if it was removed
                container.innerHTML = `
                    <div class="w-48 h-48 mx-auto">
                        <canvas id="queueStatusChart" width="192" height="192"></canvas>
                    </div>
                    <div class="w-full">
                        <div class="grid grid-cols-2 gap-x-8 gap-y-3" id="queueStatusLegend"></div>
                    </div>
                `;
            }
            
            const ctxElement = document.getElementById('queueStatusChart');
            const labels = ['Completed', 'Waiting', 'Stalled', 'Skipped', 'In Service', 'Cancelled'];
            const colors = [
                statusColors.completed,
                statusColors.waiting,
                statusColors.stalled,
                statusColors.skipped,
                statusColors.inService,
                statusColors.cancelled
            ];
            
            queueStatusChart = new Chart(ctxElement.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            updateQueueStatusLegend(data, labels, total);
        }
        
        // Update legend in two columns
        function updateQueueStatusLegend(data, labels, total) {
            const legend = document.getElementById('queueStatusLegend');
            
            // Legend order: Left column (Waiting, In Service, Skipped), Right column (Completed, Stalled, Cancelled)
            const legendItems = [
                { label: 'Waiting', value: data[1], color: statusColors.waitingSwatch, index: 1 },
                { label: 'In Service', value: data[4], color: statusColors.inService, index: 4 },
                { label: 'Skipped', value: data[3], color: statusColors.skipped, index: 3 },
                { label: 'Completed', value: data[0], color: statusColors.completed, index: 0 },
                { label: 'Stalled', value: data[2], color: statusColors.stalled, index: 2 },
                { label: 'Cancelled', value: data[5], color: statusColors.cancelled, index: 5 }
            ];
            
            legend.innerHTML = legendItems.map(item => {
                const percentage = total > 0 ? Math.round((item.value / total) * 100) : 0;
                return `
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded" style="background-color: ${item.color}"></div>
                        <span class="text-sm font-medium" style="color: ${textColor}">${item.label}: ${item.value} (${percentage}%)</span>
                    </div>
                `;
            }).join('');
        }
        
        // Update Top Services Today - show empty state if no services
        function updateTopServices() {
            const container = document.getElementById('topServicesContainer');
            if (!container) return;
            
            // Check if container is empty
            const hasContent = container.querySelector('.flex.flex-col');
            
            if (!hasContent) {
                // Show empty state since we don't show services with 0 count
                container.innerHTML = '<p class="text-[#000000] text-sm">No services data available</p>';
            }
        }
        
        // Render top services (hide services with count 0)
        function renderTopServices(services) {
            const container = document.getElementById('topServicesContainer');
            if (!container) return;
            
            // Filter out services with count 0
            const filteredServices = services.filter(service => service.count > 0);
            
            // If no services, show empty state
            if (filteredServices.length === 0) {
                container.innerHTML = '<p class="text-[#000000] text-sm">No services data available</p>';
                return;
            }
            
            // Sort by count (highest first)
            const sortedServices = [...filteredServices].sort((a, b) => b.count - a.count);
            const maxCount = Math.max(...sortedServices.map(s => s.count), 1);
            
            container.innerHTML = sortedServices.map(service => {
                const widthPercent = (service.count / maxCount) * 100;
                return `
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-[#000000] mb-1">${service.name}</span>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-[#E5E7EB] rounded-full h-2">
                                <div class="service-bar bg-[#1E3ABA] h-2 rounded-full" style="width: ${widthPercent}%"></div>
                            </div>
                            <span class="text-sm font-bold text-[#000000] flex-shrink-0">${service.count}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        // Update time
        function updateLastUpdatedTime() {
            const now = new Date();
            document.getElementById('lastUpdatedTime').textContent = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }
    </script>
</body>
</html>