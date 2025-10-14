<?php
// Backend-ready structure - ready for future implementation
session_start();

// Get data from session
$studentData = [
    'full_name' => $_SESSION['fullname'] ?? '',
    'student_id' => $_SESSION['studentid'] ?? '',
    'year_level' => $_SESSION['yearlevel'] ?? '',
    'course_program' => $_SESSION['courseprogram'] ?? ''
];

$selectedServices = $_SESSION['selected_services'] ?? [];
$priorityGroup = $_SESSION['priority_group'] ?? 'no';
$generateQr = $_SESSION['generate_qr'] ?? 'no';

// TODO: Generate queue number (backend implementation)
// For now, generate a sample queue number
$queueNumber = 'R-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

// TODO: Generate QR code (backend implementation)
// For now, create a placeholder QR code URL
$qrCodeData = "https://qms.uc.edu.ph/queue/" . $queueNumber;
$qrCodeImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrCodeData);

// TODO: Store queue data in database
// TODO: Send email with QR code if requested
// TODO: Log queue generation

// Clear session data after successful generation
unset($_SESSION['fullname'], $_SESSION['studentid'], $_SESSION['yearlevel'], 
      $_SESSION['courseprogram'], $_SESSION['selected_services'], 
      $_SESSION['priority_group'], $_SESSION['generate_qr']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Queue Number Generated - SeQueueR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .success-animation {
            animation: bounceIn 0.6s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .fade-in {
            animation: fadeInUp 0.8s ease-out;
        }
        @keyframes fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-r from-white via-slate-200 to-sky-500">
    <?php include 'Header.php'; ?>
    
    <main class="flex-grow flex items-center justify-center px-4 py-10">
        <div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-8 text-center" style="box-shadow: 0 8px 24px rgb(0 0 0 / 0.1);">
            <!-- Success Icon -->
            <div class="flex justify-center mb-6">
                <div class="bg-yellow-100 rounded-full p-4 success-animation">
                    <i class="fas fa-check text-blue-600 text-2xl"></i>
                </div>
            </div>
            
            <!-- Success Message -->
            <h2 class="text-blue-900 font-extrabold text-xl mb-2 fade-in">
                Your queue number has been successfully generated!
            </h2>
            
            <!-- Divider -->
            <hr class="border-slate-200 mb-6"/>
            
            <!-- Queue Number -->
            <div class="mb-6 fade-in">
                <h3 class="text-blue-900 font-bold text-lg mb-2">Your Queue Number</h3>
                <div class="text-4xl font-black text-slate-900 mb-4">
                    <?php echo htmlspecialchars($queueNumber); ?>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="mb-6 fade-in">
                <div class="bg-white border-2 border-slate-200 rounded-lg p-4 inline-block">
                    <img src="<?php echo htmlspecialchars($qrCodeImageUrl); ?>" 
                         alt="QR Code for Queue <?php echo htmlspecialchars($queueNumber); ?>" 
                         class="w-48 h-48 mx-auto"/>
                </div>
                <p class="text-slate-600 text-sm mt-3">Scan to track your queue status</p>
            </div>
            
            <!-- Queue Details -->
            <div class="bg-slate-50 rounded-lg p-4 mb-6 text-left fade-in">
                <h4 class="text-blue-900 font-semibold text-sm mb-3">Queue Details</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Student:</span>
                        <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($studentData['full_name']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">ID:</span>
                        <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($studentData['student_id']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Services:</span>
                        <span class="text-slate-900 font-medium"><?php echo count($selectedServices); ?> selected</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Priority:</span>
                        <span class="text-slate-900 font-medium"><?php echo $priorityGroup === 'yes' ? 'Priority' : 'Regular'; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 fade-in">
                <button onclick="window.location.href='Landing.php'" 
                        class="w-full bg-blue-900 text-white rounded-md py-3 text-sm hover:bg-blue-800 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i>
                    Finish
                </button>
            </div>
        </div>
    </main>
    
    <?php include '../Footer.php'; ?>

    <script>
        // Auto-refresh queue status (optional)
        function checkQueueStatus() {
            // TODO: Implement AJAX call to check queue status
            // This would be used to update the queue status in real-time
            console.log('Checking queue status for: <?php echo $queueNumber; ?>');
        }
        
        // Optional: Set up periodic status checking
        // setInterval(checkQueueStatus, 30000); // Check every 30 seconds
    </script>
</body>
</html>
