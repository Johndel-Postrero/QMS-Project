<?php
// Admin Header Component for SeQueueR
?>
<header class="bg-white border-b border-gray-200 py-4">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
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
            <!-- Dashboard - Active/Inactive -->
            <a href="Dashboard.php" class="flex items-center space-x-2 px-4 py-2 rounded-md transition-colors" id="dashboardTab">
                <i class="fas fa-home"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <!-- Queue Management - Active/Inactive -->
            <a href="Queue.php" class="flex items-center space-x-2 px-4 py-2 rounded-md transition-colors" id="queueTab">
                <i class="fas fa-clipboard-list"></i>
                <span class="font-medium">Queue Management</span>
            </a>
            
            <!-- Queue History - Active/Inactive -->
            <a href="History.php" class="flex items-center space-x-2 px-4 py-2 rounded-md transition-colors" id="historyTab">
                <i class="fas fa-history"></i>
                <span class="font-medium">Queue History</span>
            </a>
            
            <!-- Account Management - Active/Inactive -->
            <a href="User.php" class="flex items-center space-x-2 px-4 py-2 rounded-md transition-colors" id="userTab">
                <i class="fas fa-user-plus"></i>
                <span class="font-medium">Account Management</span>
            </a>
        </div>

        <!-- Right Section - User Profile -->
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <img alt="User profile picture" class="w-10 h-10 rounded-full object-cover" src="https://placehold.co/40x40/png?text=Admin"/>
            </div>
            <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
        </div>
    </div>
</header>

<script>
    // Highlight active tab based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const dashboardTab = document.getElementById('dashboardTab');
        const queueTab = document.getElementById('queueTab');
        const historyTab = document.getElementById('historyTab');
        const userTab = document.getElementById('userTab');
        
        // Reset all tabs to inactive state
        const tabs = [dashboardTab, queueTab, historyTab, userTab];
        tabs.forEach(tab => {
            tab.className = 'flex items-center space-x-2 px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors';
        });
        
        // Highlight active tab based on current page
        if (currentPage === 'Dashboard.php') {
            dashboardTab.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600';
            dashboardTab.innerHTML = '<i class="fas fa-home text-blue-600"></i><span class="text-blue-600 font-medium">Dashboard</span>';
        } else if (currentPage === 'Queue.php') {
            queueTab.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600';
            queueTab.innerHTML = '<i class="fas fa-clipboard-list text-blue-600"></i><span class="text-blue-600 font-medium">Queue Management</span>';
        } else if (currentPage === 'History.php') {
            historyTab.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600';
            historyTab.innerHTML = '<i class="fas fa-history text-blue-600"></i><span class="text-blue-600 font-medium">Queue History</span>';
        } else if (currentPage === 'User.php') {
            userTab.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-md border-b-2 border-blue-600';
            userTab.innerHTML = '<i class="fas fa-user-plus text-blue-600"></i><span class="text-blue-600 font-medium">Account Management</span>';
        }
    });
</script>
