<?php
// About page for SeQueueR - Queue Management System
// Backend ready with database integration capabilities

// Include database connection if needed
// require_once '../../config/database.php';

// Fetch team members from database (example structure)
$team_members = [
    [
        'id' => 1,
        'name' => 'Lourdyn Niel',
        'full_name' => 'VERDIDA, Lourdyn Niel',
        'role' => 'Project Manager',
        'description' => 'Oversees project planning, coordination, and delivery ensuring all milestones are met.',
        'image' => '../Assests/team/lourdyn.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Euwen Aldrich',
        'full_name' => 'VILLARIN, Euwen Aldrich',
        'role' => 'Lead Programmer',
        'description' => 'Leads the development team and ensures code quality and architecture standards.',
        'image' => '../Assests/team/euwen.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Johndel',
        'full_name' => 'POSTRERO, Johndel',
        'role' => 'Front-End Developer',
        'description' => 'Builds responsive and intuitive interfaces for all system modules.',
        'image' => '../Assests/team/johndel.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Dave',
        'full_name' => 'GULAY, Dave ',
        'role' => 'Backend Developer',
        'description' => 'Handles server-side logic, database management, and API development for the queue management system.',
        'image' => '../Assests/team/maria.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Russel',
        'full_name' => 'GILLERA, Russel Ray',
        'role' => 'Backend Developer',
        'description' => 'Handles server-side logic, database management, and API development for the queue management system.',
        'image' => '../Assests/team/john.jpg'
    ],
    [
        'id' => 6,
        'name' => 'Vincent',
        'full_name' => 'YBAÑEZ, Felix Vincent',
        'role' => 'Lead Designer',
        'description' => 'Leads the design process, ensuring cohesive visuals and user-centered experiences.',
        'image' => '../Assests/team/ana.jpg'
    ],
    [
        'id' => 7,
        'name' => 'JAM',
        'full_name' => 'LUPIAN, John Alfred',
        'role' => 'UI/UX Designer',
        'description' => 'Creates user-friendly designs and ensures optimal user experience across all system interfaces.',
        'image' => '../Assests/team/carlos.jpg'
    ],
    [
        'id' => 8,
        'name' => 'Paul',
        'full_name' => 'ALARBA, Paul',
        'role' => 'Lead Tester',
        'description' => 'Oversees the testing team, ensuring product reliability through systematic test planning and execution.',
        'image' => '../Assests/team/lisa.jpg'
    ],
    [
        'id' => 9,
        'name' => 'Ekoy',
        'full_name' => 'SERVICE, Jerick',
        'role' => 'Tester',
        'description' => 'Conducts software testing to identify bugs and ensure the product meets quality standards.',
        'image' => '../Assests/team/michael.jpg'
    ],
    [
        'id' => 10,
        'name' => 'Erwin',
        'full_name' => 'SEMORIO, Erwin',
        'role' => 'Tester',
        'description' => 'Conducts software testing to identify bugs and ensure the product meets quality standards.',
        'image' => '../Assests/team/sarah.jpg'
    ]
];

// Fetch project statistics from database (example)
$project_stats = [
    'services_supported' => 6,
    'user_types' => 3,
    'transaction_states' => 6
];

