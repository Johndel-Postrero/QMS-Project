<?php
// Main Landing page for SeQueueR - Admin and Working
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeQueueR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Include Working Header -->
    <?php include 'Header.php'; ?>
    <main class="bg-[#00447a] text-white flex items-center justify-center min-h-screen">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between px-6 py-20 gap-12 w-full">
            <!-- Left Content -->
            <div class="flex-1 space-y-8 max-w-lg">
                <h1 class="text-6xl font-bold leading-tight">
                    Welcome to<br>
                    <span class="text-yellow-400">SeQueueR</span>
                </h1>
                <p class="text-2xl font-normal leading-relaxed">
                    Smart Queue Management for University of Cebu Student Affairs Services
                </p>
                <p class="text-lg font-normal leading-relaxed opacity-90">
                    Skip the long lines. Get your queue number instantly and track your turn in real-time. Make your student affairs visits more efficient and stress-free.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="bg-yellow-400 text-black font-semibold px-8 py-4 rounded-lg shadow-lg flex items-center justify-center gap-3 hover:bg-yellow-300 transition-colors text-lg">
                        <i class="fas fa-ticket-alt"></i>
                        Manage Queue
                    </button>
                    <button class="border-2 border-white text-white font-semibold px-8 py-4 rounded-lg flex items-center justify-center gap-3 hover:bg-white hover:text-[#00447a] transition-colors text-lg">
                        <i class="fas fa-clock"></i>
                        About SeQueueR
                    </button>
                </div>
            </div>
            
            <!-- Right Content - Logo -->
            <div class="flex-1 flex justify-center lg:justify-end">
                <div class="relative">
                    <!-- Outer Ring -->
                    <div class="w-96 h-96 rounded-full border-4 border-white flex items-center justify-center">
                        <!-- Inner Circle -->
                        <div class="w-80 h-80 rounded-full bg-[#00447a] flex flex-col items-center justify-center relative">
                            <!-- UC Logo -->
                            <div class="text-center mb-4">
                                <div class="text-4xl font-bold text-blue-300 mb-2">UC</div>
                                <div class="text-sm text-blue-300 font-medium">university of cebu</div>
                            </div>
                            
                            <!-- Golden Wreath -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-32 h-32 border-4 border-yellow-400 rounded-full flex items-center justify-center">
                                    <!-- Feather Pen Icon -->
                                    <i class="fas fa-feather-alt text-yellow-400 text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Outer Text Ring -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-96 h-96 rounded-full flex items-center justify-center">
                            <div class="text-white text-sm font-semibold text-center">
                                <div class="absolute -top-2 left-1/2 transform -translate-x-1/2">
                                    STUDENT AFFAIRS OFFICE
                                </div>
                                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                                    UNIVERSITY OF CEBU-MAIN
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Include Footer -->
    <?php include '../Footer.php'; ?>
</body>
</html>