<?php
session_start();
require_once __DIR__ . '/../../Student/db_config.php';
require_once __DIR__ . '/../admin_functions.php';

// Get database connection
$conn = getDBConnection();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

// Filters
$search = $_GET['search'] ?? '';
$dateRange = $_GET['date_range'] ?? '';
$status = $_GET['status'] ?? '';
$service = $_GET['service'] ?? '';

// Build query
$where = ["DATE(q.created_at) <= CURDATE()"];
$params = [];
$types = '';

if ($search) {
    $where[] = "(q.queue_number LIKE ? OR q.student_name LIKE ? OR q.student_id LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'sss';
}

if ($status) {
    $where[] = "q.status = ?";
    $params[] = $status;
    $types .= 's';
}

// Filter by service name if provided
if ($service) {
    $where[] = "EXISTS (SELECT 1 FROM queue_services qs WHERE qs.queue_id = q.id AND qs.service_name = ?)";
    $params[] = $service;
    $types .= 's';
}

if ($dateRange) {
    switch ($dateRange) {
        case 'today':
            $where[] = "DATE(q.created_at) = CURDATE()";
            break;
        case 'yesterday':
            $where[] = "DATE(q.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case 'lastweek':
            $where[] = "DATE(q.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE(q.created_at) < CURDATE()";
            break;
        case 'lastmonth':
            $where[] = "MONTH(q.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(q.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
            break;
    }
}

$whereClause = implode(' AND ', $where);

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM queues q WHERE $whereClause";
if ($types) {
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param($types, ...$params);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
} else {
    $countResult = $conn->query($countQuery);
}
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Get transactions
$query = "
    SELECT 
        q.*,
        GROUP_CONCAT(qs.service_name SEPARATOR ', ') as services
    FROM queues q
    LEFT JOIN queue_services qs ON q.id = qs.queue_id
    WHERE $whereClause
    GROUP BY q.id
    ORDER BY q.created_at DESC
    LIMIT ? OFFSET ?
";

// TODO: When backend has handled_by/personnel_id field, update this query to:
// SELECT q.*, GROUP_CONCAT(qs.service_name SEPARATOR ', ') as services,
//        COALESCE(p.full_name, 'Unassigned') as handled_by
// FROM queues q
// LEFT JOIN queue_services qs ON q.id = qs.queue_id
// LEFT JOIN personnel p ON q.handled_by = p.id
// WHERE $whereClause
// GROUP BY q.id
// ORDER BY q.created_at DESC
// LIMIT ? OFFSET ?

