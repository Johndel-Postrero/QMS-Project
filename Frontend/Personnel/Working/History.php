<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - SeQueueR</title>
    <link rel="icon" type="image/png" href="/Frontend/favicon.php">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include Working Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="bg-gray-50 min-h-screen">
        <div class="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-5 py-5 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Transaction History</h1>
                        <p class="text-gray-600 mt-2">View and manage your past queue transactions</p>
                    </div>
                    <!-- Export Dropdown -->
                    <div class="mt-4 sm:mt-0 relative" id="exportDropdown">
                        <button id="exportBtn" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg flex items-center space-x-2" onclick="toggleExportDropdown(event)">
                            <i class="fas fa-download"></i>
                            <span>Export</span>
                            <i class="fas fa-chevron-down ml-1"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="exportMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-10">
                            <div class="py-2">
                                <div class="px-4 py-2 text-sm font-medium text-gray-700 border-b border-gray-100">
                                    File Type
                                </div>
                                <div class="py-1">
                                    <button onclick="exportToPDF()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-3">
                                        <div class="w-6 h-6 bg-red-500 rounded flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">PDF</span>
                                        </div>
                                        <span>PDF</span>
                                    </button>
                                    <button onclick="exportToExcel()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-3">
                                        <div class="w-6 h-6 bg-green-500 rounded flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">X</span>
                                        </div>
                                        <span>Excel</span>
                                    </button>
                                    <button onclick="exportToCSV()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-3">
                                        <div class="w-6 h-6 bg-blue-500 rounded flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">CSV</span>
                                        </div>
                                        <span>CSV</span>
                                    </button>
                                    <button onclick="exportToWord()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-3">
                                        <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">W</span>
                                        </div>
                                        <span>Word</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 space-y-4 lg:space-y-0">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Search by queue number, student name, or ...">
                        </div>
                    </div>
                    
                    <!-- Filter Dropdowns (pill style with icons and chevrons) -->
                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                        <!-- Date Range -->
                        <div class="relative">
                            <select id="dateRangeFilter" class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-48">
                                <option value="">Select Date Range</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="lastweek">Last Week</option>
                                <option value="lastmonth">Last Month</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="relative">
                            <select id="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-48">
                                <option value="">All Statuses</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-circle text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="relative">
                            <select id="serviceFilter" class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-48">
                                <option value="">All Services</option>
                                <option value="good_moral">Good Moral Certificate</option>
                                <option value="transcript">Transcript Request</option>
                                <option value="certificate">Certificate Request</option>
                                <option value="uniform_exemption">Request for Uniform Exemption</option>
                                <option value="id_validation">ID Validation</option>
                                <option value="scholarship_verification">Scholarship Verification</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file-alt text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        
                        <button id="clearFiltersBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center space-x-2">
                            <i class="fas fa-sync-alt"></i>
                            <span>Clear Filters</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto" style="min-height: 470px;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('queueNumber')">
                                    <div class="flex items-center space-x-1">
                                        <span>Queue Number</span>
                                        <i id="sort-queueNumber" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('studentName')">
                                    <div class="flex items-center space-x-1">
                                        <span>Student Name</span>
                                        <i id="sort-studentName" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('serviceType')">
                                    <div class="flex items-center space-x-1">
                                        <span>Service Type</span>
                                        <i id="sort-serviceType" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('status')">
                                    <div class="flex items-center space-x-1">
                                        <span>Status</span>
                                        <i id="sort-status" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('dateTime')">
                                    <div class="flex items-center space-x-1">
                                        <span>Date & Time</span>
                                        <i id="sort-dateTime" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('waitTime')">
                                    <div class="flex items-center space-x-1">
                                        <span>Wait Time</span>
                                        <i id="sort-waitTime" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('handledBy')">
                                    <div class="flex items-center space-x-1">
                                        <span>Handled By</span>
                                        <i id="sort-handledBy" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="transactionTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 rounded-b-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                            Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalResults">0</span> results
                        </div>
                        <div id="pagination" class="flex items-center space-x-2">
                            <!-- Pagination will be generated dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Include Footer -->
    <?php include '../../Footer.php'; ?>
    
    <script>
        // Backend-ready JavaScript for Transaction History
        let transactions = [];
        let filteredTransactions = [];
        let currentPage = 1;
        let totalPages = 1;
        let totalCount = 0;
        let itemsPerPage = 8;
        let currentSort = { column: '', direction: 'asc' };
        let currentFilters = {
            search: '',
            dateRange: '',
            status: '',
            service: ''
        };
        
        // Initialize the interface
        document.addEventListener('DOMContentLoaded', function() {
            loadTransactionHistory();
            setupEventListeners();
        });
        
        // Load transaction history from backend
        function loadTransactionHistory() {
            const params = new URLSearchParams({
                page: currentPage,
                limit: itemsPerPage,
                sort: currentSort.column,
                direction: currentSort.direction,
                ...currentFilters
            });
            
            // TODO: Replace with actual API call
            fetch(`/api/history/transactions?${params}`)
                .then(response => response.json())
                .then(data => {
                    transactions = data.transactions || [];
                    // Apply client-side filtering as fallback
                    applyClientSideFiltering();
                    totalCount = filteredTransactions.length;
                    totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
                    updateTransactionTable();
                    updatePagination();
                    updateExportButton();
                })
                .catch(error => {
                    console.log('No backend connection yet - no data available');
                    // loadDemoTransactions();
                });
        }
        
        // Update transaction table
        function updateTransactionTable() {
            const tbody = document.getElementById('transactionTableBody');
            
            if (filteredTransactions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-history text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                                <p class="text-gray-500">Try adjusting your filters or search terms.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            // Slice current page items
            const startIndex = (currentPage - 1) * itemsPerPage;
            const pageItems = filteredTransactions.slice(startIndex, startIndex + itemsPerPage);
            
            let rowsHtml = pageItems.map(transaction => {
                const isPriority = transaction.priority === 'priority';
                const numColor = isPriority ? '#DAA520' : '#003366';
                
                // Service icon based on type
                let svcIcon = '';
                const svcType = transaction.serviceType || '';
                if (svcType.includes('Good Moral') || svcType.includes('Certificate')) {
                    svcIcon = '<i class="fas fa-certificate text-yellow-500 mr-2"></i>';
                } else if (svcType.includes('Transcript')) {
                    svcIcon = '<i class="fas fa-file-alt text-blue-500 mr-2"></i>';
                } else if (svcType.includes('ID') || svcType.includes('Validation')) {
                    svcIcon = '<i class="fas fa-id-card text-green-500 mr-2"></i>';
                } else if (svcType.includes('Uniform') || svcType.includes('Exemption')) {
                    svcIcon = '<i class="fas fa-tshirt text-purple-500 mr-2"></i>';
                } else if (svcType.includes('Scholarship')) {
                    svcIcon = '<i class="fas fa-graduation-cap text-indigo-500 mr-2"></i>';
                } else {
                    svcIcon = '<i class="fas fa-certificate text-yellow-500 mr-2"></i>';
                }
                
                // Status (only completed and cancelled)
                let statusInfo;
                if (transaction.status === 'completed') {
                    statusInfo = {class: 'bg-green-100 text-green-800', text: 'Completed'};
                } else {
                    statusInfo = {class: 'bg-red-100 text-red-800', text: 'Cancelled'};
                }
                
                return `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            ${isPriority ? '<i class="fas fa-star mr-2" style="color: #DAA520;"></i>' : ''}
                            <span class="text-sm font-bold" style="color: ${numColor};">${transaction.queueNumber}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">${transaction.studentName}</div>
                            <div class="text-sm text-gray-500">${transaction.studentId}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            ${svcIcon}
                            <span class="text-sm text-gray-900">${transaction.serviceType}</span>
                            ${transaction.additionalServices > 0 ? `<span class="text-xs text-blue-600 ml-1 font-semibold">+${transaction.additionalServices}</span>` : ''}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusInfo.class}">
                            ${statusInfo.text}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>
                            <div>${transaction.dateTime}</div>
                            <div class="text-gray-500">${transaction.time}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${transaction.waitTime || '--'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${transaction.handledBy || 'Unassigned'}
                    </td>
                </tr>`;
            }).join('');
            tbody.innerHTML = rowsHtml;
        }

        // Demo data generator for frontend only
        function loadDemoTransactions() {
            const now = new Date();
            const fmt = (d) => d.toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' });
            const tm = (d) => d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
            const names = ['Maria Santos','Juan Dela Cruz','Ana Dela Cruz','Carlos Reyes','Liza Gomez','Paolo Aquino','Ruth Villanueva','Mark dela Rosa','Jenny Cruz','Victor Lim','Erika Tan','Noel Abad','Grace Uy','Leo Santos','Cathy Ramos','Dino Perez','Olivia Reyes','Kenji Sato','Mina Park','Arman Lee'];
            const services = ['Good Moral Certificate','Transcript Request','Certificate Request','ID Validation','Request for Uniform Exemption','Scholarship Verification'];
            const statuses = ['completed','cancelled']; // Only completed and cancelled for History
            const handlers = ['Juan Miguel Santos','Maria Santos','Carlos Reyes','Ana Garcia'];
            const demo = [];
            for (let i = 0; i < 100; i++) {
                const pri = i % 5 === 0 ? 'priority' : 'regular';
                const num = (i % 5 === 0 ? 'P' : 'R') + '-' + String(i + 1).padStart(3,'0');
                const dt = new Date(now.getTime() - (i * 17 + 5) * 60 * 1000);
                const waitMins = 10 + (i % 40);
                const waitSecs = i % 60;
                demo.push({
                    q: num,
                    pri,
                    name: names[i % names.length],
                    id: '202' + (i%5) + '-' + String(10000 + i),
                    svc: services[i % services.length],
                    more: (i % 3),
                    st: statuses[i % statuses.length],
                    dt,
                    wt: waitMins + ' min ' + waitSecs + ' sec',
                    handledBy: handlers[i % handlers.length]
                });
            }
            transactions = demo.map(x => ({
                queueNumber: x.q,
                priority: x.pri,
                studentName: x.name,
                studentId: x.id,
                serviceType: x.svc,
                additionalServices: x.more,
                status: x.st,
                dateTime: fmt(x.dt),
                time: tm(x.dt),
                waitTime: x.wt,
                handledBy: x.handledBy,
                dateIndex: x.dt.getTime() // Store timestamp for sorting
            }));
            
            // Apply filtering
            applyClientSideFiltering();
            totalCount = filteredTransactions.length;
            totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
            currentPage = 1;
            updateTransactionTable();
            updatePagination();
            updateExportButton();
        }
        
        // Apply client-side filtering
        function applyClientSideFiltering() {
            filteredTransactions = transactions.filter(transaction => {
                // Status filter
                if (currentFilters.status && transaction.status !== currentFilters.status) {
                    return false;
                }
                
                // Service filter
                if (currentFilters.service) {
                    const serviceMap = {
                        'good_moral': 'Good Moral Certificate',
                        'transcript': 'Transcript Request',
                        'certificate': 'Certificate Request',
                        'uniform_exemption': 'Request for Uniform Exemption',
                        'id_validation': 'ID Validation',
                        'scholarship_verification': 'Scholarship Verification'
                    };
                    const expectedService = serviceMap[currentFilters.service];
                    if (!expectedService || !transaction.serviceType.includes(expectedService)) {
                        return false;
                    }
                }
                
                // Date range filter
                if (currentFilters.dateRange && transaction.dateIndex) {
                    const now = new Date();
                    const transDate = new Date(transaction.dateIndex);
                    
                    if (currentFilters.dateRange === 'today') {
                        const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                        if (transDate < todayStart) return false;
                    } else if (currentFilters.dateRange === 'yesterday') {
                        const yesterdayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
                        const yesterdayEnd = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                        if (transDate < yesterdayStart || transDate >= yesterdayEnd) return false;
                    } else if (currentFilters.dateRange === 'lastweek') {
                        const lastWeekStart = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);
                        const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                        if (transDate < weekAgo || transDate >= lastWeekStart) return false;
                    } else if (currentFilters.dateRange === 'lastmonth') {
                        const lastMonthStart = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                        const currentMonthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                        if (transDate < lastMonthStart || transDate >= currentMonthStart) return false;
                    }
                }
                
                // Search filter
                if (currentFilters.search) {
                    const searchLower = currentFilters.search.toLowerCase();
                    const matchesQueue = transaction.queueNumber && transaction.queueNumber.toLowerCase().includes(searchLower);
                    const matchesName = transaction.studentName && transaction.studentName.toLowerCase().includes(searchLower);
                    const matchesId = transaction.studentId && transaction.studentId.toString().toLowerCase().includes(searchLower);
                    if (!matchesQueue && !matchesName && !matchesId) {
                        return false;
                    }
                }
                
                return true;
            });
        }
        
        // Update pagination
        function updatePagination() {
            const pagination = document.getElementById('pagination');
            const showingFrom = document.getElementById('showingFrom');
            const showingTo = document.getElementById('showingTo');
            const totalResults = document.getElementById('totalResults');
            
            const startItem = (currentPage - 1) * itemsPerPage + 1;
            const endItem = Math.min(currentPage * itemsPerPage, totalCount);
            
            showingFrom.textContent = totalCount > 0 ? startItem : 0;
            showingTo.textContent = endItem;
            totalResults.textContent = totalCount;
            
            totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }
            
            let paginationHTML = '';
            
            // Previous button
            if (currentPage > 1) {
                paginationHTML += `
                    <button onclick="changePage(${currentPage - 1})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        < Previous
                    </button>
                `;
            } else {
                paginationHTML += `
                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-300 border border-gray-300 rounded-md cursor-not-allowed">
                        < Previous
                    </button>
                `;
            }
            
            // Compact pages: 1 ... (cp-1, cp, cp+1) ... last
            const cp = currentPage;
            const tp = totalPages;
            // First page
            paginationHTML += `
                <button onclick="changePage(1)" class="px-3 py-2 text-sm font-medium border rounded-md ${cp===1 ? 'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'}">1</button>
            `;
            // Middle window
            let startPage = Math.max(2, cp - 1);
            let endPage = Math.min(tp - 1, cp + 1);
            if (startPage > 2) {
                paginationHTML += `<span class="px-3 py-2 text-sm text-gray-500">...</span>`;
            }
            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <button onclick="changePage(${i})" class="px-3 py-2 text-sm font-medium border rounded-md ${i===cp ? 'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'}">${i}</button>
                `;
            }
            if (endPage < tp - 1) {
                paginationHTML += `<span class="px-3 py-2 text-sm text-gray-500">...</span>`;
            }
            if (tp > 1) {
                paginationHTML += `
                    <button onclick="changePage(${tp})" class="px-3 py-2 text-sm font-medium border rounded-md ${cp===tp ? 'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'}">${tp}</button>
                `;
            }
            
            // Next button
            if (currentPage < totalPages) {
                paginationHTML += `
                    <button onclick="changePage(${currentPage + 1})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Next >
                    </button>
                `;
            } else {
                paginationHTML += `
                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-300 border border-gray-300 rounded-md cursor-not-allowed">
                        Next >
                    </button>
                `;
            }
            
            pagination.innerHTML = paginationHTML;
        }
        
        // Update export button state
        function updateExportButton() {
            const exportBtn = document.getElementById('exportBtn');
            if (transactions.length === 0) {
                exportBtn.classList.add('opacity-50', 'cursor-not-allowed');
                exportBtn.classList.remove('hover:bg-blue-800');
            } else {
                exportBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                exportBtn.classList.add('hover:bg-blue-800');
            }
        }
        
        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('searchInput').addEventListener('input', debounce(handleSearch, 300));
            document.getElementById('dateRangeFilter').addEventListener('change', handleFilterChange);
            document.getElementById('statusFilter').addEventListener('change', handleFilterChange);
            document.getElementById('serviceFilter').addEventListener('change', handleFilterChange);
            document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('exportDropdown');
                if (!dropdown.contains(event.target)) {
                    closeExportDropdown();
                }
            });
        }
        
        // Handle search
        function handleSearch(event) {
            currentFilters.search = event.target.value;
            currentPage = 1;
            
            // Apply filtering and update display
            applyClientSideFiltering();
            totalCount = filteredTransactions.length;
            totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
            updateTransactionTable();
            updatePagination();
            updateExportButton();
        }
        
        // Handle filter changes
        function handleFilterChange(event) {
            const filterType = event.target.id.replace('Filter', '');
            currentFilters[filterType] = event.target.value;
            currentPage = 1;
            
            // Apply filtering and update display
            applyClientSideFiltering();
            totalCount = filteredTransactions.length;
            totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
            updateTransactionTable();
            updatePagination();
            updateExportButton();
        }
        
        // Clear all filters
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('dateRangeFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('serviceFilter').value = '';
            
            currentFilters = {
                search: '',
                dateRange: '',
                status: '',
                service: ''
            };
            
            currentPage = 1;
            
            // Apply filtering and update display
            applyClientSideFiltering();
            totalCount = filteredTransactions.length;
            totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
            updateTransactionTable();
            updatePagination();
            updateExportButton();
        }
        
        // Change page
        function changePage(page) {
            currentPage = Math.max(1, Math.min(page, Math.max(1, Math.ceil(totalCount / itemsPerPage))));
            updateTransactionTable();
            updatePagination();
        }
        
        // Sort table
        function sortTable(column) {
            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.column = column;
                currentSort.direction = 'asc';
            }
            
            // Update sort icons
            document.querySelectorAll('[id^="sort-"]').forEach(icon => {
                icon.className = 'fas fa-sort text-gray-400';
            });
            const sortIcon = document.getElementById('sort-' + column);
            if (sortIcon) {
                sortIcon.className = currentSort.direction === 'asc' 
                    ? 'fas fa-sort-up text-blue-600' 
                    : 'fas fa-sort-down text-blue-600';
            }
            
            // Sort transactions array (sort all, then filter)
            transactions.sort((a, b) => {
                let aVal, bVal;
                
                if (column === 'queueNumber') {
                    aVal = a.queueNumber;
                    bVal = b.queueNumber;
                } else if (column === 'studentName') {
                    aVal = a.studentName;
                    bVal = b.studentName;
                } else if (column === 'serviceType') {
                    aVal = a.serviceType;
                    bVal = b.serviceType;
                } else if (column === 'status') {
                    aVal = a.status;
                    bVal = b.status;
                } else if (column === 'dateTime') {
                    // Convert date string back to timestamp if needed, or use index if stored
                    aVal = a.dateIndex !== undefined ? a.dateIndex : new Date(a.dateTime).getTime();
                    bVal = b.dateIndex !== undefined ? b.dateIndex : new Date(b.dateTime).getTime();
                } else if (column === 'waitTime') {
                    // Extract numeric minutes from "X min Y sec"
                    const aMatch = a.waitTime.match(/(\d+)\s*min/);
                    const bMatch = b.waitTime.match(/(\d+)\s*min/);
                    aVal = aMatch ? parseInt(aMatch[1]) : 0;
                    bVal = bMatch ? parseInt(bMatch[1]) : 0;
                } else if (column === 'handledBy') {
                    aVal = a.handledBy;
                    bVal = b.handledBy;
                }
                
                // Handle null/undefined
                if (aVal === null || aVal === undefined) return 1;
                if (bVal === null || bVal === undefined) return -1;
                
                // Compare
                if (aVal < bVal) return currentSort.direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return currentSort.direction === 'asc' ? 1 : -1;
                return 0;
            });
            
            // Apply filtering and update display
            applyClientSideFiltering();
            totalCount = filteredTransactions.length;
            totalPages = Math.max(1, Math.ceil(totalCount / itemsPerPage));
            currentPage = 1;
            updateTransactionTable();
            updatePagination();
        }
        
        // Toggle export dropdown
        function toggleExportDropdown(event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Don't open dropdown if there are no transactions
            if (transactions.length === 0) {
                console.log('No transactions to export');
                return;
            }
            
            console.log('Export button clicked'); // Debug log
            
            const menu = document.getElementById('exportMenu');
            if (menu.classList.contains('hidden')) {
                openExportDropdown();
            } else {
                closeExportDropdown();
            }
        }
        
        // Open export dropdown
        function openExportDropdown() {
            const menu = document.getElementById('exportMenu');
            menu.classList.remove('hidden');
            console.log('Dropdown opened'); // Debug log
        }
        
        // Close export dropdown
        function closeExportDropdown() {
            const menu = document.getElementById('exportMenu');
            menu.classList.add('hidden');
            console.log('Dropdown closed'); // Debug log
        }
        
        // Export functions (no backend yet)
        function exportToPDF() {
            if (transactions.length === 0) {
                console.log('No transactions to export to PDF');
                return;
            }
            closeExportDropdown();
            console.log('Export to PDF - Backend not implemented yet');
        }
        
        function exportToExcel() {
            if (transactions.length === 0) {
                console.log('No transactions to export to Excel');
                return;
            }
            closeExportDropdown();
            console.log('Export to Excel - Backend not implemented yet');
        }
        
        function exportToCSV() {
            if (transactions.length === 0) {
                console.log('No transactions to export to CSV');
                return;
            }
            closeExportDropdown();
            console.log('Export to CSV - Backend not implemented yet');
        }
        
        function exportToWord() {
            if (transactions.length === 0) {
                console.log('No transactions to export to Word');
                return;
            }
            closeExportDropdown();
            console.log('Export to Word - Backend not implemented yet');
        }
        
        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
    </script>
</body>
</html>