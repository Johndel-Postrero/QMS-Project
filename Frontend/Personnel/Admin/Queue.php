<?php
// Include Admin Header
include 'Header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Queue Management - SeQueueR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .nav-tab {
            transition: all 0.2s ease-in-out;
        }
        .nav-tab:hover {
            background-color: #f3f4f6;
        }
        .nav-tab.active {
            background-color: #dbeafe;
            border-bottom: 2px solid #1e40af;
        }
        .nav-tab.active .nav-icon,
        .nav-tab.active .nav-text {
            color: #1e40af;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Main Content -->
    <main class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-6">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Left Column - Currently Serving and Student Details -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Currently Serving Card -->
                    <div class="bg-white border-4 border-yellow-400 rounded-lg p-6 text-center">
                        <div class="text-6xl font-bold text-yellow-400 mb-2" id="currentQueueNumber">--</div>
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-3 h-3 bg-gray-400 rounded-full" id="servingStatus"></div>
                            <span class="text-gray-900 font-semibold" id="servingText">No Active Queue</span>
                        </div>
                    </div>
                    
                    <!-- Student Details and Services Panel -->
                    <div class="space-y-6 border-2 border-gray-300 rounded-lg p-4">
                    <!-- Student Information & Queue Details Card -->
                    <div class="bg-white rounded-lg p-6 border-2 border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information & Queue Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student Information -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-3">Student Information:</h4>
                                <div class="space-y-2">
                                    <div>
                                        <label class="text-sm text-gray-600">Full Name:</label>
                                        <p class="text-gray-900 font-medium" id="studentName">--</p>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-600">Student ID:</label>
                                        <p class="text-gray-900 font-medium" id="studentId">--</p>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-600">Course:</label>
                                        <p class="text-gray-900 font-medium" id="studentCourse">--</p>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-600">Year Level:</label>
                                        <p class="text-gray-900 font-medium" id="studentYearLevel">--</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Queue Details -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-3">Queue Details:</h4>
                                <div class="space-y-2">
                                    <div>
                                        <label class="text-sm text-gray-600">Priority Type:</label>
                                        <div id="priorityType">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-star text-gray-500 mr-1"></i>
                                                Regular
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-600">Time Requested:</label>
                                        <p class="text-gray-900 font-medium" id="timeRequested">--</p>
                                    </div>
                                    <div>
                                        <label class="text-sm text-gray-600">Total Wait Time:</label>
                                        <p class="text-gray-900 font-medium" id="totalWaitTime">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requested Services Card -->
                    <div class="bg-white rounded-lg p-6 border-2 border-gray-300">
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
                            <div class="flex items-center space-x-2 mt-2" id="documentStatus">
                                <i class="fas fa-check-circle text-gray-500"></i>
                                <span class="text-gray-500 text-sm">0 of 0 documents verified</span>
                            </div>
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
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button id="completeBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2">
                            <span>COMPLETE & NEXT</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button id="stallBtn" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg">
                            MARK AS STALLED
                        </button>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button id="skipBtn" class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-arrow-right"></i>
                            <i class="fas fa-arrow-right"></i>
                            <span>SKIP QUEUE</span>
                        </button>
                        <button id="pauseBtn" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-pause"></i>
                            <span>PAUSE QUEUE</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column - Queue List and Statistics -->
                <div class="lg:col-span-2 flex flex-col border-2 border-gray-300 rounded-lg p-4 h-full">
                    <!-- Queue List Card -->
                    <div class="bg-white rounded-lg p-6 border-2 border-gray-300 flex-1 mb-6 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Queue List</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full" id="totalQueueCount">0</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Active Queue -->
                            <div class="border border-gray-300 rounded-lg">
                                <div class="flex items-center justify-between p-3 cursor-pointer bg-blue-50 rounded-t-lg" onclick="toggleSection('activeQueue')">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-question-circle text-blue-600"></i>
                                        <h4 class="text-sm font-medium text-gray-800">Active Queue</h4>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-blue-600 text-white text-xs font-medium px-2 py-1 rounded-full" id="activeQueueCount">0</span>
                                        <i class="fas fa-chevron-up text-gray-400 transition-transform" id="activeQueueIcon"></i>
                                    </div>
                                </div>
                                <div id="activeQueueList" class="p-3 space-y-3">
                                    <p class="text-gray-500 text-sm">No active queues</p>
                                </div>
                            </div>

                            <!-- Stalled Queue -->
                            <div class="border border-gray-300 rounded-lg">
                                <div class="flex items-center justify-between p-3 cursor-pointer bg-yellow-50 rounded-t-lg" onclick="toggleSection('stalledQueue')">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        <h4 class="text-sm font-medium text-gray-800">Stalled Queue</h4>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-yellow-500 text-white text-xs font-medium px-2 py-1 rounded-full" id="stalledQueueCount">0</span>
                                        <i class="fas fa-chevron-up text-gray-400 transition-transform" id="stalledQueueIcon"></i>
                                    </div>
                                </div>
                                <div id="stalledQueueList" class="p-3 space-y-3">
                                    <p class="text-gray-500 text-sm">No stalled queues</p>
                                </div>
                            </div>

                            <!-- Skipped Queue -->
                            <div class="border border-gray-300 rounded-lg">
                                <div class="flex items-center justify-between p-3 cursor-pointer bg-red-50 rounded-t-lg" onclick="toggleSection('skippedQueue')">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-times-circle text-red-500"></i>
                                        <h4 class="text-sm font-medium text-gray-800">Skipped Queue</h4>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full" id="skippedQueueCount">0</span>
                                        <i class="fas fa-chevron-up text-gray-400 transition-transform" id="skippedQueueIcon"></i>
                                    </div>
                                </div>
                                <div id="skippedQueueList" class="p-3 space-y-3">
                                    <p class="text-gray-500 text-sm">No skipped queues</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Transaction Status Card -->
                    <div class="bg-white rounded-lg p-6 border-2 border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Transaction Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-clock text-blue-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900" id="avgServiceTime">--</p>
                                <p class="text-sm text-gray-600">Avg Service Time</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-check text-green-600 text-lg"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-900" id="completedCount">0</p>
                                <p class="text-sm text-gray-600">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-pause text-yellow-600 text-lg"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-900" id="stalledCount">0</p>
                                <p class="text-sm text-gray-600">Stalled</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-times text-red-600 text-lg"></i>
                                </div>
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
        // Backend-ready JavaScript for Admin Queue Management
        let currentQueue = null;
        let queueData = {
            active: [],
            stalled: [],
            skipped: []
        };
        let transactionStats = {
            avgServiceTime: 0,
            completed: 0,
            stalled: 0,
            cancelled: 0
        };
        
        // Initialize the interface
        document.addEventListener('DOMContentLoaded', function() {
            loadCurrentQueue();
            loadQueueData();
            loadTransactionStats();
            setupEventListeners();
            
            // Auto-refresh every 30 seconds
            setInterval(loadCurrentQueue, 30000);
            setInterval(loadQueueData, 30000);
            setInterval(loadTransactionStats, 60000);
        });
        
        // Load current serving queue from backend
        function loadCurrentQueue() {
            // TODO: Replace with actual API call
            fetch('/api/admin/queue/current')
                .then(response => response.json())
                .then(data => {
                    currentQueue = data;
                    updateCurrentQueueDisplay();
                })
                .catch(error => {
                    console.log('No backend connection yet - using empty state');
                    currentQueue = null;
                    updateCurrentQueueDisplay();
                });
        }
        
        // Load queue data from backend
        function loadQueueData() {
            // TODO: Replace with actual API call
            fetch('/api/admin/queue/list')
                .then(response => response.json())
                .then(data => {
                    queueData = data;
                    updateQueueDisplay();
                })
                .catch(error => {
                    console.log('No backend connection yet - using empty state');
                    queueData = {
                        active: [],
                        stalled: [],
                        skipped: []
                    };
                    updateQueueDisplay();
                });
        }
        
        // Load transaction statistics from backend
        function loadTransactionStats() {
            // TODO: Replace with actual API call
            fetch('/api/admin/queue/statistics')
                .then(response => response.json())
                .then(data => {
                    transactionStats = data;
                    updateTransactionStats();
                })
                .catch(error => {
                    console.log('No backend connection yet - using empty state');
                    transactionStats = {
                        avgServiceTime: 0,
                        completed: 0,
                        stalled: 0,
                        cancelled: 0
                    };
                    updateTransactionStats();
                });
        }
        
        // Update current queue display
        function updateCurrentQueueDisplay() {
            const queueNumberEl = document.getElementById('currentQueueNumber');
            const servingStatusEl = document.getElementById('servingStatus');
            const servingTextEl = document.getElementById('servingText');
            
            if (currentQueue) {
                queueNumberEl.textContent = currentQueue.queueNumber;
                servingStatusEl.className = 'w-3 h-3 bg-green-500 rounded-full';
                servingTextEl.textContent = 'Currently Serving';
                servingTextEl.className = 'text-green-600 font-semibold';
                
                // Update student information
                updateStudentInformation();
                updateServiceRequests();
                updateRequiredDocuments();
            } else {
                queueNumberEl.textContent = '--';
                servingStatusEl.className = 'w-3 h-3 bg-gray-400 rounded-full';
                servingTextEl.textContent = 'No Active Queue';
                servingTextEl.className = 'text-gray-900 font-semibold';
                
                // Clear student information
                clearStudentInformation();
            }
        }
        
        // Update student information
        function updateStudentInformation() {
            if (!currentQueue) return;
            
            document.getElementById('studentName').textContent = currentQueue.studentName || '--';
            document.getElementById('studentId').textContent = currentQueue.studentId || '--';
            document.getElementById('studentCourse').textContent = currentQueue.studentCourse || '--';
            document.getElementById('studentYearLevel').textContent = currentQueue.studentYearLevel || '--';
            document.getElementById('timeRequested').textContent = currentQueue.timeRequested || '--';
            document.getElementById('totalWaitTime').textContent = currentQueue.totalWaitTime || '--';
            
            // Update priority type
            const priorityEl = document.getElementById('priorityType');
            if (currentQueue.priority === 'priority') {
                priorityEl.innerHTML = `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        Priority
                    </span>
                `;
            } else {
                priorityEl.innerHTML = `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-star text-gray-500 mr-1"></i>
                        Regular
                    </span>
                `;
            }
        }
        
        // Clear student information
        function clearStudentInformation() {
            document.getElementById('studentName').textContent = '--';
            document.getElementById('studentId').textContent = '--';
            document.getElementById('studentCourse').textContent = '--';
            document.getElementById('studentYearLevel').textContent = '--';
            document.getElementById('timeRequested').textContent = '--';
            document.getElementById('totalWaitTime').textContent = '--';
        }
        
        // Update service requests
        function updateServiceRequests() {
            const container = document.getElementById('serviceRequests');
            
            if (!currentQueue || !currentQueue.serviceRequests || currentQueue.serviceRequests.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No service requests</p>';
                return;
            }
            
            container.innerHTML = currentQueue.serviceRequests.map(service => `
                <label class="flex items-center space-x-3">
                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked disabled>
                    <span class="text-gray-500">${service.name}</span>
                </label>
            `).join('');
        }
        
        // Update required documents
        function updateRequiredDocuments() {
            const container = document.getElementById('requiredDocuments');
            const statusEl = document.getElementById('documentStatus');
            
            if (!currentQueue || !currentQueue.requiredDocuments || currentQueue.requiredDocuments.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No documents required</p>';
                statusEl.innerHTML = `
                    <i class="fas fa-check-circle text-gray-500"></i>
                    <span class="text-gray-500 text-sm">0 of 0 documents verified</span>
                `;
                return;
            }
            
            const verifiedCount = currentQueue.requiredDocuments.filter(doc => doc.verified).length;
            const totalCount = currentQueue.requiredDocuments.length;
            
            container.innerHTML = currentQueue.requiredDocuments.map(doc => `
                <label class="flex items-center space-x-3">
                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${doc.verified ? 'checked' : ''}>
                    <span class="text-gray-700">${doc.name}</span>
                </label>
            `).join('');
            
            statusEl.innerHTML = `
                <i class="fas fa-check-circle ${verifiedCount === totalCount ? 'text-green-600' : 'text-gray-500'}"></i>
                <span class="${verifiedCount === totalCount ? 'text-green-600' : 'text-gray-500'} text-sm font-medium">${verifiedCount} of ${totalCount} documents verified</span>
            `;
        }
        
        // Update queue display
        function updateQueueDisplay() {
            // Update counts
            document.getElementById('totalQueueCount').textContent = queueData.active.length + queueData.stalled.length + queueData.skipped.length;
            document.getElementById('activeQueueCount').textContent = queueData.active.length;
            document.getElementById('stalledQueueCount').textContent = queueData.stalled.length;
            document.getElementById('skippedQueueCount').textContent = queueData.skipped.length;
            
            // Update active queue list
            updateQueueList('activeQueueList', queueData.active, 'active');
            
            // Update stalled queue list
            updateQueueList('stalledQueueList', queueData.stalled, 'stalled');
            
            // Update skipped queue list
            updateQueueList('skippedQueueList', queueData.skipped, 'skipped');
        }
        
        // Update individual queue list
        function updateQueueList(containerId, queueList, type) {
            const container = document.getElementById(containerId);
            
            if (queueList.length === 0) {
                container.innerHTML = `<p class="text-gray-500 text-sm">No ${type} queues</p>`;
                return;
            }
            
            container.innerHTML = queueList.map(queue => {
                if (type === 'active') {
                    return `
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        ${queue.priority === 'priority' ? '<i class="fas fa-star text-yellow-500 text-sm"></i>' : ''}
                                        <p class="font-medium ${queue.priority === 'priority' ? 'text-yellow-600' : 'text-blue-600'}">${queue.queueNumber}</p>
                                    </div>
                                    <p class="text-sm text-gray-500">${queue.studentName}</p>
                                    <p class="text-sm text-gray-500">${queue.serviceType}</p>
                                    <div class="flex items-center space-x-1 mt-1">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        <span class="text-xs text-blue-600">Waiting in queue</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg px-2 py-1 flex items-center space-x-1">
                                    <i class="fas fa-clock text-xs text-gray-500"></i>
                                    <span class="text-xs text-gray-700">${queue.waitTime}</span>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (type === 'stalled') {
                    return `
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-blue-600">${queue.queueNumber}</p>
                                    <p class="text-sm text-gray-500">${queue.studentName}</p>
                                    <p class="text-sm text-gray-500">${queue.serviceType}</p>
                                    <button class="bg-yellow-500 text-gray-900 text-xs px-3 py-1 rounded mt-1 font-semibold">Missing Documents</button>
                                    <button class="bg-blue-900 text-white text-xs px-3 py-1 rounded mt-1 ml-2" onclick="resumeQueue('${queue.id}')">► Resume</button>
                                </div>
                                <div class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded-full">
                                    ${queue.waitTime}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    return `
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-blue-600">${queue.queueNumber}</p>
                                    <p class="text-sm text-gray-500">${queue.studentName}</p>
                                    <p class="text-sm text-gray-500">${queue.serviceType}</p>
                                </div>
                                <div class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                    ${queue.waitTime}
                                </div>
                            </div>
                        </div>
                    `;
                }
            }).join('');
        }
        
        // Update transaction statistics
        function updateTransactionStats() {
            document.getElementById('avgServiceTime').textContent = transactionStats.avgServiceTime > 0 ? `${transactionStats.avgServiceTime} min` : '--';
            document.getElementById('completedCount').textContent = transactionStats.completed;
            document.getElementById('stalledCount').textContent = transactionStats.stalled;
            document.getElementById('cancelledCount').textContent = transactionStats.cancelled;
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // Action buttons
            document.getElementById('completeBtn').addEventListener('click', completeQueue);
            document.getElementById('stallBtn').addEventListener('click', stallQueue);
            document.getElementById('skipBtn').addEventListener('click', skipQueue);
            document.getElementById('pauseBtn').addEventListener('click', pauseQueue);
            
            // Add note button
            document.getElementById('addNoteBtn').addEventListener('click', addNote);
        }
        
        // Toggle section visibility
        function toggleSection(sectionName) {
            const list = document.getElementById(sectionName + 'List');
            const icon = document.getElementById(sectionName + 'Icon');
            
            if (list.style.display === 'none') {
                list.style.display = 'block';
                icon.className = 'fas fa-chevron-up text-gray-400 transition-transform';
            } else {
                list.style.display = 'none';
                icon.className = 'fas fa-chevron-down text-gray-400 transition-transform';
            }
        }
        
        // Action functions
        function completeQueue() {
            if (!currentQueue) {
                alert('No active queue to complete');
                return;
            }
            
            // TODO: Implement complete queue API call
            console.log('Complete queue - Backend not implemented yet');
        }
        
        function stallQueue() {
            if (!currentQueue) {
                alert('No active queue to stall');
                return;
            }
            
            // TODO: Implement stall queue API call
            console.log('Stall queue - Backend not implemented yet');
        }
        
        function skipQueue() {
            if (!currentQueue) {
                alert('No active queue to skip');
                return;
            }
            
            // TODO: Implement skip queue API call
            console.log('Skip queue - Backend not implemented yet');
        }
        
        function pauseQueue() {
            // TODO: Implement pause queue API call
            console.log('Pause queue - Backend not implemented yet');
        }
        
        function resumeQueue(queueId) {
            // TODO: Implement resume queue API call
            console.log('Resume queue - Backend not implemented yet');
        }
        
        function addNote() {
            const specialNotes = document.getElementById('specialNotes');
            const note = prompt('Enter your note:');
            if (note) {
                specialNotes.value += (specialNotes.value ? '\n' : '') + note;
            }
        }
    </script>
</body>
</html>