<?php
// Include Admin Header
include 'Header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SeQueueR</title>
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
    <!-- Main Content -->
    <main class="min-h-screen">
        <div class="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <!-- Summary Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Queues Today -->
                <div class="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-3xl font-bold text-gray-900" id="totalQueuesToday">0</p>
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
                            <p class="text-3xl font-bold text-gray-900" id="currentlyServing">--</p>
                            <p class="text-sm text-gray-600">Currently Serving</p>
                            <p class="text-xs text-gray-500" id="servingCounter">Counter 1</p>
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
                            <p class="text-3xl font-bold text-gray-900" id="completedQueues">0</p>
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
                            <p class="text-3xl font-bold text-gray-900" id="pendingQueues">0</p>
                            <p class="text-sm text-gray-600">Pending Queues</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Queue Status Overview and Recent Activity -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Queue Status Overview -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Queue Status Overview</h3>
                        <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-8">
                            <!-- Pie Chart -->
                            <div class="flex-1 mb-6 lg:mb-0">
                                <canvas id="queueStatusChart" width="300" height="300"></canvas>
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex-1">
                                <div class="space-y-3" id="queueStatusLegend">
                                    <!-- Legend items will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                            <a href="History.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                        </div>
                        
                        <!-- Activity Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody id="recentActivityTable" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Actions, Top Services, System Status -->
                <div class="space-y-8">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="Queue.php" class="w-full flex items-center justify-center space-x-2 px-4 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-list"></i>
                                <span>Manage Queues</span>
                            </a>
                            <a href="User.php" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>Add Account</span>
                            </a>
                            <button onclick="generateReport()" class="w-full flex items-center justify-center space-x-2 px-4 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-download"></i>
                                <span>Generate Report</span>
                            </button>
                        </div>
                    </div>

                    <!-- Top Services Today -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Services Today</h3>
                        <div class="space-y-4" id="topServicesList">
                            <!-- Service items will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">System Status</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full" id="systemStatusDot"></div>
                                <span class="text-sm font-medium text-gray-900" id="systemStatusText">System Online</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full" id="queueSystemStatusDot"></div>
                                <span class="text-sm font-medium text-gray-900" id="queueSystemStatusText">Queue System Active</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-4" id="lastUpdated">
                                Last updated: <span id="lastUpdatedTime">--</span>
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
        // Backend-ready JavaScript for Admin Dashboard
        let dashboardData = {
            summary: {
                totalQueuesToday: 0,
                currentlyServing: '--',
                servingCounter: 'Counter 1',
                completedQueues: 0,
                pendingQueues: 0
            },
            queueStatus: {
                waiting: 0,
                inService: 0,
                skipped: 0,
                completed: 0,
                stalled: 0,
                cancelled: 0
            },
            recentActivity: [],
            topServices: [],
            systemStatus: {
                systemOnline: true,
                queueSystemActive: true,
                lastUpdated: new Date()
            }
        };
        
        let queueStatusChart = null;
        
        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            setupEventListeners();
            updateLastUpdatedTime();
            
            // Auto-refresh every 30 seconds
            setInterval(loadDashboardData, 30000);
            setInterval(updateLastUpdatedTime, 60000);
        });
        
        // Load dashboard data from backend
        function loadDashboardData() {
            // TODO: Replace with actual API call
            fetch('/api/admin/dashboard')
                .then(response => response.json())
                .then(data => {
                    dashboardData = data;
                    updateDashboard();
                })
                .catch(error => {
                    console.log('No backend connection yet - using empty state');
                    // Reset to empty state when no backend
                    dashboardData = {
                        summary: {
                            totalQueuesToday: 0,
                            currentlyServing: '--',
                            servingCounter: 'Counter 1',
                            completedQueues: 0,
                            pendingQueues: 0
                        },
                        queueStatus: {
                            waiting: 0,
                            inService: 0,
                            skipped: 0,
                            completed: 0,
                            stalled: 0,
                            cancelled: 0
                        },
                        recentActivity: [],
                        topServices: [],
                        systemStatus: {
                            systemOnline: true,
                            queueSystemActive: true,
                            lastUpdated: new Date()
                        }
                    };
                    updateDashboard();
                });
        }
        
        // Update dashboard display
        function updateDashboard() {
            updateSummaryCards();
            updateQueueStatusChart();
            updateRecentActivity();
            updateTopServices();
            updateSystemStatus();
        }
        
        // Update summary cards
        function updateSummaryCards() {
            document.getElementById('totalQueuesToday').textContent = dashboardData.summary.totalQueuesToday;
            document.getElementById('currentlyServing').textContent = dashboardData.summary.currentlyServing;
            document.getElementById('servingCounter').textContent = dashboardData.summary.servingCounter;
            document.getElementById('completedQueues').textContent = dashboardData.summary.completedQueues;
            document.getElementById('pendingQueues').textContent = dashboardData.summary.pendingQueues;
        }
        
        // Update queue status chart
        function updateQueueStatusChart() {
            const ctx = document.getElementById('queueStatusChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (queueStatusChart) {
                queueStatusChart.destroy();
            }
            
            const data = [
                dashboardData.queueStatus.waiting,
                dashboardData.queueStatus.inService,
                dashboardData.queueStatus.skipped,
                dashboardData.queueStatus.completed,
                dashboardData.queueStatus.stalled,
                dashboardData.queueStatus.cancelled
            ];
            
            const total = data.reduce((sum, value) => sum + value, 0);
            
            queueStatusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Waiting', 'In Service', 'Skipped', 'Completed', 'Stalled', 'Cancelled'],
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#3B82F6', // Blue
                            '#6B7280', // Gray
                            '#9CA3AF', // Light Gray
                            '#10B981', // Green
                            '#F59E0B', // Yellow
                            '#EF4444'  // Red
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Update legend
            updateQueueStatusLegend(data, total);
        }
        
        // Update queue status legend
        function updateQueueStatusLegend(data, total) {
            const legend = document.getElementById('queueStatusLegend');
            const labels = ['Waiting', 'In Service', 'Skipped', 'Completed', 'Stalled', 'Cancelled'];
            const colors = ['#3B82F6', '#6B7280', '#9CA3AF', '#10B981', '#F59E0B', '#EF4444'];
            
            legend.innerHTML = labels.map((label, index) => {
                const value = data[index];
                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                return `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded" style="background-color: ${colors[index]}"></div>
                            <span class="text-sm text-gray-700">${label}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">${value} (${percentage}%)</span>
                    </div>
                `;
            }).join('');
        }
        
        // Update recent activity table
        function updateRecentActivity() {
            const tbody = document.getElementById('recentActivityTable');
            
            if (dashboardData.recentActivity.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            No recent activity
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = dashboardData.recentActivity.map(activity => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            ${activity.priority === 'priority' ? '<i class="fas fa-star text-yellow-500 mr-2"></i>' : ''}
                            <span class="text-sm font-medium text-blue-600">${activity.queueNumber}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">${activity.studentName}</div>
                            <div class="text-sm text-gray-500">${activity.studentId}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <i class="fas fa-certificate text-yellow-500 mr-2"></i>
                            <span class="text-sm text-gray-900">${activity.serviceType}</span>
                            ${activity.additionalServices > 0 ? `<span class="text-xs text-blue-600 ml-1">+${activity.additionalServices}</span>` : ''}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                            activity.status === 'completed' ? 'bg-green-100 text-green-800' :
                            activity.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                            'bg-yellow-100 text-yellow-800'
                        }">
                            ${activity.status.charAt(0).toUpperCase() + activity.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                        <div>
                            <div>${activity.date}</div>
                            <div class="text-gray-500">${activity.time}</div>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        // Update top services
        function updateTopServices() {
            const container = document.getElementById('topServicesList');
            
            if (dashboardData.topServices.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No services data available</p>';
                return;
            }
            
            const maxCount = Math.max(...dashboardData.topServices.map(service => service.count));
            
            container.innerHTML = dashboardData.topServices.map(service => `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-900">${service.name}</span>
                            <span class="text-sm font-bold text-gray-900">${service.count}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="service-bar bg-blue-600 h-2 rounded-full" style="width: ${(service.count / maxCount) * 100}%"></div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        // Update system status
        function updateSystemStatus() {
            const systemDot = document.getElementById('systemStatusDot');
            const systemText = document.getElementById('systemStatusText');
            const queueDot = document.getElementById('queueSystemStatusDot');
            const queueText = document.getElementById('queueSystemStatusText');
            
            // System status
            if (dashboardData.systemStatus.systemOnline) {
                systemDot.className = 'w-3 h-3 bg-green-500 rounded-full';
                systemText.textContent = 'System Online';
            } else {
                systemDot.className = 'w-3 h-3 bg-red-500 rounded-full';
                systemText.textContent = 'System Offline';
            }
            
            // Queue system status
            if (dashboardData.systemStatus.queueSystemActive) {
                queueDot.className = 'w-3 h-3 bg-green-500 rounded-full';
                queueText.textContent = 'Queue System Active';
            } else {
                queueDot.className = 'w-3 h-3 bg-red-500 rounded-full';
                queueText.textContent = 'Queue System Inactive';
            }
        }
        
        // Update last updated time
        function updateLastUpdatedTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
            document.getElementById('lastUpdatedTime').textContent = timeString;
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // Add any additional event listeners here
        }
        
        // Generate report function
        function generateReport() {
            // TODO: Implement report generation
            console.log('Generate report - Backend not implemented yet');
        }
    </script>
</body>
</html>