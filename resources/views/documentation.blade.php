<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Documentatie - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')
                    colors: {
                        primary: {
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .code-block {
            background-color: #1f2937;
            color: #f9fafb;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            border-radius: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
        }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background-color: #f3f4f6;
            color: #3b82f6;
        }
        .sidebar-link.active {
            background-color: #dbeafe;
            color: #1d4ed8;
            border-right: 3px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">TaskCheck Documentation</span>
            </div>
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">← Back to TaskCheck</a>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm min-h-screen p-6">
            <div class="space-y-2">
                <h3 class="font-semibold text-gray-900 mb-4">Getting Started</h3>
                <a href="#introduction" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700 active" onclick="showSection('introduction')">Introduction</a>
                <a href="#quick-start" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('quick-start')">Quick Start</a>
                <a href="#installation" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('installation')">Installation</a>
                
                <h3 class="font-semibold text-gray-900 mb-4 mt-8">User Guide</h3>
                <a href="#tasks" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('tasks')">Managing Tasks</a>
                <a href="#teams" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('teams')">Team Management</a>
                <a href="#projects" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('projects')">Projects</a>
                <a href="#notifications" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('notifications')">Notifications</a>
                
                <h3 class="font-semibold text-gray-900 mb-4 mt-8">Advanced</h3>
                <a href="#api" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('api')">API Reference</a>
                <a href="#integrations" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('integrations')">Integrations</a>
                <a href="#customization" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('customization')">Customization</a>
                
                <h3 class="font-semibold text-gray-900 mb-4 mt-8">Support</h3>
                <a href="#troubleshooting" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('troubleshooting')">Troubleshooting</a>
                <a href="#faq" class="sidebar-link block px-3 py-2 rounded-lg text-gray-700" onclick="showSection('faq')">FAQ</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Introduction -->
            <div id="introduction" class="section">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Introduction to TaskCheck</h1>
                <p class="text-lg text-gray-600 mb-6">
                    TaskCheck is a powerful task management platform designed to help teams collaborate effectively and stay organized. 
                    This documentation will guide you through all the features and help you get the most out of TaskCheck.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">What is TaskCheck?</h3>
                    <p class="text-blue-800">
                        TaskCheck is a comprehensive task management solution that combines project planning, team collaboration, 
                        and productivity tracking in one intuitive platform. Whether you're managing a small team or a large organization, 
                        TaskCheck scales to meet your needs.
                    </p>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Key Features</h2>
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="font-semibold text-gray-900 mb-2">Task Management</h3>
                        <p class="text-gray-600">Create, assign, and track tasks with advanced filtering and sorting options.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="font-semibold text-gray-900 mb-2">Team Collaboration</h3>
                        <p class="text-gray-600">Work together with real-time updates, comments, and file sharing.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="font-semibold text-gray-900 mb-2">Project Planning</h3>
                        <p class="text-gray-600">Organize tasks into projects with timelines and milestones.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                        <p class="text-gray-600">Track progress and productivity with detailed reports and insights.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Start -->
            <div id="quick-start" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Quick Start Guide</h1>
                <p class="text-lg text-gray-600 mb-8">
                    Get up and running with TaskCheck in just a few minutes. Follow these steps to create your first task and invite your team.
                </p>

                <div class="space-y-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Step 1: Create Your Account</h2>
                        <p class="text-gray-600 mb-4">Sign up for TaskCheck and verify your email address.</p>
                        <div class="code-block text-sm">
# Visit the TaskCheck website
# Click "Get Started" or "Sign Up"
# Enter your email and create a password
# Verify your email address
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Step 2: Create Your First Task</h2>
                        <p class="text-gray-600 mb-4">Learn how to create and manage your first task.</p>
                        <div class="code-block text-sm">
1. Click the "New Task" button
2. Enter a task title and description
3. Set a due date and priority level
4. Assign the task to yourself or a team member
5. Click "Create Task"
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Step 3: Invite Your Team</h2>
                        <p class="text-gray-600 mb-4">Add team members to start collaborating.</p>
                        <div class="code-block text-sm">
1. Go to Settings > Team Management
2. Click "Invite Members"
3. Enter email addresses
4. Choose roles (Admin or Employee)
5. Send invitations
                        </div>
                    </div>
                </div>
            </div>

            <!-- Installation -->
            <div id="installation" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Installation & Setup</h1>
                <p class="text-lg text-gray-600 mb-8">
                    TaskCheck is a web-based application that works in any modern browser. No installation required!
                </p>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-green-900 mb-2">System Requirements</h3>
                    <ul class="text-green-800 space-y-1">
                        <li>• Modern web browser (Chrome, Firefox, Safari, Edge)</li>
                        <li>• Internet connection</li>
                        <li>• JavaScript enabled</li>
                    </ul>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Mobile Apps</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="font-semibold text-gray-900 mb-2">iOS App</h3>
                        <p class="text-gray-600 mb-4">Download TaskCheck for iPhone and iPad from the App Store.</p>
                        <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            Download for iOS
                        </a>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="font-semibold text-gray-900 mb-2">Android App</h3>
                        <p class="text-gray-600 mb-4">Get TaskCheck for Android devices from Google Play Store.</p>
                        <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                            </svg>
                            Download for Android
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div id="tasks" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Managing Tasks</h1>
                <p class="text-lg text-gray-600 mb-8">
                    Learn how to create, organize, and track tasks effectively in TaskCheck.
                </p>

                <div class="space-y-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Creating Tasks</h2>
                        <p class="text-gray-600 mb-4">Tasks are the building blocks of your projects. Here's how to create them:</p>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                            <li>Click the "New Task" button in your dashboard</li>
                            <li>Enter a descriptive title for your task</li>
                            <li>Add a detailed description (optional but recommended)</li>
                            <li>Set a due date and priority level</li>
                            <li>Assign the task to yourself or a team member</li>
                            <li>Add tags for better organization</li>
                            <li>Click "Create Task" to save</li>
                        </ol>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Task Statuses</h2>
                        <p class="text-gray-600 mb-4">Tasks can have different statuses to track their progress:</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                <span class="text-gray-700">Not Started</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-gray-700">In Progress</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-gray-700">On Hold</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-gray-700">Completed</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Task Priorities</h2>
                        <p class="text-gray-600 mb-4">Set priorities to help your team focus on what matters most:</p>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-red-500 rounded"></div>
                                <span class="text-gray-700 font-medium">High Priority</span>
                                <span class="text-gray-500 text-sm">Urgent and important tasks</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                                <span class="text-gray-700 font-medium">Medium Priority</span>
                                <span class="text-gray-500 text-sm">Important but not urgent</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <span class="text-gray-700 font-medium">Low Priority</span>
                                <span class="text-gray-500 text-sm">Nice to have tasks</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams -->
            <div id="teams" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Team Management</h1>
                <p class="text-lg text-gray-600 mb-8">
                    Learn how to manage your team, assign roles, and collaborate effectively.
                </p>

                <div class="space-y-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">User Roles</h2>
                        <p class="text-gray-600 mb-4">TaskCheck has two main user roles with different permissions:</p>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">Admin</h3>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Create and manage tasks</li>
                                    <li>• Invite and manage team members</li>
                                    <li>• Access all projects and tasks</li>
                                    <li>• Manage billing and settings</li>
                                    <li>• View analytics and reports</li>
                                </ul>
                            </div>
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">Employee</h3>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• View assigned tasks</li>
                                    <li>• Update task status and comments</li>
                                    <li>• Access assigned projects</li>
                                    <li>• View team members</li>
                                    <li>• Limited settings access</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Inviting Team Members</h2>
                        <p class="text-gray-600 mb-4">Add new team members to your workspace:</p>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                            <li>Go to Settings > Team Management</li>
                            <li>Click "Invite Members"</li>
                            <li>Enter email addresses (one per line)</li>
                            <li>Select the role for each member</li>
                            <li>Add a personal message (optional)</li>
                            <li>Click "Send Invitations"</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- API -->
            <div id="api" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">API Reference</h1>
                <p class="text-lg text-gray-600 mb-8">
                    Integrate TaskCheck with your applications using our REST API.
                </p>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Base URL</h3>
                    <div class="code-block text-sm">
                        https://api.taskcheck.com/v1
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Authentication</h2>
                        <p class="text-gray-600 mb-4">All API requests require authentication using an API key:</p>
                        <div class="code-block text-sm">
Authorization: Bearer YOUR_API_KEY
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Example: Get Tasks</h2>
                        <div class="code-block text-sm">
curl -X GET "https://api.taskcheck.com/v1/tasks" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json"
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Example: Create Task</h2>
                        <div class="code-block text-sm">
curl -X POST "https://api.taskcheck.com/v1/tasks" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New task",
    "description": "Task description",
    "priority": "high",
    "due_date": "2024-03-20"
  }'
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('api') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        View Full API Documentation
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Other sections would go here... -->
            <div id="projects" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Projects</h1>
                <p class="text-lg text-gray-600 mb-8">Coming soon...</p>
            </div>

            <div id="notifications" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Notifications</h1>
                <p class="text-lg text-gray-600 mb-8">Coming soon...</p>
            </div>

            <div id="integrations" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Integrations</h1>
                <p class="text-lg text-gray-600 mb-8">Coming soon...</p>
            </div>

            <div id="customization" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Customization</h1>
                <p class="text-lg text-gray-600 mb-8">Coming soon...</p>
            </div>

            <div id="troubleshooting" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Troubleshooting</h1>
                <p class="text-lg text-gray-600 mb-8">Coming soon...</p>
            </div>

            <div id="faq" class="section hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h1>
                <p class="text-lg text-gray-600 mb-8">Coming soon...</p>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });

            // Show selected section
            document.getElementById(sectionId).classList.remove('hidden');

            // Update active sidebar link
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
