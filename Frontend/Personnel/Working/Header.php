<?php
// Working Header Component for SeQueueR
?>
<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between py-3 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
        <!-- Left Section - Branding -->
        <div class="flex items-center space-x-3">
            <img alt="University of Cebu Student Affairs circular seal" class="h-12 w-12 rounded-full object-cover" src="../../../sao-nobg.png"/>
            <div class="leading-tight">
                <h1 class="text-blue-900 font-bold text-lg">SeQueueR</h1>
                <p class="text-gray-600 text-xs">UC Student Affairs</p>
            </div>
        </div>

        <!-- Center Section - Navigation -->
        <div class="flex items-center space-x-8">
            <!-- Queue/Transaction - Active -->
            <a href="Queue.php" class="flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                <span class="text-blue-600 font-medium">Queue Management</span>
            </a>
            
            <!-- History - Active/Inactive -->
            <a href="History.php" class="flex items-center space-x-2 px-4 py-2 rounded-md transition-colors" id="historyTab">
                <i class="fas fa-history"></i>
                <span class="font-medium">Queue History</span>
            </a>
        </div>

        <!-- Right Section - User Profile -->
        <div class="relative">
            <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 focus:outline-none">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <img alt="User profile picture" class="w-10 h-10 rounded-full object-cover" src="https://placehold.co/40x40/png?text=User"/>
                </div>
                <i class="fas fa-chevron-down text-gray-600 text-sm transition-transform" id="profileArrow"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                <div class="p-3">
                    <button onclick="window.location.href='#'" class="w-full flex items-center space-x-3 px-4 py-3 text-left text-gray-700 bg-blue-100 hover:bg-blue-200 transition rounded-lg mb-2">
                        <i class="fas fa-cog text-xl"></i>
                        <span class="font-medium text-lg">Settings</span>
                    </button>
                    <button onclick="showLogoutModal()" class="w-full flex items-center space-x-3 px-4 py-3 text-left text-gray-700 hover:bg-gray-100 transition rounded-lg">
                        <i class="fas fa-sign-out-alt text-xl"></i>
                        <span class="font-medium text-lg">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

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

<script>
    // Highlight active tab based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const queueTab = document.querySelector('a[href="Queue.php"]');
        const historyTab = document.getElementById('historyTab');
        
        if (currentPage === 'History.php') {
            // Highlight History tab
            historyTab.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600';
            historyTab.innerHTML = '<i class="fas fa-history text-blue-600"></i><span class="text-blue-600 font-medium">History</span>';
            
            // Remove highlight from Queue tab
            queueTab.className = 'flex items-center space-x-2 px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors';
            queueTab.innerHTML = '<i class="fas fa-clipboard-list text-gray-500"></i><span class="font-medium">Queue/Transaction</span>';
        } else {
            // Default: Highlight Queue tab (for Queue.php or other pages)
            queueTab.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600';
            queueTab.innerHTML = '<i class="fas fa-clipboard-list text-blue-600"></i><span class="text-blue-600 font-medium">Queue/Transaction</span>';
            
            // Remove highlight from History tab
            historyTab.className = 'flex items-center space-x-2 px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors';
            historyTab.innerHTML = '<i class="fas fa-history text-gray-500"></i><span class="font-medium">History</span>';
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileButton = event.target.closest('button[onclick="toggleProfileDropdown()"]');
            
            if (!profileButton && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
                document.getElementById('profileArrow').style.transform = 'rotate(0deg)';
            }
        });
    });
    
    // Toggle profile dropdown
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('profileArrow');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    
    // Show logout modal
    function showLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        // Close dropdown
        document.getElementById('profileDropdown').classList.add('hidden');
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
</script>
