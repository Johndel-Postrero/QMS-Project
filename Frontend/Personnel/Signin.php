<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>SeQueueR Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-700">
    <?php include 'LoginHeader.php'; ?>
    <main class="flex-grow flex justify-center items-center bg-gradient-to-r from-white via-slate-300 to-slate-600 relative overflow-hidden py-12">
        <img alt="University of Cebu campus buildings with modern architecture, blue sky, and trees, faded and tinted blue as background" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover opacity-70 pointer-events-none select-none" src="https://placehold.co/1920x1080/png?text=University+of+Cebu+Campus+Buildings+Background"/>
        <form aria-label="SeQueueR Login Form" class="relative bg-white rounded-lg shadow-md max-w-xl w-full p-10 space-y-6">
            <div class="flex justify-center">
                <div class="bg-yellow-400 rounded-full p-4">
                    <i class="fas fa-user-graduate text-blue-700 text-xl"></i>
                </div>
            </div>
            <h2 class="text-center text-blue-700 font-extrabold text-xl">SeQueueR Login</h2>
            <p class="text-center text-gray-600 text-sm">Sign in to your queue management dashboard</p>
            <div>
                <label class="block text-blue-700 font-semibold text-sm mb-1" for="username">User Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-blue-700">
                        <i class="fas fa-id-badge"></i>
                    </span>
                    <input autocomplete="username" class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-3 text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" id="username" name="username" placeholder="e.g., WS2024-001" type="text"/>
                </div>
            </div>
            <div>
                <label class="block text-blue-700 font-semibold text-sm mb-1" for="password">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-blue-700">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input autocomplete="current-password" class="w-full border border-gray-300 rounded-md py-2 pl-10 pr-10 text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" id="password" name="password" placeholder="Enter your password" type="password"/>
                    <span aria-label="Show password" class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer" onclick="togglePassword()">
                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <input class="w-4 h-4 border border-gray-400 rounded-sm text-blue-600 focus:ring-blue-500" id="remember" name="remember" type="checkbox"/>
                <label class="text-sm text-gray-700 select-none" for="remember">Remember me</label>
            </div>
            <p class="text-xs text-gray-500">Only use on trusted office computers</p>
            <button class="w-full bg-blue-900 text-white font-medium rounded-md py-3 mt-2 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700" type="submit">Login</button>
            <div class="flex justify-end">
                <a class="text-blue-600 text-sm hover:underline" href="ForgotPassword.php">Forgot Password?</a>
            </div>
            <p class="text-center text-xs text-gray-600">First time login? Check with supervisor</p>
        </form>
    </main>
    <!-- Include Footer -->
    <?php include '../Footer.php'; ?>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>