$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
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
    <?php include 'Header.php'; ?>
    
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
            <form method="GET" action="History.php" id="filterForm" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 space-y-4 lg:space-y-0">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($search); ?>" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Search by queue number, student name, or ...">
                        </div>
                    </div>

                    <!-- Filter Dropdowns styled like pills with chevrons -->
                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                        <!-- Date Range -->
                        <div class="relative">
                            <select name="date_range" id="dateRangeFilter" onchange="handleFilterChange(this)" class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-48">
                                <option value="">Select Date Range</option>
                                <option value="today" <?php echo $dateRange === 'today' ? 'selected' : ''; ?>>Today</option>
                                <option value="yesterday" <?php echo $dateRange === 'yesterday' ? 'selected' : ''; ?>>Yesterday</option>
                                <option value="lastweek" <?php echo $dateRange === 'lastweek' ? 'selected' : ''; ?>>Last Week</option>
                                <option value="lastmonth" <?php echo $dateRange === 'lastmonth' ? 'selected' : ''; ?>>Last Month</option>
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
                            <select name="status" id="statusFilter" onchange="handleFilterChange(this)" class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-48">
                                <option value="">All Statuses</option>
                                <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
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
                            <select name="service" id="serviceFilter" onchange="handleFilterChange(this)" class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-48">
                                <option value="">All Services</option>
                                <option value="Good Moral Certificate" <?php echo $service === 'Good Moral Certificate' ? 'selected' : ''; ?>>Good Moral Certificate</option>
                                <option value="Transcript Request" <?php echo $service === 'Transcript Request' ? 'selected' : ''; ?>>Transcript Request</option>
                                <option value="Certificate Request" <?php echo $service === 'Certificate Request' ? 'selected' : ''; ?>>Certificate Request</option>
                                <option value="Request for Uniform Exemption" <?php echo $service === 'Request for Uniform Exemption' ? 'selected' : ''; ?>>Request for Uniform Exemption</option>
                                <option value="ID Validation" <?php echo $service === 'ID Validation' ? 'selected' : ''; ?>>ID Validation</option>
                                <option value="Scholarship Verification" <?php echo $service === 'Scholarship Verification' ? 'selected' : ''; ?>>Scholarship Verification</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file-alt text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button type="button" id="clearFiltersBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center space-x-2">
                            <i class="fas fa-sync-alt"></i>
                            <span>Clear Filters</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Transaction Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto" style="min-height: 470px;">
                    <table class="min-w-full table-fixed divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Queue Number</th>
                                <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Type</th>
                                <th class="w-28 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="w-44 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                                <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wait Time</th>
                                <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Handled By
                                    <i class="fas fa-sort ml-1"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-history text-gray-300 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                                        <p class="text-gray-500">Try adjusting your filters or search terms</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="w-24 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                    <div class="flex items-center">
                                        <?php 
                                        $isPriority = ($transaction['queue_type'] === 'priority');
                                        $numColor = $isPriority ? '#DAA520' : '#003366';
                                        ?>
                                        <?php if ($isPriority): ?>
                                        <i class="fas fa-star mr-2" style="color: #DAA520;"></i>
                                        <?php endif; ?>
                                        <span class="text-sm font-bold" style="color: <?php echo $numColor; ?>;"><?php echo htmlspecialchars($transaction['queue_number']); ?></span>
                                    </div>
                                </td>
                                <td class="w-64 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($transaction['student_name']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($transaction['student_id']); ?></div>
                                </td>
                                <td class="w-64 px-6 py-4 overflow-hidden text-ellipsis">
                                    <div class="text-sm text-gray-900">
                                        <?php 
                                        $services = $transaction['services'] ? explode(', ', $transaction['services']) : [];
                                        if (!empty($services)) {
                                            // Add icon based on service type
                                            $firstService = trim($services[0]);
                                            if (stripos($firstService, 'Good Moral') !== false || stripos($firstService, 'Certificate') !== false) {
                                                echo '<i class="fas fa-certificate text-yellow-500 mr-1"></i> ';
                                            } elseif (stripos($firstService, 'Transcript') !== false) {
                                                echo '<i class="fas fa-file-alt text-blue-500 mr-1"></i> ';
                                            } elseif (stripos($firstService, 'ID') !== false || stripos($firstService, 'Validation') !== false) {
                                                echo '<i class="fas fa-id-card text-green-500 mr-1"></i> ';
                                            } elseif (stripos($firstService, 'Uniform') !== false || stripos($firstService, 'Exemption') !== false) {
                                                echo '<i class="fas fa-tshirt text-purple-500 mr-1"></i> ';
                                            } elseif (stripos($firstService, 'Scholarship') !== false) {
                                                echo '<i class="fas fa-graduation-cap text-indigo-500 mr-1"></i> ';
                                            }
                                            echo htmlspecialchars($firstService);
                                        if (count($services) > 1) {
                                                echo ' <span class="text-xs text-blue-600 font-semibold">+' . (count($services) - 1) . '</span>';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td class="w-28 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis">
                                    <?php
                                    // Only show completed and cancelled in History
                                    $status = strtolower($transaction['status']);
                                    if ($status === 'completed') {
                                        $statusColor = 'bg-green-100 text-green-800';
                                        $statusText = 'Completed';
                                    } elseif ($status === 'skipped' || $status === 'cancelled') {
                                        $statusColor = 'bg-red-100 text-red-800';
                                        $statusText = 'Cancelled';
                                    } else {
                                        // For other statuses, default to cancelled
                                        $statusColor = 'bg-red-100 text-red-800';
                                        $statusText = 'Cancelled';
                                    }
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusColor; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td class="w-44 px-6 py-4 whitespace-nowrap text-sm text-gray-900 overflow-hidden text-ellipsis">
                                    <?php 
                                    $datetime = new DateTime($transaction['created_at']);
                                    echo $datetime->format('M d, Y');
                                    ?>
                                    <div class="text-gray-500"><?php echo $datetime->format('g:i A'); ?></div>
                                </td>
                                <td class="w-24 px-6 py-4 whitespace-nowrap text-sm text-gray-900 overflow-hidden text-ellipsis">
                                    <?php 
                                    if ($transaction['served_at'] && $transaction['created_at']) {
                                        $created = new DateTime($transaction['created_at']);
                                        $served = new DateTime($transaction['served_at']);
                                        $diff = $created->diff($served);
                                        $minutes = ($diff->h * 60) + $diff->i;
                                        $seconds = $diff->s;
                                        echo $minutes . ' min ' . $seconds . ' sec';
                                    } else {
                                        echo '--';
                                    }
                                    ?>
                                </td>
                                <td class="w-32 px-6 py-4 whitespace-nowrap text-sm text-gray-900 overflow-hidden text-ellipsis">
                                    <?php
                                    // TODO: When backend has handled_by/personnel_id field, display actual name
                                    // For now, use demo data or session info
                                    $handlers = ['Juan Miguel Santos', 'Maria Santos', 'Carlos Reyes', 'Ana Garcia'];
                                    echo htmlspecialchars($handlers[array_rand($handlers)]);
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 rounded-b-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                            Showing <?php echo min($offset + 1, $totalRows); ?> to <?php echo min($offset + $limit, $totalRows); ?> of <?php echo $totalRows; ?> results
                        </div>
                        <div class="flex items-center space-x-2">
                            <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&date_range=<?php echo $dateRange; ?>&status=<?php echo $status; ?>" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">&lt; Previous</a>
                            <?php else: ?>
                            <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-300 border border-gray-300 rounded-md cursor-not-allowed">&lt; Previous</span>
                            <?php endif; ?>

                            <?php
                                $tp = (int)$totalPages; $cp = (int)$page;
                                if ($tp >= 1) {
                                    // First page
                                    echo '<a href="?page=1&search='.urlencode($search).'&date_range='.$dateRange.'&status='.$status.'" class="px-3 py-2 text-sm font-medium border rounded-md '.($cp===1?'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50').'">1</a>';
                                    // Middle window (cp-1..cp+1)
                                    $start = max(2, $cp - 1);
                                    $end   = min($tp - 1, $cp + 1);
                                    if ($start > 2) {
                                        echo '<span class="px-3 py-2 text-sm text-gray-500">...</span>';
                                    }
                                    for ($i = $start; $i <= $end; $i++) {
                                        echo '<a href="?page='.$i.'&search='.urlencode($search).'&date_range='.$dateRange.'&status='.$status.'" class="px-3 py-2 text-sm font-medium border rounded-md '.($i===$cp?'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50').'">'.$i.'</a>';
                                    }
                                    if ($end < $tp - 1) {
                                        echo '<span class="px-3 py-2 text-sm text-gray-500">...</span>';
                                    }
                                    if ($tp > 1) {
                                        echo '<a href="?page='.$tp.'&search='.urlencode($search).'&date_range='.$dateRange.'&status='.$status.'" class="px-3 py-2 text-sm font-medium border rounded-md '.($cp===$tp?'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50').'">'.$tp.'</a>';
                                    }
                                }
                            ?>

                            <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&date_range=<?php echo $dateRange; ?>&status=<?php echo $status; ?>" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next &gt;</a>
                            <?php else: ?>
                            <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-300 border border-gray-300 rounded-md cursor-not-allowed">Next &gt;</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- Client-side pagination placeholder for demo mode -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hidden" id="clientPagination">
                <div class="overflow-x-auto" style="min-height: 470px;">
                    <table class="min-w-full table-fixed divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('queue_number')">
                                    <div class="flex items-center space-x-1">
                                        <span>Queue Number</span>
                                        <i id="sort-queue_number" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('student_name')">
                                    <div class="flex items-center space-x-1">
                                        <span>Student Name</span>
                                        <i id="sort-student_name" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('service_type')">
                                    <div class="flex items-center space-x-1">
                                        <span>Service Type</span>
                                        <i id="sort-service_type" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th class="w-28 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('status')">
                                    <div class="flex items-center space-x-1">
                                        <span>Status</span>
                                        <i id="sort-status" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th class="w-44 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('date_time')">
                                    <div class="flex items-center space-x-1">
                                        <span>Date & Time</span>
                                        <i id="sort-date_time" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('wait_time')">
                                    <div class="flex items-center space-x-1">
                                        <span>Wait Time</span>
                                        <i id="sort-wait_time" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                                <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100" onclick="sortTableAdmin('handled_by')">
                                    <div class="flex items-center space-x-1">
                                        <span>Handled By</span>
                                        <i id="sort-handled_by" class="fas fa-sort text-gray-400"></i>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Demo data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 rounded-b-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                            Showing <span id="c_showingFrom">0</span> to <span id="c_showingTo">0</span> of <span id="c_totalResults">0</span> results
                        </div>
                        <div id="c_pagination" class="flex items-center space-x-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../../Footer.php'; ?>
    <script>
        (function(){
            // Demo mode is OFF by default; pass ?demo=1 to enable
            function isEnabled(){ try{ return new URLSearchParams(window.location.search).get('demo')==='1'; }catch(e){ return false; } }
            if(!isEnabled()) return;

            // Hide server-side table container (the one without an id, before the clientPagination div)
            const allTables = document.querySelectorAll('.bg-white.rounded-lg.shadow-sm.border.border-gray-200.overflow-hidden');
            allTables.forEach(table => {
                // Hide the transaction table (the one that comes before clientPagination and doesn't have an id)
                if (!table.id && table.nextElementSibling?.id === 'clientPagination') {
                    table.classList.add('hidden');
                }
            });
            
            const tbody = document.querySelector('#clientPagination table tbody');
            const serverPagWrap = document.querySelector('.flex.flex-col.sm\\:flex-row.sm\\:items-center.sm\\:justify-between.mt-6');
            const clientPag = document.getElementById('clientPagination');
            if(serverPagWrap) serverPagWrap.classList.add('hidden');
            // Hide any other server-side pagers just in case
            document.querySelectorAll('.flex.flex-col.sm\\:flex-row.sm\\:items-center.sm\\:justify-between.mt-6').forEach(el=>el.classList.add('hidden'));
            if(clientPag) clientPag.classList.remove('hidden');

            const itemsPerPage = 8;
            let currentPage = 1;
            let transactions = buildDemo(50);
            let filteredTransactions = [];
            let currentSort = { column: '', direction: 'asc' };
            let currentFilters = {
                search: '',
                dateRange: '',
                status: '',
                service: ''
            };
            
            // Make transactions accessible globally for export functions
            window.transactions = transactions;

            function buildDemo(n){
                const names=['Maria Santos','Juan Dela Cruz','Ana Dela Cruz','Carlos Reyes','Liza Gomez','Paolo Aquino','Ruth Villanueva','Mark dela Rosa','Jenny Cruz','Victor Lim','Erika Tan','Noel Abad','Grace Uy','Leo Santos','Cathy Ramos','Dino Perez','Olivia Reyes','Kenji Sato','Mina Park','Arman Lee'];
                const services=['Good Moral Certificate','Transcript Request','Certificate Request','ID Validation','Request for Uniform Exemption','Scholarship Verification'];
                const statuses=['completed','cancelled']; // Only completed and cancelled for History
                const handlers=['Juan Miguel Santos','Maria Santos','Carlos Reyes','Ana Garcia'];
                const now=Date.now();
                const arr=[];
                for(let i=0;i<n;i++){
                    const priority = i%5===0;
                    // Generate random wait time: 10-50 minutes
                    const waitMins = 10 + (i % 40);
                    const waitSecs = i % 60;
                    arr.push({
                        queue_number: (priority?'P':'R')+'-'+String(i+1).padStart(3,'0'),
                        queue_type: priority?'priority':'regular',
                        student_name: names[i%names.length],
                        student_id: '202'+(i%5)+'-'+String(10000+i),
                        services: services[i%services.length]+(i%3? '' : ', '+services[(i+1)%services.length]),
                        status: statuses[i%statuses.length],
                        created_at: new Date(now - (i*17+5)*60*1000),
                        handled_by: handlers[i%handlers.length],
                        wait_time: waitMins + ' min ' + waitSecs + ' sec'
                    });
                }
                return arr;
            }

            // Apply client-side filtering
            function applyClientSideFiltering() {
                filteredTransactions = transactions.filter(t => {
                    // Status filter (only completed and cancelled for History)
                    if (currentFilters.status) {
                        if (currentFilters.status === 'completed' && t.status !== 'completed') return false;
                        if (currentFilters.status === 'cancelled' && t.status !== 'cancelled') return false;
                    }
                    
                    // Service filter
                    if (currentFilters.service && !t.services.includes(currentFilters.service)) {
                        return false;
                    }
                    
                    // Date range filter
                    if (currentFilters.dateRange) {
                        const now = new Date();
                        const transDate = new Date(t.created_at);
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
                    
                    return true;
                });
            }
            
            function render(){
                const start=(currentPage-1)*itemsPerPage;
                const pageItems=filteredTransactions.slice(start,start+itemsPerPage);
                tbody.innerHTML = pageItems.map(t=>{
                    const dt=new Date(t.created_at);
                    const ymd = dt.toLocaleString(undefined,{month:'short',day:'2-digit',year:'numeric'});
                    const hm = dt.toLocaleTimeString([], {hour:'numeric', minute:'2-digit'});
                    const isPriority = t.queue_type==='priority';
                    const numColor = isPriority ? '#DAA520' : '#003366';
                    const statusInfo = {
                        completed: {class:'bg-green-100 text-green-800', text:'Completed'},
                        cancelled: {class:'bg-red-100 text-red-800', text:'Cancelled'},
                        skipped: {class:'bg-red-100 text-red-800', text:'Cancelled'}
                    }[t.status] || {class:'bg-red-100 text-red-800', text:'Cancelled'};
                    
                    // Parse services for icon
                    const svcArr = t.services ? t.services.split(', ') : [];
                    let svcDisplay = '';
                    if (svcArr.length > 0) {
                        const firstSvc = svcArr[0].trim();
                        let icon = '';
                        if (firstSvc.includes('Good Moral') || firstSvc.includes('Certificate')) {
                            icon = '<i class=\"fas fa-certificate text-yellow-500 mr-1\"></i> ';
                        } else if (firstSvc.includes('Transcript')) {
                            icon = '<i class=\"fas fa-file-alt text-blue-500 mr-1\"></i> ';
                        } else if (firstSvc.includes('ID') || firstSvc.includes('Validation')) {
                            icon = '<i class=\"fas fa-id-card text-green-500 mr-1\"></i> ';
                        } else if (firstSvc.includes('Uniform') || firstSvc.includes('Exemption')) {
                            icon = '<i class=\"fas fa-tshirt text-purple-500 mr-1\"></i> ';
                        } else if (firstSvc.includes('Scholarship')) {
                            icon = '<i class=\"fas fa-graduation-cap text-indigo-500 mr-1\"></i> ';
                        }
                        svcDisplay = icon + firstSvc;
                        if (svcArr.length > 1) {
                            svcDisplay += ' <span class=\"text-xs text-blue-600 font-semibold\">+' + (svcArr.length - 1) + '</span>';
                        }
                    } else {
                        svcDisplay = 'N/A';
                    }
                    
                    return `
                    <tr class=\"hover:bg-gray-50\">
                        <td class=\"w-24 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis\">
                            <div class=\"flex items-center\">${isPriority ? '<i class=\"fas fa-star mr-2\" style=\"color: #DAA520;\"></i>' : ''}<span class=\"text-sm font-bold\" style=\"color: ${numColor};\">${t.queue_number}</span></div>
                        </td>
                        <td class=\"w-64 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis\">
                            <div class=\"text-sm font-medium text-gray-900\">${t.student_name}</div>
                            <div class=\"text-sm text-gray-500\">${t.student_id}</div>
                        </td>
                        <td class=\"w-64 px-6 py-4 overflow-hidden text-ellipsis\"><div class=\"text-sm text-gray-900\">${svcDisplay}</div></td>
                        <td class=\"w-28 px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis\"><span class=\"inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusInfo.class}\">${statusInfo.text}</span></td>
                        <td class=\"w-44 px-6 py-4 whitespace-nowrap text-sm text-gray-900 overflow-hidden text-ellipsis\">${ymd}<div class=\"text-gray-500\">${hm}</div></td>
                        <td class=\"w-24 px-6 py-4 whitespace-nowrap text-sm text-gray-900 overflow-hidden text-ellipsis\">${t.wait_time || '--'}</td>
                        <td class=\"w-32 px-6 py-4 whitespace-nowrap text-sm text-gray-900 overflow-hidden text-ellipsis\">${t.handled_by || 'Unassigned'}</td>
                    </tr>`;
                }).join('');
                renderPagination();
            }

            function renderPagination(){
                const total = filteredTransactions.length;
                const totalPages = Math.max(1, Math.ceil(total / itemsPerPage));
                document.getElementById('c_totalResults').textContent = String(total);
                const from = total ? (currentPage-1)*itemsPerPage + 1 : 0;
                const to = Math.min(currentPage*itemsPerPage, total);
                document.getElementById('c_showingFrom').textContent = String(from);
                document.getElementById('c_showingTo').textContent = String(to);
                const container = document.getElementById('c_pagination');
                let html='';
                if(currentPage>1){
                    html += `<button onclick=\"goto(${currentPage-1})\" class=\"px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50\">&lt; Previous</button>`;
                } else {
                    html += `<button disabled class=\"px-3 py-2 text-sm font-medium text-gray-400 bg-gray-300 border border-gray-300 rounded-md cursor-not-allowed\">&lt; Previous</button>`;
                }
                // Compact window: 1 ... (cp-1, cp, cp+1) ... last
                let start = Math.max(2, currentPage - 1);
                let end   = Math.min(totalPages - 1, currentPage + 1);
                html += `<button onclick=\"goto(1)\" class=\"px-3 py-2 text-sm font-medium border rounded-md ${currentPage===1?'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'}\">1</button>`;
                if(start > 2){ html += `<span class=\"px-3 py-2 text-sm text-gray-500\">...</span>`; }
                for(let i=start;i<=end;i++){
                    html += `<button onclick=\"goto(${i})\" class=\"px-3 py-2 text-sm font-medium border rounded-md ${i===currentPage?'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'}\">${i}</button>`;
                }
                if(end < totalPages-1){ html += `<span class=\"px-3 py-2 text-sm text-gray-500\">...</span>`; }
                if(totalPages>1){ html += `<button onclick=\"goto(${totalPages})\" class=\"px-3 py-2 text-sm font-medium border rounded-md ${currentPage===totalPages?'bg-blue-900 text-white border-blue-900':'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'}\">${totalPages}</button>`; }
                if(currentPage<totalPages){
                    html += `<button onclick=\"goto(${currentPage+1})\" class=\"px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50\">Next &gt;</button>`;
                } else {
                    html += `<button disabled class=\"px-3 py-2 text-sm font-medium text-gray-400 bg-gray-300 border border-gray-300 rounded-md cursor-not-allowed\">Next &gt;</button>`;
                }
                container.innerHTML = html;
            }

            // Sort table function
            window.sortTableAdmin = function(column) {
                // Toggle sort direction if same column
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
                
                // Sort transactions
                transactions.sort((a, b) => {
                    let aVal, bVal;
                    
                    if (column === 'queue_number') {
                        aVal = a.queue_number;
                        bVal = b.queue_number;
                    } else if (column === 'student_name') {
                        aVal = a.student_name;
                        bVal = b.student_name;
                    } else if (column === 'service_type') {
                        aVal = a.services;
                        bVal = b.services;
                    } else if (column === 'status') {
                        aVal = a.status;
                        bVal = b.status;
                    } else if (column === 'date_time') {
                        aVal = new Date(a.created_at).getTime();
                        bVal = new Date(b.created_at).getTime();
                    } else if (column === 'wait_time') {
                        // Extract numeric minutes from "X min Y sec"
                        const aMatch = a.wait_time.match(/(\d+)\s*min/);
                        const bMatch = b.wait_time.match(/(\d+)\s*min/);
                        aVal = aMatch ? parseInt(aMatch[1]) : 0;
                        bVal = bMatch ? parseInt(bMatch[1]) : 0;
                    } else if (column === 'handled_by') {
                        aVal = a.handled_by;
                        bVal = b.handled_by;
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
                currentPage = 1;
                render();
            };

            window.goto = function(p){ currentPage = p; render(); }
            
            // Expose functions and variables for external filter handlers
            window.applyClientSideFiltering = applyClientSideFiltering;
            window.currentFilters = currentFilters;
            window.currentPage = function() { return currentPage; };
            window.setCurrentPage = function(p) { currentPage = p; };
            window.render = render;
            
            // Initial filtering and render
            applyClientSideFiltering();
            render();
        })();
        
        // Toggle export dropdown
        function toggleExportDropdown(event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Don't open dropdown if there are no transactions
            if (window.transactions && window.transactions.length === 0) {
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
            if (window.transactions && window.transactions.length === 0) {
                console.log('No transactions to export to PDF');
                return;
            }
            closeExportDropdown();
            console.log('Export to PDF - Backend not implemented yet');
        }
        
        function exportToExcel() {
            if (window.transactions && window.transactions.length === 0) {
                console.log('No transactions to export to Excel');
                return;
            }
            closeExportDropdown();
            console.log('Export to Excel - Backend not implemented yet');
        }
        
        function exportToCSV() {
            if (window.transactions && window.transactions.length === 0) {
                console.log('No transactions to export to CSV');
                return;
            }
            closeExportDropdown();
            console.log('Export to CSV - Backend not implemented yet');
        }
        
        function exportToWord() {
            if (window.transactions && window.transactions.length === 0) {
                console.log('No transactions to export to Word');
                return;
            }
            closeExportDropdown();
            console.log('Export to Word - Backend not implemented yet');
        }
        
        // Filter handlers for demo mode
        function handleFilterChange(selectElement) {
            const filterType = selectElement.id.replace('Filter', '');
            // Prevent form submission in demo mode
            const form = document.getElementById('filterForm');
            if (form && isDemoMode()) {
                event.preventDefault();
                window.currentFilters[filterType] = selectElement.value;
                window.setCurrentPage(1);
                window.applyClientSideFiltering();
                window.render();
            }
        }
        
        function isDemoMode() {
            try {
                const sp = new URLSearchParams(window.location.search);
                return sp.get('demo') === '1';
            } catch (e) {
                return false;
            }
        }
        
        // Setup filter event listeners for demo mode
        (function setupFilterListeners() {
            function attachListeners() {
                const searchInput = document.getElementById('searchInput');
                const clearBtn = document.getElementById('clearFiltersBtn');
                
                if (searchInput && isDemoMode()) {
                    let searchTimeout;
                    searchInput.addEventListener('input', function(e) {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            window.currentFilters.search = e.target.value.toLowerCase();
                            window.setCurrentPage(1);
                            window.applyClientSideFiltering();
                            window.render();
                        }, 300);
                    });
                }
                
                if (clearBtn && isDemoMode()) {
                    clearBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        document.getElementById('dateRangeFilter').value = '';
                        document.getElementById('statusFilter').value = '';
                        document.getElementById('serviceFilter').value = '';
                        window.currentFilters.search = '';
                        window.currentFilters.dateRange = '';
                        window.currentFilters.status = '';
                        window.currentFilters.service = '';
                        window.setCurrentPage(1);
                        window.applyClientSideFiltering();
                        window.render();
                    });
                }
            }
            
            // Attach listeners when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', attachListeners);
            } else {
                attachListeners();
            }
        })();
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('exportDropdown');
            const menu = document.getElementById('exportMenu');
            if (dropdown && menu && !dropdown.contains(event.target)) {
                closeExportDropdown();
            }
        });
    </script>
</body>
</html>