<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Management - SeQueueR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include Working Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="bg-blue-900 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-6">
            <!-- Currently Serving Card -->
            <div class="bg-yellow-400 border-2 border-gray-800 rounded-lg p-6 mb-8 text-center">
                <div class="text-6xl font-bold text-gray-900 mb-2" id="currentQueueNumber">--</div>
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-3 h-3 bg-gray-400 rounded-full" id="servingStatus"></div>
                    <span class="text-gray-900 font-semibold" id="servingText">No Active Queue</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Student Details and Services -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Student Information & Queue Details Card -->
                    <div class="bg-white rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm text-gray-600">Full Name:</span>
                                        <p class="font-medium" id="studentName">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Student ID:</span>
                                        <p class="font-medium" id="studentId">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Course:</span>
                                        <p class="font-medium" id="studentCourse">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Year Level:</span>
                                        <p class="font-medium" id="studentYear">--</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Queue Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Queue Details</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm text-gray-600">Priority Type:</span>
                                        <div class="mt-1" id="priorityType">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                <i class="fas fa-circle mr-1"></i>
                                                Regular
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Time Requested:</span>
                                        <p class="font-medium" id="timeRequested">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Total Wait Time:</span>
                                        <p class="font-medium" id="waitTime">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requested Services Card -->
                    <div class="bg-white rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Requested Services</h3>
                        
                        <!-- Service Request -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Service Request:</h4>
                            <div id="serviceRequests">
                                <p class="text-gray-500 text-sm">No service requests</p>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Required Documents:</h4>
                            <div id="requiredDocuments" class="space-y-2">
                                <p class="text-gray-500 text-sm">No documents required</p>
                            </div>
                            <p class="text-gray-500 text-sm mt-2" id="documentStatus">0 of 0 documents verified</p>
                        </div>

                        <!-- Service Notes -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-800 mb-3">Service Notes:</h4>
                            <textarea id="serviceNotes" class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter service notes..."></textarea>
                        </div>

                        <!-- Special Notes -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-2 mb-3">
                                <i class="fas fa-info-circle text-yellow-500"></i>
                                <h4 class="text-md font-medium text-gray-800">Special Notes:</h4>
                            </div>
                            <textarea id="specialNotes" class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter special notes..."></textarea>
                            <button id="addNoteBtn" class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">+ Add Note</button>
                        </div>

                        <!-- Additional Request -->
                        <div>
                            <h4 class="text-md font-medium text-gray-800 mb-3">Additional Request:</h4>
                            <div id="additionalRequests">
                                <p class="text-gray-500 text-sm">No additional requests</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button id="completeBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2" disabled>
                            <i class="fas fa-arrow-right"></i>
                            <span>COMPLETE & NEXT</span>
                        </button>
                        <button id="stallBtn" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2" disabled>
                            <i class="fas fa-pause"></i>
                            <span>MARK AS STALLED</span>
                        </button>
                        <button id="skipBtn" class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2" disabled>
                            <i class="fas fa-forward"></i>
                            <span>SKIP QUEUE</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column - Queue List and Statistics -->
                <div class="space-y-6">
                    <!-- Queue List Card -->
                    <div class="bg-white rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Queue List</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full" id="totalQueueCount">0</span>
                        </div>

                        <!-- Active Queue -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-medium text-gray-800">Active Queue</h4>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full" id="activeQueueCount">0</span>
                            </div>
                            <div id="activeQueueList" class="space-y-3">
                                <p class="text-gray-500 text-sm">No active queues</p>
                            </div>
                        </div>

                        <!-- Stalled Queue -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                    <h4 class="text-md font-medium text-gray-800">Stalled Queue</h4>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full" id="stalledQueueCount">0</span>
                            </div>
                            <div id="stalledQueueList" class="space-y-3">
                                <p class="text-gray-500 text-sm">No stalled queues</p>
                            </div>
                        </div>

                        <!-- Skipped Queue -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-times-circle text-red-500"></i>
                                    <h4 class="text-md font-medium text-gray-800">Skipped Queue</h4>
                                </div>
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full" id="skippedQueueCount">0</span>
                            </div>
                            <div id="skippedQueueList">
                                <p class="text-gray-500 text-sm">No skipped queues</p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Transaction Status Card -->
                    <div class="bg-white rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Transaction Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-clock text-blue-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900" id="avgServiceTime">--</p>
                                <p class="text-sm text-gray-600">Avg Service Time</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900" id="completedCount">0</p>
                                <p class="text-sm text-gray-600">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-pause-circle text-yellow-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900" id="stalledCount">0</p>
                                <p class="text-sm text-gray-600">Stalled</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900" id="cancelledCount">0</p>
                                <p class="text-sm text-gray-600">Cancelled</p>
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
        // Backend-ready JavaScript for Queue Management
        let currentQueue = null;
        let queueList = [];
        
        // Initialize the interface
        document.addEventListener('DOMContentLoaded', function() {
            loadQueueData();
            setupEventListeners();
        });
        
        // Load queue data from backend
        function loadQueueData() {
            // TODO: Replace with actual API call
            fetch('/api/queue/current')
                .then(response => response.json())
                .then(data => {
                    updateCurrentQueue(data.currentQueue);
                    updateQueueList(data.queueList);
                    updateStatistics(data.statistics);
                })
                .catch(error => {
                    console.log('No backend connection yet - using empty state');
                });
        }
        
        // Update current queue display
        function updateCurrentQueue(queue) {
            if (queue) {
                document.getElementById('currentQueueNumber').textContent = queue.number;
                document.getElementById('servingStatus').className = 'w-3 h-3 bg-green-500 rounded-full';
                document.getElementById('servingText').textContent = 'Currently Serving';
                
                // Update student information
                document.getElementById('studentName').textContent = queue.student.name;
                document.getElementById('studentId').textContent = queue.student.id;
                document.getElementById('studentCourse').textContent = queue.student.course;
                document.getElementById('studentYear').textContent = queue.student.year;
                
                // Update queue details
                updatePriorityType(queue.priority);
                document.getElementById('timeRequested').textContent = queue.timeRequested;
                document.getElementById('waitTime').textContent = queue.waitTime;
                
                // Update services and documents
                updateServiceRequests(queue.services);
                updateRequiredDocuments(queue.documents);
                
                // Enable action buttons
                enableActionButtons();
            } else {
                // No active queue
                document.getElementById('currentQueueNumber').textContent = '--';
                document.getElementById('servingStatus').className = 'w-3 h-3 bg-gray-400 rounded-full';
                document.getElementById('servingText').textContent = 'No Active Queue';
                
                // Clear student information
                document.getElementById('studentName').textContent = '--';
                document.getElementById('studentId').textContent = '--';
                document.getElementById('studentCourse').textContent = '--';
                document.getElementById('studentYear').textContent = '--';
                
                // Disable action buttons
                disableActionButtons();
            }
        }
        
        // Update priority type display
        function updatePriorityType(priority) {
            const priorityElement = document.getElementById('priorityType');
            if (priority === 'priority') {
                priorityElement.innerHTML = `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-star mr-1"></i>
                        Priority
                    </span>
                `;
            } else {
                priorityElement.innerHTML = `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        <i class="fas fa-circle mr-1"></i>
                        Regular
                    </span>
                `;
            }
        }
        
        // Update service requests
        function updateServiceRequests(services) {
            const container = document.getElementById('serviceRequests');
            if (services && services.length > 0) {
                container.innerHTML = services.map(service => `
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${service.completed ? 'checked' : ''}>
                        <span class="text-gray-700">${service.name}</span>
                    </label>
                `).join('');
            } else {
                container.innerHTML = '<p class="text-gray-500 text-sm">No service requests</p>';
            }
        }
        
        // Update required documents
        function updateRequiredDocuments(documents) {
            const container = document.getElementById('requiredDocuments');
            const statusElement = document.getElementById('documentStatus');
            
            if (documents && documents.length > 0) {
                const verifiedCount = documents.filter(doc => doc.verified).length;
                const totalCount = documents.length;
                
                container.innerHTML = documents.map(doc => `
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" ${doc.verified ? 'checked' : ''}>
                        <span class="text-gray-700">${doc.name}</span>
                    </label>
                `).join('');
                
                statusElement.textContent = `${verifiedCount} of ${totalCount} documents verified`;
                statusElement.className = verifiedCount === totalCount ? 'text-green-600 text-sm mt-2' : 'text-yellow-600 text-sm mt-2';
            } else {
                container.innerHTML = '<p class="text-gray-500 text-sm">No documents required</p>';
                statusElement.textContent = '0 of 0 documents verified';
                statusElement.className = 'text-gray-500 text-sm mt-2';
            }
        }
        
        // Update queue list
        function updateQueueList(queues) {
            const activeContainer = document.getElementById('activeQueueList');
            const stalledContainer = document.getElementById('stalledQueueList');
            const skippedContainer = document.getElementById('skippedQueueList');
            
            const activeQueues = queues.filter(q => q.status === 'active');
            const stalledQueues = queues.filter(q => q.status === 'stalled');
            const skippedQueues = queues.filter(q => q.status === 'skipped');
            
            // Update counts
            document.getElementById('totalQueueCount').textContent = queues.length;
            document.getElementById('activeQueueCount').textContent = activeQueues.length;
            document.getElementById('stalledQueueCount').textContent = stalledQueues.length;
            document.getElementById('skippedQueueCount').textContent = skippedQueues.length;
            
            // Update active queue list
            if (activeQueues.length > 0) {
                activeContainer.innerHTML = activeQueues.map(queue => `
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                ${queue.priority === 'priority' ? '<div class="flex items-center space-x-2"><i class="fas fa-star text-yellow-500 text-sm"></i>' : ''}
                                <p class="font-medium text-gray-900">${queue.number}</p>
                                ${queue.priority === 'priority' ? '</div>' : ''}
                                <p class="text-sm text-gray-600">${queue.student.name}</p>
                                <p class="text-sm text-gray-500">${queue.service}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-1 text-blue-600">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-xs">Waiting in queue</span>
                                </div>
                                <div class="flex items-center space-x-1 text-gray-500 mt-1">
                                    <i class="fas fa-clock text-xs"></i>
                                    <span class="text-xs">${queue.waitTime}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                activeContainer.innerHTML = '<p class="text-gray-500 text-sm">No active queues</p>';
            }
            
            // Update stalled queue list
            if (stalledQueues.length > 0) {
                stalledContainer.innerHTML = stalledQueues.map(queue => `
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">${queue.number}</p>
                                <p class="text-sm text-gray-600">${queue.student.name}</p>
                                <p class="text-sm text-gray-500">${queue.service}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-1 text-yellow-600">
                                    <i class="fas fa-file-alt text-xs"></i>
                                    <span class="text-xs">${queue.stallReason}</span>
                                </div>
                                <button class="mt-1 bg-blue-900 text-white text-xs px-2 py-1 rounded" onclick="resumeQueue('${queue.id}')">► Resume</button>
                                <div class="flex items-center space-x-1 text-gray-500 mt-1">
                                    <i class="fas fa-clock text-xs"></i>
                                    <span class="text-xs">${queue.waitTime}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                stalledContainer.innerHTML = '<p class="text-gray-500 text-sm">No stalled queues</p>';
            }
            
            // Update skipped queue list
            if (skippedQueues.length > 0) {
                skippedContainer.innerHTML = skippedQueues.map(queue => `
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">${queue.number}</p>
                                <p class="text-sm text-gray-600">${queue.student.name}</p>
                                <p class="text-sm text-gray-500">${queue.service}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-1 text-red-600">
                                    <i class="fas fa-times text-xs"></i>
                                    <span class="text-xs">Skipped</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                skippedContainer.innerHTML = '<p class="text-gray-500 text-sm">No skipped queues</p>';
            }
        }
        
        // Update statistics
        function updateStatistics(stats) {
            document.getElementById('avgServiceTime').textContent = stats.avgServiceTime || '--';
            document.getElementById('completedCount').textContent = stats.completed || 0;
            document.getElementById('stalledCount').textContent = stats.stalled || 0;
            document.getElementById('cancelledCount').textContent = stats.cancelled || 0;
        }
        
        // Enable/disable action buttons
        function enableActionButtons() {
            document.getElementById('completeBtn').disabled = false;
            document.getElementById('stallBtn').disabled = false;
            document.getElementById('skipBtn').disabled = false;
        }
        
        function disableActionButtons() {
            document.getElementById('completeBtn').disabled = true;
            document.getElementById('stallBtn').disabled = true;
            document.getElementById('skipBtn').disabled = true;
        }
        
        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('completeBtn').addEventListener('click', completeQueue);
            document.getElementById('stallBtn').addEventListener('click', stallQueue);
            document.getElementById('skipBtn').addEventListener('click', skipQueue);
            document.getElementById('addNoteBtn').addEventListener('click', addNote);
        }
        
        // Action functions (to be connected to backend)
        function completeQueue() {
            // TODO: Send API request to complete current queue
            console.log('Complete queue action');
        }
        
        function stallQueue() {
            // TODO: Send API request to stall current queue
            console.log('Stall queue action');
        }
        
        function skipQueue() {
            // TODO: Send API request to skip current queue
            console.log('Skip queue action');
        }
        
        function resumeQueue(queueId) {
            // TODO: Send API request to resume stalled queue
            console.log('Resume queue:', queueId);
        }
        
        function addNote() {
            // TODO: Add note functionality
            console.log('Add note action');
        }
        
        // Auto-refresh queue data every 30 seconds
        setInterval(loadQueueData, 30000);
    </script>
</body>
</html>