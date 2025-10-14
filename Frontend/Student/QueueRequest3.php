<?php
// Backend-ready structure - ready for future implementation
session_start();

// Service information for popups
$serviceInfo = [
    'good-moral' => [
        'title' => 'Request for Good Moral Certificate',
        'required_documents' => [
            'Valid Student ID Card',
            'Accomplished Request Form',
            'Certificate of Registration (current semester)',
            'Clearance from previous semester',
            'Two copies of 2×2 ID picture',
            'Parent\'s consent form (for minors)'
        ]
    ],
    'insurance-payment' => [
        'title' => 'Insurance Payment',
        'required_documents' => [
            'Valid Student ID Card',
            'Accomplished Request Form',
            'Certificate of Registration (current semester)',
            'Insurance payment slip',
            'Two copies of 2×2 ID picture'
        ]
    ],
    'approval-letter' => [
        'title' => 'Submission of Approval/Transmittal Letter',
        'required_documents' => [
            'Valid Student ID Card',
            'Accomplished Request Form',
            'Certificate of Registration (current semester)',
            'Original approval/transmittal letter',
            'Two copies of 2×2 ID picture'
        ]
    ],
    'temporary-gate-pass' => [
        'title' => 'Request for Temporary Gate Pass',
        'required_documents' => [
            'Valid Student ID Card',
            'Accomplished Request Form',
            'Certificate of Registration (current semester)',
            'Valid reason for gate pass',
            'Two copies of 2×2 ID picture'
        ]
    ],
    'uniform-exemption' => [
        'title' => 'Request for Uniform Exemption',
        'required_documents' => [
            'Valid Student ID Card',
            'Accomplished Request Form',
            'Certificate of Registration (current semester)',
            'Medical certificate (if applicable)',
            'Two copies of 2×2 ID picture'
        ]
    ],
    'enrollment-transfer' => [
        'title' => 'Enrollment/Transfer',
        'required_documents' => [
            'Valid Student ID Card',
            'Accomplished Request Form',
            'Certificate of Registration (current semester)',
            'Transfer credentials',
            'Two copies of 2×2 ID picture'
        ]
    ]
];

// Get student data from session (from Step 1)
$studentData = [
    'full_name' => $_SESSION['fullname'] ?? '',
    'student_id' => $_SESSION['studentid'] ?? '',
    'year_level' => $_SESSION['yearlevel'] ?? '',
    'course_program' => $_SESSION['courseprogram'] ?? ''
];

// Get selected services from session (from Step 2)
$selectedServices = $_SESSION['selected_services'] ?? [];

// Handle service removal
if (isset($_GET['remove']) && isset($_GET['index'])) {
    $indexToRemove = (int)$_GET['index'];
    if (isset($selectedServices[$indexToRemove])) {
        unset($selectedServices[$indexToRemove]);
        $selectedServices = array_values($selectedServices); // Re-index array
        $_SESSION['selected_services'] = $selectedServices;
        // Redirect to prevent resubmission
        header('Location: QueueRequest3.php');
        exit;
    }
}

