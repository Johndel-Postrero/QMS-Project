<?php 
// TV Board - Now Serving and Next in Queue (Backend Ready)
// TODO: Add backend integration for live queue data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SeQueueR TV Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .tv-counter-number {
            font-size: 2.75rem;
        }
        @media (min-width: 768px) {
            .tv-counter-number {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col" style="background-image: url('Assests/QueueReqPic.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <?php include __DIR__ . '/TvHeader.php'; ?>

    <main class="flex-grow mt-8">
        <div class="px-6 md:px-10 mx-16 md:mx-32 lg:mx-48 py-6 md:py-10">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left: Now Serving -->
                <div class="lg:flex-[7]">
                    <div class="bg-blue-900 text-white rounded-2xl shadow-lg px-6 py-4 mb-6">
                        <h2 class="text-center text-3xl md:text-4xl font-extrabold tracking-wider">NOW SERVING</h2>
                    </div>

                    <!-- Counter 1 -->
                    <div class="flex items-stretch gap-0 mb-9 rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-blue-900 text-white px-20 py-10 flex items-center justify-center">
                            <span class="text-xl md:text-2xl font-extrabold tracking-wide">COUNTER 1</span>
                        </div>
                        <div class="flex-1 bg-white border-2 border-blue-900 rounded-r-2xl px-8 py-10 flex items-center justify-center">
                            <span class="text-gray-300 font-bold text-3xl md:text-4xl">--</span>
                        </div>
                    </div>

                    <!-- Counter 2 -->
                    <div class="flex items-stretch gap-0 mb-9 rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-blue-900 text-white px-20 py-10 flex items-center justify-center">
                            <span class="text-xl md:text-2xl font-extrabold tracking-wide">COUNTER 2</span>
                        </div>
                        <div class="flex-1 bg-white border-2 border-blue-900 rounded-r-2xl px-8 py-10 flex items-center justify-center">
                            <span class="text-gray-300 font-bold text-3xl md:text-4xl">--</span>
                        </div>
                    </div>

                    <!-- Counter 3 -->
                    <div class="flex items-stretch gap-0 mb-9 rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-blue-900 text-white px-20 py-10 flex items-center justify-center">
                            <span class="text-xl md:text-2xl font-extrabold tracking-wide">COUNTER 3</span>
                        </div>
                        <div class="flex-1 bg-white border-2 border-blue-900 rounded-r-2xl px-8 py-10 flex items-center justify-center">
                            <span class="text-gray-300 font-bold text-3xl md:text-4xl">--</span>
                        </div>
                    </div>

                    <!-- Counter 4 -->
                    <div class="flex items-stretch gap-0 mb-9 rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-blue-900 text-white px-20 py-10 flex items-center justify-center">
                            <span class="text-xl md:text-2xl font-extrabold tracking-wide">COUNTER 4</span>
                        </div>
                        <div class="flex-1 bg-white border-2 border-blue-900 rounded-r-2xl px-8 py-10 flex items-center justify-center">
                            <span class="text-gray-300 font-bold text-3xl md:text-4xl">--</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Next in Queue -->
                <aside class="lg:flex-[2.5] w-full self-stretch">
                    <div class="rounded-2xl shadow-lg overflow-hidden w-full max-w-[280px] bg-white ml-auto h-[calc(100%-25px)] flex flex-col">
                        <div class="bg-blue-900 text-white px-5 py-4">
                            <h3 class="text-2xl md:text-3xl font-extrabold tracking-wider text-center">NEXT IN QUEUE</h3>
                        </div>
                        <div class="p-6 space-y-5 flex-1">
                            <!-- Empty slots ready for backend data -->
                            <div class="bg-gray-100 rounded-xl px-3 h-[60px] flex items-center justify-center w-[180px] mx-auto">
                                <span class="text-gray-400 font-bold text-lg">--</span>
                            </div>
                            <div class="bg-gray-100 rounded-xl px-3 h-[60px] flex items-center justify-center w-[180px] mx-auto">
                                <span class="text-gray-400 font-bold text-lg">--</span>
                            </div>
                            <div class="bg-gray-100 rounded-xl px-3 h-[60px] flex items-center justify-center w-[180px] mx-auto">
                                <span class="text-gray-400 font-bold text-lg">--</span>
                            </div>
                            <div class="bg-gray-100 rounded-xl px-3 h-[60px] flex items-center justify-center w-[180px] mx-auto">
                                <span class="text-gray-400 font-bold text-lg">--</span>
                            </div>
                            <div class="bg-gray-100 rounded-xl px-3 h-[60px] flex items-center justify-center w-[180px] mx-auto">
                                <span class="text-gray-400 font-bold text-lg">--</span>
                            </div>
                            <div class="bg-gray-100 rounded-xl px-3 h-[60px] flex items-center justify-center w-[180px] mx-auto">
                                <span class="text-gray-400 font-bold text-lg">--</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/TvFooter.php'; ?>

    <script>
        // Optional: Auto-refresh every 30 seconds when backend is integrated
        // setInterval(function() { location.reload(); }, 30000);
    </script>
</body>
</html>
