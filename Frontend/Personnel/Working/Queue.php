<?php
// Queue Management Dashboard for SeQueueR
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Management - SeQueueR</title>
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
    <!-- Include Working Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="bg-gray-100 min-h-screen">
        <div class="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Left Panel - Current Queue Details -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Currently Serving Card -->
                    <div id="currentServingCard" class="bg-white border-2 rounded-lg p-8 text-center shadow-sm" style="border-color:#DAA520;">
                        <div class="text-6xl font-bold mb-3" id="currentQueueNumber" style="color:#DAA520;">--</div>
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
                        
                        <!-- Tabs header -->
                        <div id="servicesTabs" class="flex flex-wrap gap-2 mb-4" role="tablist"></div>
                        <!-- Tabs panels -->
                        <div id="servicesPanels"></div>
                        
                        <!-- Empty state (shown if no services) -->
                        <div id="servicesEmpty" class="text-center py-12 text-gray-500">
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
                             <div id="queueListTotalCount" class="bg-blue-900 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-semibold">0</div>
                         </div>
                         
                         <!-- Active Queue -->
                         <div class="border-b border-gray-200">
                             <button class="group flex justify-between items-center w-full px-5 py-3 bg-blue-50 focus:outline-none" onclick="toggleQueueSection('activeQueue')">
                                 <div class="flex items-center space-x-2">
                                     <i class="fas fa-question-circle text-blue-600 w-4 h-4"></i>
                                     <h4 class="font-semibold text-blue-900 text-sm">Active Queue</h4>
                                     <div id="activeQueueCount" class="bg-blue-900 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">0</div>
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
                                    <div id="stalledQueueCount" class="bg-yellow-400 text-yellow-900 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">0</div>
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
                                    <div id="skippedQueueCount" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">0</div>
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
                    <div class="bg-white border border-gray-200 rounded-lg p-6" style="background-color: #DBEAFE;">
                        <h3 class="text-lg font-semibold mb-4" style="color: #003366;">Today's Transaction Status</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Average Service Time -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #003366; box-shadow: 0 0 0 8px #EFF6FF;">
                                    <i class="fas fa-clock text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;" id="statAvgTime">--</p>
                                <p class="text-sm" style="color: #485563;">Avg Service Time</p>
                            </div>
                            <!-- Completed -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #28A745; box-shadow: 0 0 0 8px #DCFCE7;">
                                    <i class="fas fa-check text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;" id="statCompleted">0</p>
                                <p class="text-sm" style="color: #485563;">Completed</p>
                            </div>
                            <!-- Stalled -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #FFD700; box-shadow: 0 0 0 8px rgba(255, 215, 0, 0.1);">
                                    <i class="fas fa-pause text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;" id="statStalled">0</p>
                                <p class="text-sm" style="color: #485563;">Stalled</p>
                            </div>
                            <!-- Cancelled -->
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background-color: #DC3545; box-shadow: 0 0 0 8px #FEE2E2;">
                                    <i class="fas fa-times text-white text-2xl"></i>
                                </div>
                                <p class="text-2xl font-bold mb-1" style="color: #003366;" id="statCancelled">0</p>
                                <p class="text-sm" style="color: #485563;">Cancelled</p>
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
                    startActiveQueueTimers();
                    startStalledQueueTimers();
                    startSkippedQueueTimers();
                })
                .catch(error => {
                    console.log('No backend connection yet - no data available');
                    // No dummy data - empty state
                    // loadEmptyData();
                });
        }
        
        // Load empty data when no backend connection
        function loadEmptyData() {
            const emptyData = {
                currentQueue: {
                    number: 'P-001',
                    priority: 'priority',
                    timeRequested: new Date().toLocaleTimeString([], {hour: 'numeric', minute: '2-digit'}),
                    waitTime: '0h 5m',
                    student: {
                        name: 'Maria Santos',
                        id: '2020-54321',
                        course: 'BSCS',
                        year: '4'
                    },
                    services: [
                        'Good Moral Certificate',
                        'Request for Uniform Exemption',
                        'Scholarship Verification',
                        'ID Validation'
                    ]
                },
                queueList: [
                    {
                        queue_number: 'P-002',
                        queue_type: 'priority',
                        student_name: 'Ana Dela Cruz',
                        student_id: '2021-67890',
                        status: 'active',
                        services: 'Good Moral Certificate',
                        created_at: new Date().toISOString()
                    },
                    {
                        queue_number: 'R-001',
                        queue_type: 'regular',
                        student_name: 'Juan Dela Cruz',
                        student_id: '2021-12345',
                        status: 'active',
                        services: 'Transcript Request, Good Moral Certificate',
                        created_at: new Date(Date.now() - 5 * 60 * 1000).toISOString()
                    },
                    {
                        queue_number: 'P-003',
                        queue_type: 'priority',
                        student_name: 'Sarah Johnson',
                        student_id: '2020-78901',
                        status: 'stalled',
                        services: 'Scholarship Verification, ID Validation, Transcript Request',
                        created_at: new Date(Date.now() - 30 * 60 * 1000).toISOString()
                    },
                    {
                        queue_number: 'R-015',
                        queue_type: 'regular',
                        student_name: 'Michael Chen',
                        student_id: '2022-23456',
                        status: 'stalled',
                        services: 'Transcript Request',
                        created_at: new Date(Date.now() - 15 * 60 * 1000).toISOString()
                    },
                    {
                        queue_number: 'R-010',
                        queue_type: 'regular',
                        student_name: 'Lisa Garcia',
                        student_id: '2021-34567',
                        status: 'skipped',
                        services: 'Good Moral Certificate',
                        created_at: new Date(Date.now() - 45 * 60 * 1000).toISOString()
                    },
                    {
                        queue_number: 'P-005',
                        queue_type: 'priority',
                        student_name: 'David Park',
                        student_id: '2020-45678',
                        status: 'skipped',
                        services: 'ID Validation',
                        created_at: new Date(Date.now() - 60 * 60 * 1000).toISOString()
                    }
                ],
                statistics: {
                    avgServiceTime: "--",
                    completed: 0,
                    stalled: 2,
                    cancelled: 2
                }
            };
            
            updateCurrentQueue(emptyData.currentQueue);
            updateQueueList(emptyData.queueList);
            updateStatistics(emptyData.statistics);
            startActiveQueueTimers();
            startStalledQueueTimers();
            startSkippedQueueTimers();
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
                // Colors based on priority
                const isPriority = queue.priority === 'priority';
                const colorHex = isPriority ? '#DAA520' : '#003366';
                const numEl = document.getElementById('currentQueueNumber');
                const cardEl = document.getElementById('currentServingCard');
                if (numEl) numEl.style.color = colorHex;
                if (cardEl) cardEl.style.borderColor = colorHex;
                // Priority pill
                const pt = document.getElementById('priorityType');
                if (isPriority) {
                    pt.innerHTML = '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold" style="background-color:#DAA520;color:#111">\
                        <i class="fas fa-star mr-2" style="color:#111"></i>Priority</span>';
                } else {
                    pt.innerHTML = '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold" style="background-color:#003366;color:#fff">\
                        Regular</span>';
                }
                // Services - pass special note if available
                const specialNote = queue.specialNote || queue.special_note || queue.notes || queue.stall_note || queue.service_note || '';
                renderServices(queue.services || [], specialNote);
            } else {
                // Show empty state
                document.getElementById('currentQueueNumber').textContent = '--';
                document.getElementById('studentName').textContent = '--';
                document.getElementById('studentId').textContent = '--';
                document.getElementById('studentCourse').textContent = '--';
                document.getElementById('studentYear').textContent = '--';
                document.getElementById('timeRequested').textContent = '--';
                document.getElementById('waitTime').textContent = '--';
                renderServices([], '');
            }
        }

        // Render services as tabs with panels
        function renderServices(services, specialNote = '') {
            const tabs = document.getElementById('servicesTabs');
            const panels = document.getElementById('servicesPanels');
            const empty = document.getElementById('servicesEmpty');
            tabs.innerHTML = '';
            panels.innerHTML = '';
            if (!services || services.length === 0) {
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');
            services.forEach((svc, i) => {
                const tabId = `wk-svc-tab-${i}`;
                const panelId = `wk-svc-panel-${i}`;
                // tab
                const btn = document.createElement('button');
                btn.id = tabId;
                btn.type = 'button';
                btn.setAttribute('role', 'tab');
                btn.setAttribute('aria-controls', panelId);
                btn.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
                btn.className = 'px-4 py-2 rounded-lg border text-sm font-semibold ' + (i === 0 ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-blue-900 border-blue-200 hover:bg-blue-50');
                btn.textContent = (svc || '').trim();
                btn.addEventListener('click', () => showServiceTabWorking(i));
                tabs.appendChild(btn);

                // panel
                const panel = document.createElement('div');
                panel.id = panelId;
                if (i !== 0) panel.classList.add('hidden');
                panel.innerHTML = `
                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="font-semibold text-gray-900">${escapeHtml((svc || '').trim())}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Required Documents</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-gray-800"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300" checked><span>Valid Student ID</span></label>
                            <label class="flex items-center gap-2 text-gray-800"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300" checked><span>Certificate of Registration</span></label>
                            <label class="flex items-center gap-2 text-gray-800"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300"><span>Payment Receipt</span></label>
                            <label class="flex items-center gap-2 text-gray-800"><input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300" checked><span>2x2 ID Picture</span></label>
                        </div>
                        <div class="mt-3 rounded-md bg-[#DCFCE7] border-l-4 border-l-[#28A745] flex items-center gap-3 px-4 py-2">
                            <div class="flex-shrink-0 w-6 h-6 bg-[#28A745] rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-sm font-medium text-[#28A745]">3 of 4 documents verified</span>
                        </div>
                    </div>
                    ${specialNote ? `
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-[#111827] mb-2">Service Notes</h4>
                        <div class="rounded-lg bg-[#F9FAFB] border-l-4 border-l-[#D1D5DB] shadow-sm">
                            <div class="w-full px-3 py-2 bg-[#F9FAFB] rounded-lg text-[#111827] min-h-[60px]">
                                ${escapeHtml(specialNote)}
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-2"><i class="fas fa-exclamation-circle text-yellow-500"></i><h4 class="text-sm font-semibold text-gray-700">Special Notes</h4></div>
                        <div class="border rounded-lg bg-gray-50">
                            <textarea class="w-full px-3 py-2 bg-gray-50 rounded-lg focus:outline-none text-gray-500" rows="2" placeholder="Student needs to submit additional documents for verification."></textarea>
                        </div>
                    </div>
                    <button type="button" class="inline-flex items-center gap-2 border-2 border-dashed border-blue-300 text-blue-900 px-4 py-2 rounded-lg hover:bg-blue-50"><span class="text-xl leading-none">+</span>Add Note</button>
                `;
                panels.appendChild(panel);
            });
        }

        function showServiceTabWorking(index) {
            const panels = document.querySelectorAll('[id^="wk-svc-panel-"]');
            const tabs = document.querySelectorAll('[id^="wk-svc-tab-"]');
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

        function escapeHtml(str){
            return (str || '').replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[s]));
        }
        
        // Update queue list
        function updateQueueList(queues) {
            // Update counts using specific IDs
            const totalCount = document.getElementById('queueListTotalCount');
            const activeCount = document.getElementById('activeQueueCount');
            const stalledCount = document.getElementById('stalledQueueCount');
            const skippedCount = document.getElementById('skippedQueueCount');
            
            if (totalCount) totalCount.textContent = queues.length;
            
            // Count by status
            const activeQueues = queues.filter(q => q.status === 'active').length;
            const stalledQueues = queues.filter(q => q.status === 'stalled').length;
            const skippedQueues = queues.filter(q => q.status === 'skipped').length;
            
            if (activeCount) activeCount.textContent = activeQueues;
            if (stalledCount) stalledCount.textContent = stalledQueues;
            if (skippedCount) skippedCount.textContent = skippedQueues;

            // Populate Active Queue list
            const activeContainer = document.getElementById('activeQueue-content');
            if (activeContainer) {
                const actives = queues.filter(q => q.status === 'active');
                if (actives.length === 0) {
                    activeContainer.innerHTML = `
                        <div class="px-5 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-3xl mb-2"></i>
                            <p>No active queue items</p>
                        </div>
                    `;
                } else {
                    activeContainer.innerHTML = actives.map((q, idx) => {
                        const priorityIcon = q.queue_type === 'priority' ? '<i class="fas fa-star text-yellow-500 text-xs"></i>' : '';
                        const numColor = q.queue_type === 'priority' ? '#DAA520' : '#003366';
                        
                        // Handle services display
                        let serviceName = 'Service Request';
                        let additionalServices = 0;
                        if (q.services && q.services.length > 0) {
                            const servicesArray = q.services.split(', ');
                            serviceName = servicesArray[0].trim();
                            additionalServices = servicesArray.length - 1;
                        } else if (q.service_name || q.service) {
                            serviceName = q.service_name || q.service;
                        }
                        
                        return `
                        <div class="px-5 py-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100" data-active-idx="${idx}" data-created-at="${q.created_at}">
                            <div class="flex items-start justify-between">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        ${priorityIcon}
                                        <span class="font-bold" style="color:${numColor};">${q.queue_number || q.queueNumber || ''}</span>
                                    </div>
                                    <div class="text-sm text-gray-900 leading-tight">${q.student_name || q.studentName || ''}</div>
                                    <div class="text-xs text-gray-500">
                                        ${escapeHtml(serviceName)}${additionalServices > 0 ? '<span class="text-blue-600 font-semibold"> +' + additionalServices + '</span>' : ''}
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
                        </div>`;
                    }).join('');

                    // Do not hook up Active list clicks to "Currently Serving" (disabled by request)
                }
            }

            // Populate Stalled Queue list
            const stalledContainer = document.getElementById('stalledQueue-content');
            if (stalledContainer) {
                const stalled = queues.filter(q => q.status === 'stalled');
                if (stalled.length === 0) {
                    stalledContainer.innerHTML = `
                        <div class="px-5 py-8 text-center text-gray-500">
                            <i class=\"fas fa-exclamation-triangle text-3xl mb-2\"></i>
                            <p>No stalled queue items</p>
                        </div>
                    `;
                } else {
                    stalledContainer.innerHTML = stalled.map((q) => {
                        const priorityIcon = q.queue_type === 'priority' ? '<i class="fas fa-star text-yellow-500 text-xs"></i>' : '';
                        const numColor = (q.queue_type === 'priority') ? '#DAA520' : '#003366';
                        
                        // Handle services display
                        let serviceName = 'Service Request';
                        let additionalServices = 0;
                        if (q.services && q.services.length > 0) {
                            const servicesArray = q.services.split(', ');
                            serviceName = servicesArray[0].trim();
                            additionalServices = servicesArray.length - 1;
                        } else if (q.service_name || q.service) {
                            serviceName = q.service_name || q.service;
                        }
                        
                        return `
                        <div class=\"px-5 py-4 hover:bg-gray-50 border-b border-gray-100\" data-created-at=\"${q.created_at}\">
                            <div class=\"flex items-start justify-between\">
                                <div class=\"min-w-0\">
                                    <div class=\"flex items-center gap-2 mb-1\">
                                        ${priorityIcon}
                                        <span class=\"font-bold\" style=\"color:${numColor};\">${q.queue_number || ''}</span>
                                    </div>
                                    <div class=\"text-sm text-gray-900 leading-tight\">${q.student_name || ''}</div>
                                    <div class=\"text-xs text-gray-500\">
                                        ${escapeHtml(serviceName)}${additionalServices > 0 ? '<span class="text-blue-600 font-semibold"> +' + additionalServices + '</span>' : ''}
                                    </div>
                                    <div class=\"mt-2\">
                                        <span class=\"inline-flex items-center gap-2 text-[11px] font-medium bg-yellow-50 text-yellow-800 px-3 py-1 rounded\">\
                                            <i class=\"fas fa-file text-yellow-600\"></i>Missing Documents\
                                        </span>
                                    </div>
                                </div>
                                <div class=\"shrink-0 text-right\">
                                    <div class=\"mb-2\">\
                                        <span class=\"inline-flex items-center gap-1 text-[11px] font-semibold bg-rose-100 text-rose-600 px-2.5 py-1 rounded-full stalled-queue-timer\">\
                                            <i class=\"fas fa-clock\"></i>\
                                            <span class=\"stalled-timer-display\">0 min</span>\
                                        </span>
                                    </div>
                                    <button class=\"px-4 py-2 bg-[#003366] hover:opacity-90 text-white rounded w-28 text-sm\">Resume</button>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                }
            }

            // Populate Skipped Queue list
            const skippedContainer = document.getElementById('skippedQueue-content');
            if (skippedContainer) {
                const skipped = queues.filter(q => q.status === 'skipped');
                if (skipped.length === 0) {
                    skippedContainer.innerHTML = `
                        <div class="px-5 py-8 text-center text-gray-500">
                            <i class=\"fas fa-times-circle text-3xl mb-2\"></i>
                            <p>No skipped queue items</p>
                        </div>
                    `;
                } else {
                    skippedContainer.innerHTML = skipped.map((q) => {
                        const priorityIcon = q.queue_type === 'priority' ? '<i class="fas fa-star text-yellow-500 text-xs"></i>' : '';
                        const numColor = (q.queue_type === 'priority') ? '#DAA520' : '#003366';
                        
                        // Handle services display
                        let serviceName = 'Service Request';
                        let additionalServices = 0;
                        if (q.services && q.services.length > 0) {
                            const servicesArray = q.services.split(', ');
                            serviceName = servicesArray[0].trim();
                            additionalServices = servicesArray.length - 1;
                        } else if (q.service_name || q.service) {
                            serviceName = q.service_name || q.service;
                        }
                        
                        return `
                        <div class=\"px-5 py-4 hover:bg-gray-50 border-b border-gray-100\" data-queue-id=\"${q.id || 0}\" data-created-at=\"${q.created_at}\">
                            <div class=\"flex items-start justify-between\">
                                <div class=\"min-w-0\">
                                    <div class=\"flex items-center gap-2 mb-1\">
                                        ${priorityIcon}
                                        <span class=\"font-bold\" style=\"color:${numColor};\">${q.queue_number || ''}</span>
                                    </div>
                                    <div class=\"text-sm text-gray-900 leading-tight\">${q.student_name || ''}</div>
                                    <div class=\"text-xs text-gray-500\">
                                        ${escapeHtml(serviceName)}${additionalServices > 0 ? '<span class="text-blue-600 font-semibold"> +' + additionalServices + '</span>' : ''}
                                    </div>
                                </div>
                                <div class=\"shrink-0 text-right\">
                                    <div class=\"mb-2\">
                                        <span class=\"inline-flex items-center gap-1 text-[11px] font-semibold bg-red-100 text-red-600 px-2.5 py-1 rounded-full\">\
                                            <i class=\"fas fa-hourglass-half\"></i>\
                                            <span class=\"timer-display\">0:00 left</span>\
                                        </span>
                                    </div>
                                    <button onclick=\"resumeSkippedQueue(${q.id || 0})\" class=\"px-4 py-2 bg-[#003366] hover:opacity-90 text-white rounded w-28 text-sm font-medium\">Resume</button>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                }
            }
        }
        
        // Update statistics
        function updateStatistics(stats) {
            const avgTimeElement = document.getElementById('statAvgTime');
            const completedElement = document.getElementById('statCompleted');
            const stalledElement = document.getElementById('statStalled');
            const cancelledElement = document.getElementById('statCancelled');
            
            if (avgTimeElement) avgTimeElement.textContent = stats.avgServiceTime || '--';
            if (completedElement) completedElement.textContent = stats.completed || 0;
            if (stalledElement) stalledElement.textContent = stats.stalled || 0;
            if (cancelledElement) cancelledElement.textContent = stats.cancelled || 0;
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // Intentionally not wiring COMPLETE & NEXT per request
            const stallBtn = document.querySelector('.bg-yellow-500');
            const skipBtn = document.querySelector('.bg-blue-900');
            if (stallBtn) stallBtn.addEventListener('click', stallQueue);
            if (skipBtn) skipBtn.addEventListener('click', skipQueue);
        }
        
        // Action functions
        function completeQueue() {
            // Disabled per request; not wired to any action
            console.log('Complete & Next is disabled.');
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
        
        // Resume skipped queue
        function resumeSkippedQueue(queueId) {
            if (confirm('Resume this skipped queue and put it back in the active queue?')) {
                // TODO: Call backend API to resume queue
                console.log('Resume queue:', queueId);
                loadQueueData(); // Reload queue data
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
                const response = await fetch(`/api/queue/cancel?id=${queueId}`, {
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
                                // Update counts and reload data
                                loadQueueData();
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