<?php
// Example queue data - Replace with actual database query
$queueNumber = "R-01";  // or "P-01" for priority
$isPriority = (strpos($queueNumber, 'P-') === 0); // Check if it starts with 'P-'
$position = 3;
$peopleAhead = 2;

// Set colors based on queue type
if ($isPriority) {
    $bgGradient = "from-orange-100 to-red-200";
    $textColor = "text-red-700";
    $positionColor = "text-red-900";
} else {
    $bgGradient = "from-blue-100 to-blue-200";
    $textColor = "text-blue-700";
    $positionColor = "text-blue-900";
}
?>

<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   SeQueueR
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: 'Poppins', sans-serif;
    }
    .queue-card {
      background: white;
      max-width: 400px;
      margin: 0 auto;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
  </style>
 </head>
 <body class="bg-white text-gray-700 flex flex-col min-h-screen">
    <!-- Header Component -->
    <?php include 'Header.php'; ?>
   <main class="flex-grow flex items-start justify-center pt-12 pb-20" style="background-image: linear-gradient(135deg, rgba(227, 242, 253, 0.3) 0%, rgba(225, 233, 240, 0.3) 20%, rgba(223, 227, 238, 0.3) 40%, rgba(212, 217, 232, 0.3) 60%, rgba(200, 208, 224, 0.3) 80%, rgba(188, 199, 216, 0.3) 100%), url('../Assests/Phone Background.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
     <div class="w-full max-w-md px-4">
       
       <!-- Notification Alert -->
       <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded-lg flex items-start gap-3 mb-5">
         <i class="fas fa-bell text-green-600 text-xl mt-1"></i>
         <div class="text-green-800 text-sm font-medium leading-relaxed">
           Your turn is approaching! Please prepare your documents.
         </div>
       </div>

       <!-- Queue Card -->
       <div class="queue-card w-full">
         <!-- Content Section -->
         <div class="px-5 py-6">
         
         <!-- Queue Number Section -->
         <div class="mb-6">
           <h3 class="text-gray-600 text-xs font-semibold text-center mb-3 tracking-wider flex items-center justify-center gap-2">
             YOUR QUEUE NUMBER
             <?php if ($isPriority): ?>
               <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full font-bold">PRIORITY</span>
             <?php endif; ?>
           </h3>
           <div class="bg-gradient-to-br <?php echo $bgGradient; ?> rounded-xl py-8 text-center">
             <div class="text-6xl font-bold <?php echo $textColor; ?>"><?php echo $queueNumber; ?></div>
           </div>
         </div>

         <!-- Position Section -->
         <div class="mb-6">
           <h3 class="text-gray-600 text-xs font-semibold text-center mb-3 tracking-wider">YOUR POSITION</h3>
           <div class="text-center">
             <div class="flex items-center justify-center gap-2">
               <span class="text-6xl font-bold <?php echo $positionColor; ?>"><?php echo $position; ?></span>
               <span class="text-lg text-gray-600">in line</span>
             </div>
             <div class="flex items-center justify-center gap-2 mt-4 text-gray-600 text-sm">
               <i class="fas fa-users"></i>
               <span><?php echo $peopleAhead; ?> people ahead of you</span>
             </div>
           </div>
         </div>

         <!-- Buttons -->
         <div class="space-y-3 mt-8">
           <button onclick="refreshStatus()" class="w-full py-3 px-4 border-2 <?php echo $isPriority ? 'border-red-700 text-red-700 hover:bg-red-700' : 'border-blue-700 text-blue-700 hover:bg-blue-700'; ?> font-semibold rounded-lg flex items-center justify-center gap-2 hover:text-white transition">
             <i class="fas fa-sync-alt"></i>
             Refresh Status
           </button>
           <button onclick="cancelQueue()" class="w-full py-3 px-4 border-2 border-red-600 text-red-600 font-semibold rounded-lg flex items-center justify-center gap-2 hover:bg-red-600 hover:text-white transition">
             <i class="fas fa-times-circle"></i>
             Cancel My Queue
           </button>
         </div>

       </div>
     </div>
     </div>
   </main>

   <script>
     function refreshStatus() {
       location.reload();
     }

     function cancelQueue() {
       if (confirm('Are you sure you want to cancel your queue?')) {
         alert('Queue cancelled');
         window.location.href = 'Landing.php';
       }
     }

     // Auto-refresh every 30 seconds
     setInterval(function() {
       console.log('Auto-checking status...');
     }, 30000);
   </script>
  <!-- Footer Component -->
  <?php include 'Footer.php'; ?>
 </body>
</html>