// Include header
include 'Header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About SeQueueR - UC Student Affairs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'uc-blue': '#1e3a8a',
                        'uc-yellow': '#fbbf24'
                    }
                }
            }
        }
    </script>
    <style>
        .carousel-container {
            overflow: hidden;
        }
        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;
        }
        .carousel-slide {
            flex-shrink: 0;
            width: 33.333%;
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            background-color: #1e3a8a;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Hero Section -->
    <section class="bg-uc-blue text-white py-20">
        <div class="px-6 md:px-10 mx-20 md:mx-34 lg:mx-44 text-center">
            <h1 class="text-5xl font-bold mb-4">About SeQueueR</h1>
            <p class="text-xl mb-6">Streamlining Student Affairs Services Through Innovation</p>
            <p class="text-lg max-w-3xl mx-auto">
                A modern Queue Management System designed to enhance efficiency, transparency, and accessibility for the UC Student Affairs and Services Office.
            </p>
        </div>
    </section>

    <!-- The Project Section -->
    <section class="py-16 bg-white">
        <div class="px-6 md:px-10 mx-20 md:mx-34 lg:mx-44">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-uc-blue mb-8 flex items-center">
                        <div class="w-1 h-16 bg-uc-yellow mr-4"></div>
                        The Project
                    </h2>
                    <div class="space-y-6 text-uc-blue">
                        <p class="text-lg leading-relaxed">
                            SeQueuer is a comprehensive Queue Management System (QMS) developed as part of the Testing & Quality Assurance (TESQUA) course. The system addresses the operational challenges faced by the Student Affairs and Services (SAS) Office in managing daily student transactions.
                        </p>
                        <p class="text-lg leading-relaxed">
                            By digitizing the queuing process, SeQueuer eliminates long wait times, improves transparency, and provides real-time visibility for both students and staff. The system supports six core services ranging from Good Moral Certificate requests to enrollment transfers.
                        </p>
                        <p class="text-lg leading-relaxed">
                            Built on a robust web-based architecture using PHP and MySQL, SeQueueR demonstrates industry-standard software development practices while serving as a practical solution for campus operations.
                        </p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-gray-100 p-8 rounded-lg text-center">
                        <div class="text-6xl font-bold text-uc-blue mb-2"><?php echo $project_stats['services_supported']; ?></div>
                        <div class="text-xl text-uc-blue font-semibold">Services Supported</div>
                    </div>
                    <div class="bg-gray-100 p-8 rounded-lg text-center">
                        <div class="text-6xl font-bold text-uc-blue mb-2"><?php echo $project_stats['user_types']; ?></div>
                        <div class="text-xl text-uc-blue font-semibold">User Types</div>
                    </div>
                    <div class="bg-gray-100 p-8 rounded-lg text-center">
                        <div class="text-6xl font-bold text-uc-blue mb-2"><?php echo $project_stats['transaction_states']; ?></div>
                        <div class="text-xl text-uc-blue font-semibold">Transaction States</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="py-16 bg-white">
        <div class="px-6 md:px-10 mx-20 md:mx-34 lg:mx-44">
            <h2 class="text-4xl font-bold text-uc-blue text-center mb-12">Key Features</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: QR Code Integration -->
                <div class="bg-gray-100 p-6 rounded-lg">
                    <div class="feature-icon mb-4">📱</div>
                    <h3 class="text-xl font-bold text-uc-blue mb-3">QR Code Integration</h3>
                    <p class="text-uc-blue">Students can track their queue status in real-time through QR codes, providing visibility into their position and estimated wait time.</p>
                </div>

                <!-- Feature 2: Multi-Service Support -->
                <div class="bg-gray-100 p-6 rounded-lg">
                    <div class="feature-icon mb-4">📋</div>
                    <h3 class="text-xl font-bold text-uc-blue mb-3">Multi-Service Support</h3>
                    <p class="text-uc-blue">Merge multiple service requests into a single queue number with automatic routing to required counters in sequence.</p>
                </div>

                <!-- Feature 3: Priority Queuing -->
                <div class="bg-gray-100 p-6 rounded-lg">
                    <div class="feature-icon mb-4">⭐</div>
                    <h3 class="text-xl font-bold text-uc-blue mb-3">Priority Queuing</h3>
                    <p class="text-uc-blue">Ensures persons with disabilities, pregnant students, and the elderly receive timely assistance while maintaining system fairness.</p>
                </div>

                <!-- Feature 4: Real-Time Notifications -->
                <div class="bg-gray-100 p-6 rounded-lg">
                    <div class="feature-icon mb-4">🔔</div>
                    <h3 class="text-xl font-bold text-uc-blue mb-3">Real-Time Notifications</h3>
                    <p class="text-uc-blue">Audio alerts, screen displays, and vibration notifications keep students informed when their turn is approaching.</p>
                </div>

                <!-- Feature 5: Comprehensive Dashboard -->
                <div class="bg-gray-100 p-6 rounded-lg">
                    <div class="feature-icon mb-4">📊</div>
                    <h3 class="text-xl font-bold text-uc-blue mb-3">Comprehensive Dashboard</h3>
                    <p class="text-uc-blue">Tailored interfaces for Working Scholars and Personnel with tools to manage queues, view analytics, and generate reports.</p>
                </div>

                <!-- Feature 6: Complete Audit Trail -->
                <div class="bg-gray-100 p-6 rounded-lg">
                    <div class="feature-icon mb-4">📄</div>
                    <h3 class="text-xl font-bold text-uc-blue mb-3">Complete Audit Trail</h3>
                    <p class="text-uc-blue">All status changes and transactions are logged automatically for transparency, accountability, and reporting purposes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet The Team Section -->
    <section class="py-12 bg-uc-blue relative">
        <div class="px-6 md:px-10 mx-20 md:mx-34 lg:mx-44">
            <h2 class="text-3xl font-bold text-white text-center mb-3">Meet The Team</h2>
            <p class="text-lg text-white text-center mb-8">Charlie Three Group - IT-TESQUA 31 (MW)</p>
            
            <!-- Carousel Container -->
            <div class="carousel-container relative overflow-hidden">
				<div class="carousel-track flex" id="carouselTrack">
					<?php 
						$visibleCount = 3; // number of cards visible at once (w-1/3)
						$total = count($team_members);
						// Prepend last N clones for seamless previous navigation
						for ($i = $total - $visibleCount; $i < $total; $i++): 
							$member = $team_members[$i];
						?>
					<div class="carousel-slide flex-shrink-0 w-1/3 px-2" data-clone="prepend">
						<div class="bg-white p-6 rounded-lg shadow-lg text-center transform transition-transform duration-300 hover:scale-105">
							<div class="w-24 h-24 mx-auto mb-4 rounded-full border-3 border-uc-yellow overflow-hidden bg-uc-blue">
								<img src="<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>" class="w-full h-full object-cover">
							</div>
							<div class="bg-uc-blue text-white text-xs px-3 py-1 rounded-full inline-block mb-3 uppercase font-medium"><?php echo $member['role']; ?></div>
							<h3 class="text-lg font-bold text-uc-blue mb-1"><?php echo $member['name']; ?></h3>
							<div class="text-sm text-gray-600 mb-3"><?php echo $member['full_name']; ?></div>
							<p class="text-xs text-gray-700 leading-relaxed"><?php echo $member['description']; ?></p>
						</div>
					</div>
					<?php endfor; ?>

					<?php foreach($team_members as $member): ?>
					<div class="carousel-slide flex-shrink-0 w-1/3 px-2">
						<div class="bg-white p-6 rounded-lg shadow-lg text-center transform transition-transform duration-300 hover:scale-105">
							<div class="w-24 h-24 mx-auto mb-4 rounded-full border-3 border-uc-yellow overflow-hidden bg-uc-blue">
								<img src="<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>" class="w-full h-full object-cover">
							</div>
							<div class="bg-uc-blue text-white text-xs px-3 py-1 rounded-full inline-block mb-3 uppercase font-medium"><?php echo $member['role']; ?></div>
							<h3 class="text-lg font-bold text-uc-blue mb-1"><?php echo $member['name']; ?></h3>
							<div class="text-sm text-gray-600 mb-3"><?php echo $member['full_name']; ?></div>
							<p class="text-xs text-gray-700 leading-relaxed"><?php echo $member['description']; ?></p>
						</div>
					</div>
					<?php endforeach; ?>

					<?php 
						// Append first N clones for seamless next navigation
						for ($i = 0; $i < $visibleCount; $i++): 
							$member = $team_members[$i];
						?>
					<div class="carousel-slide flex-shrink-0 w-1/3 px-2" data-clone="append">
						<div class="bg-white p-6 rounded-lg shadow-lg text-center transform transition-transform duration-300 hover:scale-105">
							<div class="w-24 h-24 mx-auto mb-4 rounded-full border-3 border-uc-yellow overflow-hidden bg-uc-blue">
								<img src="<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>" class="w-full h-full object-cover">
							</div>
							<div class="bg-uc-blue text-white text-xs px-3 py-1 rounded-full inline-block mb-3 uppercase font-medium"><?php echo $member['role']; ?></div>
							<h3 class="text-lg font-bold text-uc-blue mb-1"><?php echo $member['name']; ?></h3>
							<div class="text-sm text-gray-600 mb-3"><?php echo $member['full_name']; ?></div>
							<p class="text-xs text-gray-700 leading-relaxed"><?php echo $member['description']; ?></p>
						</div>
					</div>
					<?php endfor; ?>
				</div>
                
                <!-- Navigation Arrows -->
                <button onclick="previousSlide()" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 rounded-full p-2 shadow-lg hover:bg-opacity-100 transition-all z-10">
                    <svg class="w-5 h-5 text-uc-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button onclick="nextSlide(); resetTimer();" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 rounded-full p-2 shadow-lg hover:bg-opacity-100 transition-all z-10">
                    <svg class="w-5 h-5 text-uc-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Carousel Indicators -->
            <div class="flex justify-center mt-6 space-x-2">
                <?php 
                $totalSlides = count($team_members);
                for($i = 0; $i < $totalSlides; $i++): 
                ?>
                <button onclick="goToSlide(<?php echo $i; ?>)" class="w-2 h-2 rounded-full bg-white hover:bg-gray-300 transition-colors" id="indicator-<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../Footer.php'; ?>

    <script>
        const visibleCount = 3; // number of visible cards (w-1/3)
        const originalCount = <?php echo count($team_members); ?>;
        const totalSlides = originalCount + (visibleCount * 2); // includes clones
        let currentSlide = visibleCount; // start after prepended clones
		let slideTimer;
		let isWrapping = false; // prevent multiple wrap handlers
        
        function updateCarousel() {
            const track = document.getElementById('carouselTrack');
            // Move by 33.333% (one card width) for each slide
            track.style.transform = `translateX(-${currentSlide * 33.333}%)`;
            
            // Update indicators
            for(let i = 0; i < originalCount; i++) {
                const indicator = document.getElementById(`indicator-${i}`);
                // Map currentSlide (with clones) to logical index
                const logicalIndex = (currentSlide - visibleCount + originalCount) % originalCount;
                if(i === logicalIndex) {
                    indicator.classList.remove('bg-white');
                    indicator.classList.add('bg-blue-600');
                } else {
                    indicator.classList.remove('bg-blue-600');
                    indicator.classList.add('bg-white');
                }
            }
        }
        
        function resetTimer() {
            clearInterval(slideTimer);
            slideTimer = setInterval(nextSlide, 4000);
        }
        
		function nextSlide() {
			const track = document.getElementById('carouselTrack');
			if (isWrapping) return; // avoid triggering during wrap reset
			currentSlide++;
			updateCarousel();
			// If we've moved into appended clones, wait for transition end then jump to equivalent real slide
			if (currentSlide === totalSlides - visibleCount) {
				isWrapping = true;
				const onEnd = () => {
					track.removeEventListener('transitionend', onEnd);
					track.style.transition = 'none';
					currentSlide = visibleCount; // first real slide index
					track.style.transform = `translateX(-${currentSlide * 33.333}%)`;
					void track.offsetWidth;
					track.style.transition = 'transform 0.5s ease-in-out';
					isWrapping = false;
				};
				track.addEventListener('transitionend', onEnd, { once: true });
			}
		}
        
		function previousSlide() {
			const track = document.getElementById('carouselTrack');
			if (isWrapping) return; // avoid triggering during wrap reset
			currentSlide--;
			updateCarousel();
			// If we've moved into prepended clones, wait for transition end then jump to equivalent real slide
			if (currentSlide === visibleCount - 1) {
				isWrapping = true;
				const onEnd = () => {
					track.removeEventListener('transitionend', onEnd);
					track.style.transition = 'none';
					currentSlide = originalCount + visibleCount - 1; // last real slide index in track
					track.style.transform = `translateX(-${currentSlide * 33.333}%)`;
					void track.offsetWidth;
					track.style.transition = 'transform 0.5s ease-in-out';
					isWrapping = false;
				};
				track.addEventListener('transitionend', onEnd, { once: true });
			}
			resetTimer(); // Reset timer when previous arrow is clicked
		}
        
        function goToSlide(slideIndex) {
            // Map dot index (0..originalCount-1) to real index in track
            currentSlide = slideIndex + visibleCount;
            updateCarousel();
            resetTimer(); // Reset timer when dot is clicked
        }
        
        // Auto-play carousel
        slideTimer = setInterval(nextSlide, 4000);
        
        // Initialize carousel
        // Start positioned after prepended clones
        const trackInit = document.getElementById('carouselTrack');
        trackInit.style.transition = 'none';
        trackInit.style.transform = `translateX(-${currentSlide * 33.333}%)`;
        void trackInit.offsetWidth;
        trackInit.style.transition = 'transform 0.5s ease-in-out';
        updateCarousel();
    </script>
</body>
</html>