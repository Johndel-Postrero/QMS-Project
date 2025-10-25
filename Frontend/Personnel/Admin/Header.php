<?php
// Admin Header Component for SeQueueR
?>
<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between py-3 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <!-- Logo and Application Name Section -->
            <div class="flex items-center space-x-4">
                <!-- University Seal Logo -->
                <img alt="University of Cebu Student Affairs circular seal" class="h-12 w-12 rounded-full object-cover" src="../../../sao-nobg.png"/>
                
                <!-- Application Name -->
                <div class="leading-tight">
                    <h1 class="text-blue-900 font-bold text-xl -mb-1">SeQueueR</h1>
                    <p class="text-gray-600 text-sm">UC Student Affairs</p>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <nav class="flex items-center space-x-1">
                <!-- Dashboard Tab -->
                <a href="Dashboard.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors" id="dashboardTab">
                    <i class="fas fa-home nav-icon text-gray-600"></i>
                    <span class="nav-text text-gray-600 font-medium">Dashboard</span>
                </a>
                
                <!-- Queue Management Tab -->
                <a href="Queue.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors" id="queueTab">
                    <i class="fas fa-clipboard-list nav-icon text-gray-600"></i>
                    <span class="nav-text text-gray-600 font-medium">Queue Management</span>
                </a>
                
                <!-- Queue History Tab -->
                <a href="History.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors" id="historyTab">
                    <i class="fas fa-history nav-icon text-gray-600"></i>
                    <span class="nav-text text-gray-600 font-medium">Queue History</span>
                </a>
                
                <!-- Account Management Tab -->
                <a href="User.php" class="nav-tab flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors" id="userTab">
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
                         class="w-10 h-10 rounded-full border-2 border-gray-200 cursor-pointer hover:border-blue-500 transition-colors"
                         onclick="toggleUserDropdown()">
                </div>
                
                <!-- Dropdown Arrow -->
                <button id="userDropdownBtn" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="toggleUserDropdown()">
                    <i class="fas fa-chevron-down text-sm transition-transform" id="profileArrow"></i>
                </button>
            </div>
    </div>
</header>

<!-- User Dropdown Menu (Hidden by default) -->
<div id="userDropdown" class="absolute right-6 top-16 w-64 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
    <div class="p-3">
        <button onclick="window.location.href='Settings.php'" class="w-full flex items-center space-x-3 px-4 py-3 text-left text-gray-700 bg-blue-100 hover:bg-blue-200 transition rounded-lg mb-2">
            <i class="fas fa-cog text-xl"></i>
            <span class="font-medium text-lg">Settings</span>
        </button>
        <button onclick="showLogoutModal()" class="w-full flex items-center space-x-3 px-4 py-3 text-left text-gray-700 hover:bg-gray-100 transition rounded-lg">
            <i class="fas fa-sign-out-alt text-xl"></i>
            <span class="font-medium text-lg">Logout</span>
        </button>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Logout Confirmation</h3>
        <p class="text-gray-600 text-center mb-6">Are you sure you want to Logout?</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeLogoutModal()" 
                    class="border border-blue-500 text-blue-500 rounded-md text-sm hover:bg-blue-50 transition font-medium" 
                    style="padding: 8px 24px; width: 120px; height: 36px;">
                No
            </button>
            <button type="button" onclick="confirmLogout()" 
                    class="bg-blue-900 text-white rounded-md text-sm hover:bg-blue-800 transition font-medium" 
                    style="padding: 8px 24px; width: 120px; height: 36px;">
                Yes
            </button>
        </div>
    </div>
</div>

<style>
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
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const dropdownBtn = document.getElementById('userDropdownBtn');
            const profileImage = document.getElementById('userProfileImage');
            
            // Check if click is outside the dropdown, button, and profile image
            if (!dropdown.contains(event.target) && 
                !dropdownBtn.contains(event.target) && 
                event.target !== profileImage &&
                !event.target.closest('#userDropdownBtn') &&
                !event.target.closest('#userProfileImage')) {
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
    function toggleUserDropdown(event) {
        if (event) {
            event.stopPropagation();
        }
        
        const dropdown = document.getElementById('userDropdown');
        const arrow = document.getElementById('profileArrow');
        
        if (dropdown.classList.contains('hidden')) {
            openUserDropdown();
            arrow.style.transform = 'rotate(180deg)';
        } else {
            closeUserDropdown();
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    
    // Make function globally available
    window.toggleUserDropdown = toggleUserDropdown;
    
    // Open user dropdown
    function openUserDropdown() {
        document.getElementById('userDropdown').classList.remove('hidden');
    }
    
    // Close user dropdown
    function closeUserDropdown() {
        document.getElementById('userDropdown').classList.add('hidden');
        document.getElementById('profileArrow').style.transform = 'rotate(0deg)';
    }
    
    // Show logout modal
    function showLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        // Close dropdown
        document.getElementById('userDropdown').classList.add('hidden');
        document.getElementById('profileArrow').style.transform = 'rotate(0deg)';
    }
    
    // Close logout modal
    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
    
    // Confirm logout
    function confirmLogout() {
        // TODO: Add logout logic here (clear session, redirect to login page, etc.)
        // For now, redirect to signin page
        window.location.href = '../Signin.php';
    }
    
    // Make functions globally available
    window.showLogoutModal = showLogoutModal;
    window.closeLogoutModal = closeLogoutModal;
    window.confirmLogout = confirmLogout;
    
    // Auto-refresh user profile every 5 minutes
    setInterval(loadUserProfile, 300000);
</script>