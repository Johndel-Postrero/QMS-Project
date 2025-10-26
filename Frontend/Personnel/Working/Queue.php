<?php
// Queue Management Dashboard for SeQueueR
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
    <!-- Include Working Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="bg-gray-100 min-h-screen">
        <div class="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Left Panel - Current Queue Details -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Currently Serving Card -->
                    <div class="bg-white border-2 border-yellow-600 rounded-lg p-8 text-center shadow-sm">
                        <div class="text-6xl font-bold text-yellow-600 mb-3" id="currentQueueNumber">--</div>
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-green-600 font-medium">Currently Serving</span>
                        </div>
                    </div>

                    <!-- Student Information & Queue Details Card -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Student Information -->
                            <div>
                                <h3 class="text-lg font-bold text-blue-800 mb-6 pb-2 border-b border-gray-200">Student Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Full Name</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentName">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Student ID</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentId">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Course</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentCourse">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Year Level</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentYear">--</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Queue Details -->
                            <div>
                                <h3 class="text-lg font-bold text-blue-800 mb-6 pb-2 border-b border-gray-200">Queue Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-2">Priority Type</span>
                                        <div id="priorityType">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-200 text-gray-800">
                                                <i class="fas fa-star mr-2 text-black"></i>
                                                Priority
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Time Requested</span>
                                        <p class="font-bold text-gray-800 text-base" id="timeRequested">--</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Total Wait Time</span>
                                        <p class="font-bold text-gray-800 text-base" id="waitTime">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requested Services -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-blue-800 mb-6">Requested Services</h3>
                        
                        <!-- Services will be populated dynamically -->
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                            <p class="text-lg font-medium">No services requested</p>
                            <p class="text-sm">Services will appear here when a student requests them</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <button class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-arrow-right"></i>
                            <span>COMPLETE & NEXT</span>
                        </button>
                        <button class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-pause"></i>
                            <span>MARK AS STALLED</span>
                        </button>
                        <button class="flex-1 bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-forward"></i>
                            <span>SKIP QUEUE</span>
                        </button>
                    </div>
                </div>

                <!-- Right Panel - Queue Lists -->
                <div class="lg:col-span-3 space-y-6">
                     <!-- Queue List -->
                     <div class="bg-white border border-gray-200 rounded-lg">
                         <!-- Header -->
                         <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                             <h3 class="text-lg font-bold text-blue-900">Queue List</h3>
                             <div class="bg-blue-900 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-semibold">0</div>
                         </div>
                         
                         <!-- Active Queue -->
                         <div class="border-b border-gray-200">
                             <button class="group flex justify-between items-center w-full px-5 py-3 bg-blue-50 focus:outline-none" onclick="toggleQueueSection('activeQueue')">
                                 <div class="flex items-center space-x-2">
                                     <i class="fas fa-question-circle text-blue-600 w-4 h-4"></i>
                                     <h4 class="font-semibold text-blue-900 text-sm">Active Queue</h4>
                                     <div class="bg-blue-900 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">0</div>
                                 </div>
                                 <i class="fas fa-chevron-down text-blue-900 w-4 h-4 transition-transform" id="activeQueue-arrow"></i>
                             </button>
                             <!-- Active queue items -->
                             <div id="activeQueue-content" class="divide-y divide-gray-200">
                                 <!-- Queue items will be populated dynamically -->
                                 <div class="px-5 py-8 text-center text-gray-500">
                                     <i class="fas fa-users text-3xl mb-2"></i>
                                     <p>No active queue items</p>
                                 </div>
                             </div>
                         </div>

                        <!-- Stalled Queue -->
                        <div class="border-b border-gray-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-yellow-50 focus:outline-none" onclick="toggleQueueSection('stalledQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 w-4 h-4"></i>
                                    <h4 class="font-semibold text-yellow-600 text-sm">Stalled Queue</h4>
                                    <div class="bg-yellow-400 text-yellow-900 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">0</div>
                                </div>
                                <i class="fas fa-chevron-down text-yellow-600 w-4 h-4 transition-transform" id="stalledQueue-arrow"></i>
                            </button>
                            <!-- Stalled items -->
                            <div id="stalledQueue-content" class="divide-y divide-gray-200">
                                <!-- Stalled items will be populated dynamically -->
                                <div class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
                                    <p>No stalled queue items</p>
                                </div>
                            </div>
                        </div>

                        <!-- Skipped Queue -->
                        <div class="border-b border-red-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-red-50 hover:bg-red-100 focus:outline-none" onclick="toggleQueueSection('skippedQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-times-circle text-red-600 w-4 h-4"></i>
                                    <h4 class="font-semibold text-red-600 text-sm">Skipped Queue</h4>
                                    <div class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">0</div>
                                </div>
                                <i class="fas fa-chevron-down text-red-600 w-4 h-4 transition-transform" id="skippedQueue-arrow"></i>
                            </button>
                            <div id="skippedQueue-content" class="divide-y divide-gray-200">
                                <!-- Skipped items will be populated dynamically -->
                                <div class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-times-circle text-3xl mb-2"></i>
                                    <p>No skipped queue items</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Transaction Status -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Transaction Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-clock text-blue-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">--</p>
                                <p class="text-sm text-gray-600">Avg Service Time</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                                <p class="text-sm text-gray-600">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-pause-circle text-yellow-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                                <p class="text-sm text-gray-600">Stalled</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">0</p>
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
                    console.log('No backend connection yet - no data available');
                    // No dummy data - empty state
                    loadEmptyData();
                });
        }
        
        // Load empty data when no backend connection
        function loadEmptyData() {
            const emptyData = {
                currentQueue: null,
                queueList: [],
                statistics: {
                    avgServiceTime: "--",
                    completed: 0,
                    stalled: 0,
                    cancelled: 0
                }
            };
            
            updateCurrentQueue(emptyData.currentQueue);
            updateQueueList(emptyData.queueList);
            updateStatistics(emptyData.statistics);
        }
        
        // Update current queue display
        function updateCurrentQueue(queue) {
            if (queue) {
                document.getElementById('currentQueueNumber').textContent = queue.number;
                document.getElementById('studentName').textContent = queue.student.name || '--';
                document.getElementById('studentId').textContent = queue.student.id || '--';
                document.getElementById('studentCourse').textContent = queue.student.course || '--';
                document.getElementById('studentYear').textContent = queue.student.year || '--';
                document.getElementById('timeRequested').textContent = queue.timeRequested || '--';
                document.getElementById('waitTime').textContent = queue.waitTime || '--';
            } else {
                // Show empty state
                document.getElementById('currentQueueNumber').textContent = '--';
                document.getElementById('studentName').textContent = '--';
                document.getElementById('studentId').textContent = '--';
                document.getElementById('studentCourse').textContent = '--';
                document.getElementById('studentYear').textContent = '--';
                document.getElementById('timeRequested').textContent = '--';
                document.getElementById('waitTime').textContent = '--';
            }
        }
        
        // Update queue list
        function updateQueueList(queues) {
            // Update counts - use more specific selectors to avoid targeting buttons
            const totalCount = document.querySelector('.bg-blue-900.text-white.rounded-full');
            const activeCount = document.querySelector('.bg-blue-900.text-white.rounded-full');
            const stalledCount = document.querySelector('.bg-yellow-400.text-yellow-900.rounded-full');
            const skippedCount = document.querySelector('.bg-red-500.text-white.rounded-full');
            
            if (totalCount) totalCount.textContent = queues.length;
            
            // Count by status
            const activeQueues = queues.filter(q => q.status === 'active').length;
            const stalledQueues = queues.filter(q => q.status === 'stalled').length;
            const skippedQueues = queues.filter(q => q.status === 'skipped').length;
            
            if (activeCount) activeCount.textContent = activeQueues;
            if (stalledCount) stalledCount.textContent = stalledQueues;
            if (skippedCount) skippedCount.textContent = skippedQueues;
        }
        
        // Update statistics
        function updateStatistics(stats) {
            // Use more specific selectors to avoid targeting buttons
            const avgTimeElement = document.querySelector('.bg-gray-50 .text-2xl.font-bold.text-gray-900');
            const completedElement = document.querySelectorAll('.bg-gray-50 .text-2xl.font-bold.text-gray-900')[0];
            const stalledElement = document.querySelectorAll('.bg-gray-50 .text-2xl.font-bold.text-gray-900')[1];
            const cancelledElement = document.querySelectorAll('.bg-gray-50 .text-2xl.font-bold.text-gray-900')[2];
            
            if (avgTimeElement) avgTimeElement.textContent = stats.avgServiceTime || '--';
            if (completedElement) completedElement.textContent = stats.completed || 0;
            if (stalledElement) stalledElement.textContent = stats.stalled || 0;
            if (cancelledElement) cancelledElement.textContent = stats.cancelled || 0;
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // Add event listeners for buttons
            document.querySelector('.bg-green-600').addEventListener('click', completeQueue);
            document.querySelector('.bg-yellow-500').addEventListener('click', stallQueue);
            document.querySelector('.bg-blue-900').addEventListener('click', skipQueue);
        }
        
        // Action functions
        function completeQueue() {
            console.log('Complete queue action');
        }
        
        function stallQueue() {
            console.log('Stall queue action');
        }
        
        function skipQueue() {
            console.log('Skip queue action');
        }
        
        // Toggle service details
        function toggleServiceDetails(serviceId) {
            const details = document.getElementById(serviceId + '-details');
            const arrow = document.getElementById(serviceId + '-arrow');
            
            if (details.classList.contains('hidden')) {
                // Show details
                details.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                // Hide details
                details.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Toggle all documents when service checkbox is clicked
        function toggleAllDocuments(serviceId) {
            const serviceCheckbox = document.querySelector(`input[onclick*="${serviceId}"]`);
            const documentCheckboxes = document.querySelectorAll(`#${serviceId}-details input[type="checkbox"]`);
            
            const isChecked = serviceCheckbox.checked;
            
            // Update all document checkboxes
            documentCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                if (isChecked) {
                    checkbox.classList.remove('text-gray-400');
                    checkbox.classList.add('text-green-600');
                } else {
                    checkbox.classList.remove('text-green-600');
                    checkbox.classList.add('text-gray-400');
                }
            });
            
            // Update verification status
            updateVerificationStatus(serviceId);
        }
        
        // Update service checkbox when individual documents are checked
        function updateServiceCheckbox(serviceId) {
            const documentCheckboxes = document.querySelectorAll(`#${serviceId}-details input[type="checkbox"]`);
            const serviceCheckbox = document.querySelector(`input[onclick*="${serviceId}"]`);
            
            const checkedCount = Array.from(documentCheckboxes).filter(cb => cb.checked).length;
            const totalCount = documentCheckboxes.length;
            
            // Update service checkbox based on document status
            if (checkedCount === totalCount) {
                serviceCheckbox.checked = true;
                serviceCheckbox.classList.remove('text-gray-400');
                serviceCheckbox.classList.add('text-green-600');
            } else {
                serviceCheckbox.checked = false;
                serviceCheckbox.classList.remove('text-green-600');
                serviceCheckbox.classList.add('text-gray-400');
            }
            
            // Update verification status
            updateVerificationStatus(serviceId);
        }
        
        // Update verification status display
        function updateVerificationStatus(serviceId) {
            const documentCheckboxes = document.querySelectorAll(`#${serviceId}-details input[type="checkbox"]`);
            const checkedCount = Array.from(documentCheckboxes).filter(cb => cb.checked).length;
            const totalCount = documentCheckboxes.length;
            
            // Find the verification status element
            const statusElement = document.querySelector(`#${serviceId}-details .inline-flex.items-center`);
            if (statusElement) {
                if (checkedCount === totalCount) {
                    statusElement.className = 'inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    statusElement.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + checkedCount + ' of ' + totalCount + ' documents verified';
                } else if (checkedCount > 0) {
                    statusElement.className = 'inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800';
                    statusElement.innerHTML = '<i class="fas fa-clock mr-2"></i>' + checkedCount + ' of ' + totalCount + ' documents verified';
                } else {
                    statusElement.className = 'inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800';
                    statusElement.innerHTML = '<i class="fas fa-times-circle mr-2"></i>' + checkedCount + ' of ' + totalCount + ' documents verified';
                }
            }
        }
        
        // Add special note as a card in Service Notes section
        function addSpecialNote(serviceId) {
            const input = document.getElementById(serviceId + '-specialInput');
            const container = document.getElementById(serviceId + '-serviceNotes');
            
            if (input.value.trim() === '') {
                alert('Please enter a special note before adding.');
                return;
            }
            
            // Create special note card
            const noteCard = document.createElement('div');
            noteCard.className = 'bg-gray-100 border border-gray-200 rounded-lg p-3 flex items-start justify-between';
            noteCard.innerHTML = `
                <p class="text-gray-800 text-sm">${input.value}</p>
                <button onclick="removeServiceNote(this)" class="text-gray-500 hover:text-gray-700 ml-2">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add to container
            container.appendChild(noteCard);
            
            // Clear input
            input.value = '';
        }
        
        // Remove service note card (works for both regular and special notes)
        function removeServiceNote(button) {
            button.parentElement.remove();
        }
        
        // Toggle queue section (Active, Stalled, Skipped)
        function toggleQueueSection(sectionId) {
            const content = document.getElementById(sectionId + '-content');
            const arrow = document.getElementById(sectionId + '-arrow');
            
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                // Hide content
                content.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Auto-refresh queue data every 30 seconds
        setInterval(loadQueueData, 30000);
    </script>
</body>
</html>