// TODO: Handle form submission when implementing backend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Process final form submission
    // TODO: Generate queue number
    // TODO: Store in database
    // TODO: Redirect to success page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Queue Number Request - Review</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .modal {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-backdrop {
            animation: fadeInBackdrop 0.3s ease-out;
        }
        @keyframes fadeInBackdrop {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-r from-white via-slate-200 to-sky-500">
    <?php include 'Header.php'; ?>
    
    <main class="flex-grow flex items-center justify-center px-4 py-10">
        <div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-8" style="box-shadow: 0 8px 24px rgb(0 0 0 / 0.1);">
            <div class="flex justify-center mb-6">
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-ticket-alt text-yellow-400 text-xl"></i>
                </div>
            </div>
            <h2 class="text-blue-900 font-extrabold text-xl text-center mb-2">Request Your Queue Number</h2>
            <p class="text-center text-slate-600 mb-6 text-sm">Please provide the following information to get your queue number</p>
            
            <div class="flex items-center justify-between text-xs md:text-sm mb-4">
                <span class="font-semibold text-blue-900">
                    Step 3 of 3
                </span>
                <span class="text-gray-500">
                    Review & Submit
                </span>
            </div>
            <div class="w-full h-1 rounded-full bg-slate-300 mb-6 relative">
                <div class="h-1 rounded-full bg-yellow-400 w-full"></div>
            </div>
            <hr class="border-slate-200 mb-6"/>
            
            <form action="QueueRequest3.php" method="POST" id="reviewForm">
                <!-- Student Information Section -->
                <h3 class="text-blue-900 font-semibold mb-4 text-sm">Student Information</h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Full Name</label>
                        <p class="text-sm text-slate-900"><?php echo htmlspecialchars($studentData['full_name']); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Student ID Number</label>
                        <p class="text-sm text-slate-900"><?php echo htmlspecialchars($studentData['student_id']); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Year Level</label>
                        <p class="text-sm text-slate-900"><?php echo htmlspecialchars($studentData['year_level']); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Course/Program</label>
                        <p class="text-sm text-slate-900"><?php echo htmlspecialchars($studentData['course_program']); ?></p>
                    </div>
                </div>
                
                <!-- Selected Services Section -->
                <h3 class="text-blue-900 font-semibold mb-4 text-sm">Selected Services</h3>
                <div class="space-y-3 mb-8">
                    <?php if (empty($selectedServices)): ?>
                    <div class="bg-white border border-slate-200 rounded-lg p-4 text-center shadow-sm">
                        <p class="text-slate-500 text-sm">No services selected. Please go back to Step 2 to select services.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($selectedServices as $index => $serviceKey): ?>
                    <div class="bg-white border border-slate-200 rounded-lg p-4 flex items-center justify-between shadow-sm">
                        <span class="text-sm text-slate-900 font-medium"><?php echo htmlspecialchars($serviceInfo[$serviceKey]['title'] ?? $serviceKey); ?></span>
                        <div class="flex items-center gap-3">
                            <button type="button" class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center hover:bg-blue-600 transition" 
                                    onclick="showServiceInfo('<?php echo htmlspecialchars($serviceKey); ?>')">
                                <i class="fas fa-info text-white text-xs"></i>
                            </button>
                            <button type="button" class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center hover:bg-red-600 transition" 
                                    onclick="removeService(<?php echo $index; ?>)">
                                <i class="fas fa-trash text-white text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-center" style="gap: 70px;">
                    <button class="flex items-center gap-2 border border-slate-300 rounded-md py-3 text-slate-700 text-sm hover:bg-slate-100 transition" 
                            style="padding-left: 60px; padding-right: 60px;"
                            type="button" onclick="window.location.href='QueueRequest2.php'">
                        <i class="fas fa-arrow-left text-sm"></i>
                        Back
                    </button>
                    <button class="bg-blue-900 text-white rounded-md py-3 text-sm hover:bg-blue-800 transition flex items-center justify-center gap-2" 
                            style="padding-left: 65px; padding-right: 65px;"
                            type="submit">
                        Submit
                        <i class="fas fa-check text-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <?php include '../Footer.php'; ?>

    <!-- Service Information Modal -->
    <div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden modal-backdrop z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6 modal">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold text-slate-900"></h3>
                <button type="button" onclick="closeServiceInfo()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="bg-yellow-100 rounded-full p-2">
                        <i class="fas fa-check text-yellow-400 text-sm"></i>
                    </div>
                    <h4 class="font-semibold text-slate-900">Required Documents</h4>
                </div>
                <ul id="modalDocuments" class="space-y-2 text-sm text-slate-700">
                    <!-- Documents will be populated by JavaScript -->
                </ul>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeServiceInfo()" 
                        class="bg-blue-900 text-white px-6 py-2 rounded-md text-sm hover:bg-blue-800 transition">
                    Got It
                </button>
            </div>
        </div>
    </div>

    <script>
        const serviceInfo = <?php echo json_encode($serviceInfo); ?>;
        
        function showServiceInfo(serviceKey) {
            const info = serviceInfo[serviceKey];
            if (!info) return;
            
            document.getElementById('modalTitle').textContent = info.title;
            const documentsList = document.getElementById('modalDocuments');
            documentsList.innerHTML = '';
            
            info.required_documents.forEach(doc => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-2';
                li.innerHTML = `
                    <span class="text-slate-400 mt-1">•</span>
                    <span>${doc}</span>
                `;
                documentsList.appendChild(li);
            });
            
            document.getElementById('serviceModal').classList.remove('hidden');
        }
        
        function closeServiceInfo() {
            document.getElementById('serviceModal').classList.add('hidden');
        }
        
        function removeService(index) {
            if (confirm('Are you sure you want to remove this service?')) {
                // Redirect to remove the service
                window.location.href = 'QueueRequest3.php?remove=1&index=' + index;
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('serviceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeServiceInfo();
            }
        });
        
        // Handle form submission
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // TODO: Add backend processing here
            // For now, just show success message
            alert('Queue request submitted successfully! Your queue number will be generated.');
            
            // TODO: Redirect to success page or show queue number
        });
    </script>
</body>
</html>
