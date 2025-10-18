<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SeQueueR</title>
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
<body class="bg-white">
    <!-- Admin Header -->
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo and Application Name Section -->
                <div class="flex items-center space-x-4">
                    <!-- University Seal Logo -->
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center shadow-md">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-university text-blue-600 text-lg"></i>
                        </div>
                    </div>
                    
                    <!-- Application Name -->
                    <div class="leading-tight">
                        <h1 class="text-blue-900 font-bold text-xl -mb-1">SeQueueR</h1>
                        <p class="text-gray-600 text-sm">UC Student Affairs</p>
                    </div>
                </div>
                
                <!-- Navigation Tabs -->
                <nav class="flex items-center space-x-1">
                    <!-- Dashboard Tab -->
                    <a href="Dashboard.php" class="nav-tab active flex items-center space-x-2 px-4 py-2 rounded-lg">
                        <i class="fas fa-home nav-icon text-blue-600"></i>
                        <span class="nav-text text-blue-600 font-medium">Dashboard</span>
                    </a>
                    
                    <!-- Queue Management Tab -->
                    <a href="Queue.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg">
                        <i class="fas fa-clipboard-list nav-icon text-gray-600"></i>
                        <span class="nav-text text-gray-600 font-medium">Queue Management</span>
                    </a>
                    
                    <!-- Queue History Tab -->
                    <a href="History.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg">
                        <i class="fas fa-history nav-icon text-gray-600"></i>
                        <span class="nav-text text-gray-600 font-medium">Queue History</span>
                    </a>
                    
                    <!-- Account Management Tab -->
                    <a href="User.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg">
                        <i class="fas fa-user-plus nav-icon text-gray-600"></i>
                        <span class="nav-text text-gray-600 font-medium">Account Management</span>
                    </a>
                </nav>
                
                <!-- User Profile Section -->
                <div class="flex items-center space-x-3">
                    <!-- User Profile Picture -->
                    <div class="relative">
                        <img id="userProfileImage" 
                             src="https://placehold.co/40x40/4f46e5/ffffff?text=U" 
                             alt="User Profile" 
                             class="w-10 h-10 rounded-full border-2 border-gray-200 cursor-pointer hover:border-blue-500 transition-colors">
                    </div>
                    
                    <!-- Dropdown Arrow -->
                    <button id="userDropdownBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- User Dropdown Menu (Hidden by default) -->
    <div id="userDropdown" class="absolute right-6 top-16 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
        <div class="py-2">
            <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-900" id="userName">Admin User</p>
                <p class="text-xs text-gray-500" id="userRole">Administrator</p>
            </div>
            <div class="py-1">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                    <i class="fas fa-user text-gray-400"></i>
                    <span>Profile</span>
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                    <i class="fas fa-cog text-gray-400"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                    <i class="fas fa-question-circle text-gray-400"></i>
                    <span>Help</span>
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="../Signin.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2">
                    <i class="fas fa-sign-out-alt text-red-500"></i>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Backend-ready JavaScript for Admin Header
        let currentUser = null;
        
        // Initialize the header
        document.addEventListener('DOMContentLoaded', function() {
            loadUserProfile();
            setupEventListeners();
            updateActiveTab();
        });
        
        // Load user profile from backend
        function loadUserProfile() {
            // TODO: Replace with actual API call
            fetch('/api/admin/user/profile')
                .then(response => response.json())
                .then(data => {
                    currentUser = data;
                    updateUserDisplay();
                })
                .catch(error => {
                    console.log('No backend connection yet - using default user');
                    // Default user data when no backend
                    currentUser = {
                        name: 'Admin User',
                        role: 'Administrator',
                        profileImage: 'https://placehold.co/40x40/4f46e5/ffffff?text=U'
                    };
                    updateUserDisplay();
                });
        }
        
        // Update user display elements
        function updateUserDisplay() {
            if (currentUser) {
                document.getElementById('userName').textContent = currentUser.name || 'Admin User';
                document.getElementById('userRole').textContent = currentUser.role || 'Administrator';
                document.getElementById('userProfileImage').src = currentUser.profileImage || 'https://placehold.co/40x40/4f46e5/ffffff?text=U';
            }
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // User dropdown toggle
            document.getElementById('userDropdownBtn').addEventListener('click', toggleUserDropdown);
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('userDropdown');
                const dropdownBtn = document.getElementById('userDropdownBtn');
                
                if (!dropdown.contains(event.target) && !dropdownBtn.contains(event.target)) {
                    closeUserDropdown();
                }
            });
            
            // Navigation tab click handlers
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    // Remove active class from all tabs
                    document.querySelectorAll('.nav-tab').forEach(t => {
                        t.classList.remove('active');
                        t.querySelector('.nav-icon').classList.remove('text-blue-600');
                        t.querySelector('.nav-icon').classList.add('text-gray-600');
                        t.querySelector('.nav-text').classList.remove('text-blue-600');
                        t.querySelector('.nav-text').classList.add('text-gray-600');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    this.querySelector('.nav-icon').classList.remove('text-gray-600');
                    this.querySelector('.nav-icon').classList.add('text-blue-600');
                    this.querySelector('.nav-text').classList.remove('text-gray-600');
                    this.querySelector('.nav-text').classList.add('text-blue-600');
                });
            });
        }
        
        // Update active tab based on current page
        function updateActiveTab() {
            const currentPage = window.location.pathname.split('/').pop();
            const tabMap = {
                'Dashboard.php': 'Dashboard.php',
                'Queue.php': 'Queue.php',
                'History.php': 'History.php',
                'User.php': 'User.php'
            };
            
            // Remove active class from all tabs
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
                tab.querySelector('.nav-icon').classList.remove('text-blue-600');
                tab.querySelector('.nav-icon').classList.add('text-gray-600');
                tab.querySelector('.nav-text').classList.remove('text-blue-600');
                tab.querySelector('.nav-text').classList.add('text-gray-600');
            });
            
            // Add active class to current page tab
            const activeTab = document.querySelector(`a[href="${currentPage}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
                activeTab.querySelector('.nav-icon').classList.remove('text-gray-600');
                activeTab.querySelector('.nav-icon').classList.add('text-blue-600');
                activeTab.querySelector('.nav-text').classList.remove('text-gray-600');
                activeTab.querySelector('.nav-text').classList.add('text-blue-600');
            }
        }
        
        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown.classList.contains('hidden')) {
                openUserDropdown();
            } else {
                closeUserDropdown();
            }
        }
        
        // Open user dropdown
        function openUserDropdown() {
            document.getElementById('userDropdown').classList.remove('hidden');
        }
        
        // Close user dropdown
        function closeUserDropdown() {
            document.getElementById('userDropdown').classList.add('hidden');
        }
        
        // Auto-refresh user profile every 5 minutes
        setInterval(loadUserProfile, 300000);
    </script>
</body>
</html>
