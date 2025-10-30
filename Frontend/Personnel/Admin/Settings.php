<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SeQueueR Admin</title>
    <link rel="icon" type="image/png" href="/Frontend/favicon.php">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include Admin Header -->
    <?php include 'Header.php'; ?>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        <div class="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
            <!-- Settings Container -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <!-- Queue Limits Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Queue Limits</h2>
                    <p class="text-gray-600 mb-6">
                        Set the maximum number of active queue entries across all services. This helps manage workload and prevents system overload.
                    </p>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Maximum Queue Capacity
                        </label>
                        <input type="number" 
                               id="maxQueueCapacity" 
                               class="w-48 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="100"
                               min="1"
                               max="500">
                        <div class="flex items-start mt-3 text-sm text-gray-600">
                            <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                            <span>Total number of students that can queue simultaneously across all services.</span>
                        </div>
                    </div>
                </div>
                
                <!-- Timeout Settings Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Timeout Settings</h2>
                    <p class="text-gray-600 mb-6">
                        Configure automatic timeout durations for different queue states to maintain system efficiency.
                    </p>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 space-y-6">
                        <!-- Auto-Cancel Skipped Queues -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Auto-Cancel Skipped Queues After
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="number" 
                                       id="skipTimeout" 
                                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="1"
                                       min="1"
                                       max="24">
                                <select id="skipTimeoutUnit" 
                                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white pr-10"
                                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.25rem;">
                                    <option value="minutes">Minute(s)</option>
                                    <option value="hours" selected>Hour(s)</option>
                                </select>
                            </div>
                            <div class="flex items-start mt-3 text-sm text-gray-600">
                                <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                                <span>Skipped queues that remain unattended will be automatically cancelled after this duration. (SRS Requirement 3.1.5.4)</span>
                            </div>
                        </div>
                        
                        <!-- Session Inactivity Timeout -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Session Inactivity Timeout
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="number" 
                                       id="sessionTimeout" 
                                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="30"
                                       min="5"
                                       max="120">
                                <span class="text-gray-700">minutes</span>
                            </div>
                            <div class="flex items-start mt-3 text-sm text-gray-600">
                                <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                                <span>Working Scholars will be automatically logged out after this period of inactivity for security purposes.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <button type="button" 
                            onclick="resetToDefaults()" 
                            class="flex items-center space-x-2 px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        <i class="fas fa-redo-alt"></i>
                        <span>Reset to Default Values</span>
                    </button>
                    
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                onclick="cancelChanges()" 
                                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="button" 
                                onclick="saveChanges()" 
                                class="flex items-center space-x-2 px-6 py-2.5 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                            <i class="fas fa-check"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Include Footer -->
    <?php include '../../Footer.php'; ?>
    
    <script>
        // Backend-ready JavaScript for Settings
        let originalSettings = {};
        let currentSettings = {};
        
        // Default values
        const defaultSettings = {
            maxQueueCapacity: 100,
            skipTimeout: 1,
            skipTimeoutUnit: 'hours',
            sessionTimeout: 30
        };
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
        });
        
        // Load settings from backend
        function loadSettings() {
            // TODO: Replace with actual API call
            fetch('/api/admin/settings')
                .then(response => response.json())
                .then(data => {
                    originalSettings = { ...data };
                    currentSettings = { ...data };
                    updateFormFields();
                })
                .catch(error => {
                    console.log('No backend connection yet - using default settings');
                    // Use default settings when no backend
                    originalSettings = { ...defaultSettings };
                    currentSettings = { ...defaultSettings };
                    updateFormFields();
                });
        }
        
        // Update form fields with current settings
        function updateFormFields() {
            document.getElementById('maxQueueCapacity').value = currentSettings.maxQueueCapacity || defaultSettings.maxQueueCapacity;
            document.getElementById('skipTimeout').value = currentSettings.skipTimeout || defaultSettings.skipTimeout;
            document.getElementById('skipTimeoutUnit').value = currentSettings.skipTimeoutUnit || defaultSettings.skipTimeoutUnit;
            document.getElementById('sessionTimeout').value = currentSettings.sessionTimeout || defaultSettings.sessionTimeout;
        }
        
        // Get current form values
        function getCurrentFormValues() {
            return {
                maxQueueCapacity: parseInt(document.getElementById('maxQueueCapacity').value),
                skipTimeout: parseInt(document.getElementById('skipTimeout').value),
                skipTimeoutUnit: document.getElementById('skipTimeoutUnit').value,
                sessionTimeout: parseInt(document.getElementById('sessionTimeout').value)
            };
        }
        
        // Save changes
        function saveChanges() {
            const formData = getCurrentFormValues();
            
            // Validate inputs
            if (formData.maxQueueCapacity < 1 || formData.maxQueueCapacity > 500) {
                alert('Maximum Queue Capacity must be between 1 and 500.');
                return;
            }
            
            if (formData.skipTimeout < 1 || formData.skipTimeout > 24) {
                alert('Skip timeout must be between 1 and 24.');
                return;
            }
            
            if (formData.sessionTimeout < 5 || formData.sessionTimeout > 120) {
                alert('Session timeout must be between 5 and 120 minutes.');
                return;
            }
            
            // TODO: Send data to backend
            console.log('Saving settings:', formData);
            
            // Simulate API call
            fetch('/api/admin/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                alert('Settings saved successfully!');
                originalSettings = { ...formData };
                currentSettings = { ...formData };
            })
            .catch((error) => {
                console.error('Error:', error);
                // For demo purposes, show success message
                alert('Settings saved successfully! (Demo mode - backend not connected)');
                originalSettings = { ...formData };
                currentSettings = { ...formData };
            });
        }
        
        // Reset to default values
        function resetToDefaults() {
            if (confirm('Are you sure you want to reset all settings to their default values?')) {
                currentSettings = { ...defaultSettings };
                updateFormFields();
                console.log('Settings reset to defaults');
            }
        }
        
        // Cancel changes
        function cancelChanges() {
            const formData = getCurrentFormValues();
            const hasChanges = JSON.stringify(formData) !== JSON.stringify(originalSettings);
            
            if (hasChanges) {
                if (confirm('You have unsaved changes. Are you sure you want to cancel?')) {
                    currentSettings = { ...originalSettings };
                    updateFormFields();
                    window.history.back();
                }
            } else {
                window.history.back();
            }
        }
        
        // Warn user about unsaved changes when leaving page
        window.addEventListener('beforeunload', function (e) {
            const formData = getCurrentFormValues();
            const hasChanges = JSON.stringify(formData) !== JSON.stringify(originalSettings);
            
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        // Track changes in real-time
        document.getElementById('maxQueueCapacity')?.addEventListener('change', function() {
            currentSettings.maxQueueCapacity = parseInt(this.value);
        });
        
        document.getElementById('skipTimeout')?.addEventListener('change', function() {
            currentSettings.skipTimeout = parseInt(this.value);
        });
        
        document.getElementById('skipTimeoutUnit')?.addEventListener('change', function() {
            currentSettings.skipTimeoutUnit = this.value;
        });
        
        document.getElementById('sessionTimeout')?.addEventListener('change', function() {
            currentSettings.sessionTimeout = parseInt(this.value);
        });
    </script>
</body>
</html>

