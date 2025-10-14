<?php
session_start();

$courses = [
    'BS Computer Science',
    'BS Information Technology', 
    'BS Business Administration',
    'BS Accountancy',
    'BS Psychology',
    'BS Education',
    'BS Nursing',
    'BS Engineering',
    'BS Architecture',
    'BS Tourism',
    'BS Criminology',
    'BS Psychology',
    'BS Social Work',
    'BS Hotel and Restaurant Management',
    'BS Tourism Management'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store student data in session
    $_SESSION['fullname'] = $_POST['fullname'] ?? '';
    $_SESSION['studentid'] = $_POST['studentid'] ?? '';
    $_SESSION['yearlevel'] = $_POST['yearlevel'] ?? '';
    $_SESSION['courseprogram'] = $_POST['courseprogram'] ?? '';
    
    // Redirect to Step 2
    header('Location: QueueRequest2.php');
    exit;
}

// TODO: Handle AJAX course search when implementing backend
if (isset($_GET['action']) && $_GET['action'] === 'search_courses') {
    $searchTerm = $_GET['q'] ?? '';
    $filteredCourses = array_filter($courses, function($course) use ($searchTerm) {
        return stripos($course, $searchTerm) !== false;
    });
    header('Content-Type: application/json');
    echo json_encode(array_values($filteredCourses));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>SeQueueR Request Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Custom scrollbar for dropdown */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #facc15 #e5e7eb;
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #e5e7eb;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #facc15;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-r from-white via-slate-200 to-sky-500">
    <?php include 'Header.php'; ?>

    <main class="flex-grow flex items-center justify-center px-4 py-10">
        <form action="QueueRequest.php" aria-label="Request Your Queue Number Form" class="bg-white rounded-lg shadow-lg max-w-xl w-full p-8" method="POST" novalidate="">
            <div class="flex justify-center mb-6">
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-ticket-alt text-yellow-400 text-xl"></i>
                </div>
            </div>
            <h2 class="text-blue-900 font-extrabold text-xl text-center mb-2">Request Your Queue Number</h2>
            <p class="text-center text-slate-600 mb-6 text-sm">Please provide the following information to get your queue number</p>
            
            <div class="flex items-center justify-between text-xs md:text-sm mb-4">
                <span class="font-semibold text-blue-900">
                    Step 1 of 3
                </span>
                <span class="text-gray-500">
                    Service Request
                </span>
            </div>
            <div class="w-full h-1 rounded-full bg-slate-300 mb-6 relative">
                <div class="h-1 rounded-full bg-yellow-400 w-[33%]"></div>
            </div>
            <hr class="border-slate-200 mb-6"/>
            
            <h3 class="text-blue-900 font-semibold mb-4 text-sm">Student Information</h3>
            
            <label class="block mb-1 text-sm font-normal text-slate-900" for="fullname">
                Full Name <span class="text-red-600">*</span>
            </label>
            <input autocomplete="name" class="w-full mb-6 px-3 py-2 border border-slate-300 rounded-md text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
                   id="fullname" name="fullname" placeholder="Enter your complete name (Last, First, Middle)" 
                   required="" type="text"/>
            
            <div class="flex flex-col sm:flex-row sm:space-x-6 mb-6">
                <div class="flex-1">
                    <label class="block mb-1 text-sm font-normal text-slate-900" for="studentid">
                        Student ID Number <span class="text-red-600">*</span>
                    </label>
                    <input autocomplete="student-id" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
                           id="studentid" name="studentid" placeholder="e.g., 21411277" 
                           required="" type="text" pattern="[0-9]{8}" maxlength="8"/>
                    <p class="text-xs text-slate-500 mt-1">Enter your official university ID number (8 digits)</p>
                </div>
                <div class="flex-1 mt-4 sm:mt-0">
                    <label class="block mb-1 text-sm font-normal text-slate-900" for="yearlevel">
                        Year Level <span class="text-red-600">*</span>
                    </label>
                    <select class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm text-black focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
                            id="yearlevel" name="yearlevel" required="">
                        <option disabled="" selected="" value="">Select year level</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                        <option value="5th Year">5th Year</option>
                    </select>
                </div>
            </div>
            
            <label class="block mb-1 text-sm font-normal text-slate-900" for="courseSearch">
                Course/Program <span class="text-red-600">*</span>
            </label>
            <div class="relative mb-8">
                <input aria-label="Search Course/Program" autocomplete="off" 
                       class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
                       id="courseSearch" placeholder="Type to search Course/Program" type="text"/>
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500">
                     <i class="fas fa-chevron-down"></i>
                </div>
                <ul class="absolute z-10 w-full max-h-48 mt-1 overflow-auto bg-white border border-slate-300 rounded-md shadow-lg scrollbar-thin hidden" 
                    id="courseDropdown" role="listbox" tabindex="-1">
                    <!-- Options will be populated by JavaScript -->
                </ul>
                <input id="courseprogram" name="courseprogram" required="" type="hidden"/>
            </div>
            
            <div class="flex justify-center" style="gap: 70px;">
                <button class="flex items-center gap-2 border border-slate-300 rounded-md py-3 text-slate-700 text-sm hover:bg-slate-100 transition" 
                        style="padding-left: 60px; padding-right: 60px;"
                        onclick="window.location.href='Landing.php'" type="button">
                    <i class="fas fa-home text-sm"></i>
                    Home
                </button>
                    <button class="bg-blue-900 text-white rounded-md py-3 text-sm hover:bg-blue-800 transition flex items-center justify-center gap-2" 
                            style="padding-left: 65px; padding-right: 65px;"
                            type="submit">
                        Next
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button>
            </div>
        </form>
    </main>

    <?php include '../Footer.php'; ?>

    <script>
        (() => {
            const input = document.getElementById('courseSearch');
            const dropdown = document.getElementById('courseDropdown');
            const hiddenInput = document.getElementById('courseprogram');
            let courses = <?php echo json_encode($courses); ?>;
            let filteredCourses = [...courses];
            let focusedIndex = -1;
            let searchTimeout;

            // Populate dropdown with courses
            function populateDropdown(coursesToShow) {
                dropdown.innerHTML = '';
                if (coursesToShow.length === 0) {
                    dropdown.innerHTML = '<li class="px-3 py-2 text-slate-500 cursor-default select-none">No results found</li>';
                    return;
                }
                
                coursesToShow.forEach(course => {
                    const li = document.createElement('li');
                    li.className = 'cursor-pointer px-3 py-2 hover:bg-yellow-100';
                    li.setAttribute('data-value', course);
                    li.setAttribute('role', 'option');
                    li.setAttribute('tabindex', '0');
                    li.textContent = course;
                    
                    li.addEventListener('click', () => selectOption(li));
                    li.addEventListener('mouseenter', () => {
                        clearFocus();
                        li.classList.add('bg-yellow-200');
                        focusedIndex = coursesToShow.indexOf(course);
                    });
                    
                    dropdown.appendChild(li);
                });
            }

            // Toggle dropdown visibility
            function toggleDropdown(show) {
                if (show) {
                    dropdown.classList.remove('hidden');
                    input.setAttribute('aria-expanded', 'true');
                    populateDropdown(filteredCourses);
                } else {
                    dropdown.classList.add('hidden');
                    input.setAttribute('aria-expanded', 'false');
                    focusedIndex = -1;
                }
            }

            // Clear focus from all options
            function clearFocus() {
                dropdown.querySelectorAll('li').forEach(li => {
                    li.classList.remove('bg-yellow-200');
                });
            }

            // Filter courses based on search term
            function filterCourses(searchTerm) {
                const term = searchTerm.toLowerCase();
                filteredCourses = courses.filter(course => 
                    course.toLowerCase().includes(term)
                );
                populateDropdown(filteredCourses);
            }

            // Select option
            function selectOption(option) {
                if (option.classList.contains('cursor-default')) return;
                input.value = option.textContent;
                hiddenInput.value = option.getAttribute('data-value');
                toggleDropdown(false);
                focusedIndex = -1;
            }

            // Search courses with debouncing
            function searchCourses(searchTerm) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (searchTerm.length > 0) {
                        // You can implement AJAX search here if needed
                        filterCourses(searchTerm);
                    } else {
                        filteredCourses = [...courses];
                        populateDropdown(filteredCourses);
                    }
                }, 150);
            }

            // Event listeners
            input.addEventListener('click', () => {
                toggleDropdown(true);
            });

            input.addEventListener('input', (e) => {
                const value = e.target.value;
                if (value.length > 0) {
                    toggleDropdown(true);
                    searchCourses(value);
                } else {
                    hiddenInput.value = '';
                    toggleDropdown(false);
                }
            });

            input.addEventListener('keydown', (e) => {
                const visibleOptions = Array.from(dropdown.querySelectorAll('li:not(.cursor-default)'));
                
                if (dropdown.classList.contains('hidden')) {
                    if (e.key === 'ArrowDown' || e.key === 'Enter') {
                        e.preventDefault();
                        toggleDropdown(true);
                    }
                    return;
                }

                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        focusedIndex = (focusedIndex + 1) % visibleOptions.length;
                        clearFocus();
                        visibleOptions[focusedIndex].classList.add('bg-yellow-200');
                        visibleOptions[focusedIndex].scrollIntoView({ block: 'nearest' });
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        focusedIndex = (focusedIndex - 1 + visibleOptions.length) % visibleOptions.length;
                        clearFocus();
                        visibleOptions[focusedIndex].classList.add('bg-yellow-200');
                        visibleOptions[focusedIndex].scrollIntoView({ block: 'nearest' });
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (focusedIndex >= 0 && visibleOptions[focusedIndex]) {
                            selectOption(visibleOptions[focusedIndex]);
                        }
                        break;
                    case 'Escape':
                        toggleDropdown(false);
                        break;
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    toggleDropdown(false);
                }
            });

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!hiddenInput.value) {
                        e.preventDefault();
                        alert('Please select a course/program');
                        input.focus();
                        return false;
                    }
                });
            }
        })();
    </script>
</body>
</html>