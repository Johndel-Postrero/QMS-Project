<?php
// Working Header Component for SeQueueR
?>
<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Left Section - Branding -->
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center relative">
                <!-- UC Logo -->
                <div class="text-center">
                    <div class="text-white font-bold text-sm">UC</div>
                    <div class="text-xs text-blue-200">university of cebu</div>
                </div>
                <!-- Golden Wreath -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 border-2 border-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-feather-alt text-yellow-400 text-sm"></i>
                    </div>
                </div>
            </div>
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
                <span class="text-blue-600 font-medium">Queue/Transaction</span>
            </a>
            
            <!-- History - Active/Inactive -->
            <a href="History.php" class="flex items-center space-x-2 px-4 py-2 rounded-md transition-colors" id="historyTab">
                <i class="fas fa-history"></i>
                <span class="font-medium">History</span>
            </a>
        </div>

        <!-- Right Section - User Profile -->
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <img alt="User profile picture" class="w-10 h-10 rounded-full object-cover" src="https://placehold.co/40x40/png?text=User"/>
            </div>
            <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
        </div>
    </div>
</header>

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
    });
</script>
