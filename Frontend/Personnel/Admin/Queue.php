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
    <!-- Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Left Panel - Current Queue Details -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Currently Serving Card -->
                    <div class="bg-white border-2 border-yellow-600 rounded-lg p-8 text-center shadow-sm">
                        <div class="text-6xl font-bold text-yellow-600 mb-3" id="currentQueueNumber">P-042</div>
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
                                        <p class="font-bold text-gray-800 text-base" id="studentName">Juan Miguel Dela Cruz</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Student ID</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentId">2021-12345</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Course</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentCourse">BSIT</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Year Level</span>
                                        <p class="font-bold text-gray-800 text-base" id="studentYear">3rd Year</p>
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
                                        <p class="font-bold text-gray-800 text-base" id="timeRequested">2:30 PM</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600 block mb-1">Total Wait Time</span>
                                        <p class="font-bold text-gray-800 text-base" id="waitTime">8 min 42 sec</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requested Services -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-blue-800 mb-6">Requested Services</h3>
                        
                        <!-- Good Moral Certificate Service Card -->
                        <div class="mb-4">
                            <div class="border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" onclick="toggleServiceDetails('goodMoral')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" checked onclick="event.stopPropagation(); toggleAllDocuments('goodMoral')">
                                        <span class="text-gray-800 font-medium">Good Moral Certificate</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-500 transition-transform" id="goodMoral-arrow"></i>
                                </div>
                            </div>
                            
                            <!-- Required Documents for Good Moral Certificate -->
                            <div id="goodMoral-details" class="mt-4 ml-7 border-l-2 border-blue-200 pl-4 hidden">
                                <h4 class="text-md font-bold text-blue-800 mb-3">Required Documents</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked onchange="updateServiceCheckbox('goodMoral')">
                                        <span class="text-gray-800">Valid Student ID</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked onchange="updateServiceCheckbox('goodMoral')">
                                        <span class="text-gray-800">Certificate of Registration</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-gray-400 border-gray-300 rounded" onchange="updateServiceCheckbox('goodMoral')">
                                        <span class="text-gray-800">Payment Receipt</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked onchange="updateServiceCheckbox('goodMoral')">
                                        <span class="text-gray-800">2x2 ID Picture</span>
                                    </label>
                                </div>
                                <div class="mt-4">
                                    <div class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        3 of 4 documents verified
                                    </div>
                                </div>
                                
                                <!-- Service Notes -->
                                <div class="mt-6">
                                    <h4 class="text-md font-bold text-blue-800 mb-3">Service Notes</h4>
                                    
                                    <!-- Service Notes Cards Container -->
                                    <div id="goodMoral-serviceNotes" class="space-y-2">
                                        <!-- Initial service note card -->
                                        <div class="bg-gray-100 border border-gray-200 rounded-lg p-3 flex items-start justify-between">
                                            <p class="text-gray-800 text-sm">Please bring parent's consent form for processing</p>
                                            <button onclick="removeServiceNote(this)" class="text-gray-500 hover:text-gray-700 ml-2">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Dotted Separator -->
                                    <div class="mt-4 mb-4 border-t border-dashed border-gray-300"></div>
                                    
                                    <!-- Special Notes Input -->
                                    <div class="flex items-center space-x-2 mb-3">
                                        <i class="fas fa-exclamation-circle text-yellow-500"></i>
                                        <h4 class="text-md font-bold text-blue-800">Special Notes</h4>
                                    </div>
                                    <textarea id="goodMoral-specialInput" class="w-full p-3 border border-gray-300 rounded-md text-gray-800 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter special notes..."></textarea>
                                    <button onclick="addSpecialNote('goodMoral')" class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 px-3 py-1 rounded-md hover:bg-blue-50 transition">+ Add Note</button>
                                </div>
                            </div>
                        </div>

                        <!-- Request for Uniform Exemption Service Card -->
                        <div class="mb-6">
                            <div class="border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" onclick="toggleServiceDetails('uniformExemption')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onclick="event.stopPropagation(); toggleAllDocuments('uniformExemption')">
                                        <span class="text-gray-800 font-medium">Request for Uniform Exemption</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-500 transition-transform" id="uniformExemption-arrow"></i>
                                </div>
                            </div>
                            
                            <!-- Required Documents for Uniform Exemption -->
                            <div id="uniformExemption-details" class="mt-4 ml-7 border-l-2 border-blue-200 pl-4 hidden">
                                <h4 class="text-md font-bold text-blue-800 mb-3">Required Documents</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-gray-800">Medical Certificate</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-gray-800">Parent's Consent Letter</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-gray-800">Valid Student ID</span>
                                    </label>
                                </div>
                                <div class="mt-4">
                                    <div class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i>
                                        0 of 3 documents verified
                                    </div>
                                </div>
                                
                                <!-- Service Notes -->
                                <div class="mt-6">
                                    <h4 class="text-md font-bold text-blue-800 mb-3">Service Notes</h4>
                                    
                                    <!-- Service Notes Cards Container -->
                                    <div id="uniformExemption-serviceNotes" class="space-y-2">
                                        <!-- Service notes will be added here as cards -->
                                    </div>
                                    
                                    <!-- Dotted Separator -->
                                    <div class="mt-4 mb-4 border-t border-dashed border-gray-300"></div>
                                    
                                    <!-- Special Notes Input -->
                                    <div class="flex items-center space-x-2 mb-3">
                                        <i class="fas fa-exclamation-circle text-yellow-500"></i>
                                        <h4 class="text-md font-bold text-blue-800">Special Notes</h4>
                                    </div>
                                    <textarea id="uniformExemption-specialInput" class="w-full p-3 border border-gray-300 rounded-md text-gray-800 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter special notes..."></textarea>
                                    <button onclick="addSpecialNote('uniformExemption')" class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium border border-blue-300 px-3 py-1 rounded-md hover:bg-blue-50 transition">+ Add Note</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md">
                            <i class="fas fa-arrow-right"></i>
                            <span>COMPLETE & NEXT</span>
                        </button>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md">
                            <i class="fas fa-pause"></i>
                            <span>MARK AS STALLED</span>
                        </button>
                        <button class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md">
                            <i class="fas fa-forward"></i>
                            <span>SKIP QUEUE</span>
                        </button>
                        <button id="pauseResumeBtn" class="bg-white border-2 border-yellow-400 text-black font-semibold py-3 px-6 rounded-lg flex items-center justify-center space-x-2 shadow-md hover:bg-yellow-50 transition" onclick="togglePauseResume()">
                            <i class="fas fa-pause"></i>
                            <span>Pause Queue</span>
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
                             <div class="bg-blue-900 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-semibold">8</div>
                         </div>
                         
                         <!-- Active Queue -->
                         <div class="border-b border-gray-200">
                             <button class="group flex justify-between items-center w-full px-5 py-3 bg-blue-50 focus:outline-none" onclick="toggleQueueSection('activeQueue')">
                                 <div class="flex items-center space-x-2">
                                     <i class="fas fa-question-circle text-blue-600 w-4 h-4"></i>
                                     <h4 class="font-semibold text-blue-900 text-sm">Active Queue</h4>
                                     <div class="bg-blue-900 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">2</div>
                                 </div>
                                 <i class="fas fa-chevron-down text-blue-900 w-4 h-4 transition-transform" id="activeQueue-arrow"></i>
                             </button>
                             <!-- Active queue items -->
                             <div id="activeQueue-content" class="divide-y divide-gray-200">
                                 <!-- Item 1 -->
                                 <div class="px-5 py-4">
                                     <div class="flex justify-between items-center mb-1">
                                         <span class="text-blue-900 font-bold text-sm">R-043</span>
                                         <div class="flex items-center space-x-1 bg-blue-100 px-2 py-0.5 rounded-full text-xs text-blue-900 font-semibold">
                                             <i class="fas fa-clock w-3 h-3"></i>
                                             <span>35 min</span>
                                         </div>
                                     </div>
                                     <p class="font-semibold text-base text-blue-900 mb-0.5">Maria Santos</p>
                                     <p class="text-gray-500 text-xs mb-2">Good Moral Certificate</p>
                                     <div class="flex items-center space-x-2 text-sm text-blue-900 font-semibold">
                                         <span class="w-2 h-2 rounded-full bg-blue-900 inline-block"></span>
                                         <span>Waiting in queue</span>
                                     </div>
                                 </div>
                                 <!-- Item 2 -->
                                 <div class="px-5 py-4">
                                     <div class="flex justify-between items-center mb-1">
                                         <span class="text-yellow-600 font-bold text-sm flex items-center space-x-1">
                                             <i class="fas fa-star text-yellow-500 w-4 h-4"></i>
                                             <span>P-044</span>
                                         </span>
                                         <div class="flex items-center space-x-1 bg-blue-100 px-2 py-0.5 rounded-full text-xs text-blue-900 font-semibold">
                                             <i class="fas fa-clock w-3 h-3"></i>
                                             <span>35 min</span>
                                         </div>
                                     </div>
                                     <p class="font-semibold text-base text-blue-900 mb-0.5">Carlos Rivera</p>
                                     <p class="text-gray-500 text-xs mb-2">Transcript Request</p>
                                     <div class="flex items-center space-x-2 text-sm text-blue-900 font-semibold">
                                         <span class="w-2 h-2 rounded-full bg-blue-900 inline-block"></span>
                                         <span>Waiting in queue</span>
                                     </div>
                                 </div>
                             </div>
                         </div>

                        <!-- Stalled Queue -->
                        <div class="border-b border-gray-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-yellow-50 focus:outline-none" onclick="toggleQueueSection('stalledQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 w-4 h-4"></i>
                                    <h4 class="font-semibold text-yellow-600 text-sm">Stalled Queue</h4>
                                    <div class="bg-yellow-400 text-yellow-900 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">3</div>
                                </div>
                                <i class="fas fa-chevron-down text-yellow-600 w-4 h-4 transition-transform" id="stalledQueue-arrow"></i>
                            </button>
                            <!-- Stalled items -->
                            <div id="stalledQueue-content" class="divide-y divide-gray-200">
                                <!-- Stalled Item 1 -->
                                <div class="px-5 py-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-blue-900 font-bold text-sm">R-020</span>
                                        <div class="flex items-center space-x-1 bg-red-100 px-2 py-0.5 rounded-full text-xs text-red-800 font-semibold">
                                            <i class="fas fa-clock w-3 h-3"></i>
                                            <span>35 min</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-base text-blue-900 mb-0.5">Jerick Burdagol</p>
                                    <p class="text-gray-500 text-xs mb-2">Certificate Request</p>
                                    <div class="p-2 mb-2 bg-yellow-50 rounded border border-yellow-100 flex items-center space-x-2 text-yellow-700 text-xs font-semibold">
                                        <i class="fas fa-folder-open w-4 h-4"></i>
                                        <span>Missing Documents</span>
                                    </div>
                                    <button class="w-full bg-blue-900 text-white font-semibold py-2 rounded focus:outline-none hover:bg-blue-800 flex items-center justify-center space-x-2">
                                        <i class="fas fa-play w-4 h-4"></i>
                                        <span>Resume</span>
                                    </button>
                                </div>

                                <!-- Stalled Item 2 -->
                                <div class="px-5 py-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-blue-900 font-bold text-sm">R-021</span>
                                        <div class="flex items-center space-x-1 bg-red-100 px-2 py-0.5 rounded-full text-xs text-red-800 font-semibold">
                                            <i class="fas fa-clock w-3 h-3"></i>
                                            <span>25 min</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-base text-blue-900 mb-0.5">Paul Bantaruso</p>
                                    <p class="text-gray-500 text-xs mb-2">Good Moral</p>
                                    <div class="p-2 mb-2 bg-yellow-50 rounded border border-yellow-100 flex items-center space-x-2 text-yellow-700 text-xs font-semibold">
                                        <i class="fas fa-folder-open w-4 h-4"></i>
                                        <span>Missing Documents</span>
                                    </div>
                                    <button class="w-full bg-blue-900 text-white font-semibold py-2 rounded focus:outline-none hover:bg-blue-800 flex items-center justify-center space-x-2">
                                        <i class="fas fa-play w-4 h-4"></i>
                                        <span>Resume</span>
                                    </button>
                                </div>

                                <!-- Stalled Item 3 -->
                                <div class="px-5 py-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-blue-900 font-bold text-sm">R-022</span>
                                        <div class="flex items-center space-x-1 bg-red-100 px-2 py-0.5 rounded-full text-xs text-red-800 font-semibold">
                                            <i class="fas fa-clock w-3 h-3"></i>
                                            <span>20 min</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-base text-blue-900 mb-0.5">Alfredo Binulbil</p>
                                    <p class="text-gray-500 text-xs mb-2">Good Moral</p>
                                    <div class="p-2 mb-2 bg-yellow-50 rounded border border-yellow-100 flex items-center space-x-2 text-yellow-700 text-xs font-semibold">
                                        <i class="fas fa-folder-open w-4 h-4"></i>
                                        <span>Missing Documents</span>
                                    </div>
                                    <button class="w-full bg-blue-900 text-white font-semibold py-2 rounded focus:outline-none hover:bg-blue-800 flex items-center justify-center space-x-2">
                                        <i class="fas fa-play w-4 h-4"></i>
                                        <span>Resume</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Skipped Queue -->
                        <div class="border-b border-red-200">
                            <button class="group flex justify-between items-center w-full px-5 py-3 bg-red-50 hover:bg-red-100 focus:outline-none" onclick="toggleQueueSection('skippedQueue')">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-times-circle text-red-600 w-4 h-4"></i>
                                    <h4 class="font-semibold text-red-600 text-sm">Skipped Queue</h4>
                                    <div class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold">3</div>
                                </div>
                                <i class="fas fa-chevron-down text-red-600 w-4 h-4 transition-transform" id="skippedQueue-arrow"></i>
                            </button>
                            <div id="skippedQueue-content" class="divide-y divide-gray-200 hidden">
                                <!-- Skipped Queue Item 1 -->
                                <div class="px-5 py-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-blue-900 font-bold text-sm">R-015</span>
                                        <div class="flex items-center space-x-1 bg-red-100 px-2 py-0.5 rounded-full text-xs text-red-800 font-semibold">
                                            <i class="fas fa-clock w-3 h-3"></i>
                                            <span>45 min</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-base text-blue-900 mb-0.5">Ana Garcia</p>
                                    <p class="text-gray-500 text-xs mb-2">Transcript Request</p>
                                    <div class="flex items-center space-x-2 text-sm text-red-600 font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-red-600 inline-block"></span>
                                        <span>Skipped</span>
                                    </div>
                                </div>

                                <!-- Skipped Queue Item 2 -->
                                <div class="px-5 py-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-blue-900 font-bold text-sm">R-016</span>
                                        <div class="flex items-center space-x-1 bg-red-100 px-2 py-0.5 rounded-full text-xs text-red-800 font-semibold">
                                            <i class="fas fa-clock w-3 h-3"></i>
                                            <span>38 min</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-base text-blue-900 mb-0.5">Luis Martinez</p>
                                    <p class="text-gray-500 text-xs mb-2">Good Moral Certificate</p>
                                    <div class="flex items-center space-x-2 text-sm text-red-600 font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-red-600 inline-block"></span>
                                        <span>Skipped</span>
                                    </div>
                                </div>

                                <!-- Skipped Queue Item 3 -->
                                <div class="px-5 py-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-blue-900 font-bold text-sm">R-017</span>
                                        <div class="flex items-center space-x-1 bg-red-100 px-2 py-0.5 rounded-full text-xs text-red-800 font-semibold">
                                            <i class="fas fa-clock w-3 h-3"></i>
                                            <span>32 min</span>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-base text-blue-900 mb-0.5">Sofia Rodriguez</p>
                                    <p class="text-gray-500 text-xs mb-2">Certificate Request</p>
                                    <div class="flex items-center space-x-2 text-sm text-red-600 font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-red-600 inline-block"></span>
                                        <span>Skipped</span>
                                    </div>
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
                                <p class="text-2xl font-bold text-gray-900">8 min</p>
                                <p class="text-sm text-gray-600">Avg Service Time</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">32</p>
                                <p class="text-sm text-gray-600">Completed</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-pause-circle text-yellow-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">3</p>
                                <p class="text-sm text-gray-600">Stalled</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-times-circle text-red-600 text-2xl mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">2</p>
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
                    console.log('No backend connection yet - using sample data');
                    // Use sample data for demonstration
                    loadSampleData();
                });
        }
        
        // Load sample data matching the image
        function loadSampleData() {
            // Sample data matches the image exactly
            const sampleData = {
                currentQueue: {
                    number: "P-042",
                    student: {
                        name: "Juan Miguel Dela Cruz",
                        id: "2021-12345",
                        course: "BSIT",
                        year: "3rd Year"
                    },
                    priority: "priority",
                    timeRequested: "2:30 PM",
                    waitTime: "8 min 42 sec",
                    services: ["Good Moral Certificate"],
                    documents: [
                        { name: "Valid Student ID", verified: true },
                        { name: "Certificate of Registration", verified: true },
                        { name: "Payment Receipt", verified: false },
                        { name: "2x2 ID Picture", verified: true }
                    ]
                },
                queueList: [
                    {
                        number: "R-043",
                        student: { name: "Maria Santos" },
                        service: "Good Moral Certificate",
                        status: "active",
                        waitTime: "35 min",
                        priority: "regular"
                    },
                    {
                        number: "P-044",
                        student: { name: "Carlos Rivera" },
                        service: "Transcript Request",
                        status: "active",
                        waitTime: "35 min",
                        priority: "priority"
                    },
                    {
                        number: "R-020",
                        student: { name: "Jerick Burdagol" },
                        service: "Certificate Request",
                        status: "stalled",
                        waitTime: "35 min",
                        stallReason: "Missing Documents"
                    },
                    {
                        number: "R-021",
                        student: { name: "Paul Bantaruso" },
                        service: "Good Moral",
                        status: "stalled",
                        waitTime: "25 min",
                        stallReason: "Missing Documents"
                    },
                    {
                        number: "R-022",
                        student: { name: "Alfredo Binulbil" },
                        service: "Good Moral",
                        status: "stalled",
                        waitTime: "20 min",
                        stallReason: "Missing Documents"
                    }
                ],
                statistics: {
                    avgServiceTime: "8 min",
                    completed: 32,
                    stalled: 3,
                    cancelled: 2
                }
            };
            
            updateCurrentQueue(sampleData.currentQueue);
            updateQueueList(sampleData.queueList);
            updateStatistics(sampleData.statistics);
        }
        
        // Update current queue display
        function updateCurrentQueue(queue) {
            if (queue) {
                document.getElementById('currentQueueNumber').textContent = queue.number;
                // Update student information
                document.querySelector('.space-y-3 .font-bold').textContent = queue.student.name;
                // Update other fields as needed
            }
        }
        
        // Update queue list
        function updateQueueList(queues) {
            // Update counts
            document.querySelector('.bg-blue-100').textContent = queues.length;
            // Update individual queue items as needed
        }
        
        // Update statistics
        function updateStatistics(stats) {
            // Update statistics display
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
        
        // Toggle between Pause Queue and Resume Queue
        function togglePauseResume() {
            const button = document.getElementById('pauseResumeBtn');
            const icon = button.querySelector('i');
            const text = button.querySelector('span');
            
            if (text.textContent === 'Pause Queue') {
                // Change to Resume Queue
                icon.className = 'fas fa-play';
                text.textContent = 'Resume Queue';
                console.log('Queue paused');
            } else {
                // Change back to Pause Queue
                icon.className = 'fas fa-pause';
                text.textContent = 'Pause Queue';
                console.log('Queue resumed');
            }
        }
    </script>
</body>
</html>