<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Vacatures - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">TaskCheck Careers</span>
            </div>
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">← Back to TaskCheck</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Join Our Team</h1>
            <p class="text-lg text-gray-600">Help us build the future of task management and team collaboration</p>
        </div>

        <!-- Company Culture -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Why Work at TaskCheck?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Innovation</h3>
                    <p class="text-gray-600">Work on cutting-edge technology that helps teams worldwide be more productive and efficient.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Collaboration</h3>
                    <p class="text-gray-600">Join a diverse, inclusive team that values collaboration, creativity, and mutual support.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Growth</h3>
                    <p class="text-gray-600">Advance your career with learning opportunities, mentorship, and challenging projects.</p>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Benefits & Perks</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Health & Wellness</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Comprehensive health, dental, and vision insurance</li>
                        <li>• Mental health support and counseling</li>
                        <li>• Gym membership reimbursement</li>
                        <li>• Flexible work arrangements</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Development</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Learning and development budget</li>
                        <li>• Conference and training opportunities</li>
                        <li>• Mentorship programs</li>
                        <li>• Career advancement paths</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Work-Life Balance</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Unlimited PTO policy</li>
                        <li>• Remote work options</li>
                        <li>• Flexible hours</li>
                        <li>• Family-friendly policies</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Compensation</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Competitive salary and equity</li>
                        <li>• Performance bonuses</li>
                        <li>• 401(k) with company matching</li>
                        <li>• Stock option program</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Open Positions -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Open Positions</h2>
            <div class="space-y-6">
                <!-- Job 1 -->
                <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Senior Full Stack Developer</h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Remote / San Francisco
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Full-time
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    $120k - $180k
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">We're looking for a senior full stack developer to help build and scale our task management platform. You'll work with modern technologies and have a significant impact on our product.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">React</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Node.js</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">TypeScript</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">PostgreSQL</span>
                            </div>
                        </div>
                        <button class="ml-6 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Now
                        </button>
                    </div>
                </div>

                <!-- Job 2 -->
                <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Product Designer</h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    New York / Remote
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Full-time
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    $90k - $130k
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">Join our design team to create beautiful, intuitive user experiences that help teams collaborate more effectively. You'll work closely with product and engineering teams.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Figma</span>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Sketch</span>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Prototyping</span>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">User Research</span>
                            </div>
                        </div>
                        <button class="ml-6 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Now
                        </button>
                    </div>
                </div>

                <!-- Job 3 -->
                <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">DevOps Engineer</h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Remote
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Full-time
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    $110k - $160k
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">Help us scale our infrastructure and ensure high availability. You'll work with modern cloud technologies and have the opportunity to shape our DevOps practices.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">AWS</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Docker</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Kubernetes</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Terraform</span>
                            </div>
                        </div>
                        <button class="ml-6 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Now
                        </button>
                    </div>
                </div>

                <!-- Job 4 -->
                <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Customer Success Manager</h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    San Francisco
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Full-time
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    $70k - $100k
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">Help our customers succeed with TaskCheck. You'll work directly with enterprise clients to ensure they get maximum value from our platform.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Customer Success</span>
                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Account Management</span>
                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">SaaS</span>
                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Enterprise</span>
                            </div>
                        </div>
                        <button class="ml-6 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Process -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Application Process</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">1</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Apply</h3>
                    <p class="text-sm text-gray-600">Submit your application with resume and cover letter</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">2</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Initial Review</h3>
                    <p class="text-sm text-gray-600">Our team reviews your application within 48 hours</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">3</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Interviews</h3>
                    <p class="text-sm text-gray-600">Video calls with team members and technical assessments</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold">4</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Decision</h3>
                    <p class="text-sm text-gray-600">We make a decision and extend an offer</p>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-blue-50 rounded-lg border border-blue-200 p-8 text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Don't See a Role for You?</h2>
            <p class="text-gray-600 mb-6">We're always looking for talented people to join our team. Send us your resume and let us know how you'd like to contribute.</p>
            <div class="space-y-4">
                <a href="mailto:careers@taskcheck.com" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    careers@taskcheck.com
                </a>
                <p class="text-sm text-gray-600">We'll get back to you within 2 business days</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p>Follow us on <a href="#" class="text-blue-600 hover:underline">LinkedIn</a> for company updates and new job postings</p>
            <p class="mt-2">Questions about our culture? <a href="{{ route('about') }}" class="text-blue-600 hover:underline">Learn more about us</a></p>
        </div>
    </div>

    @include('components.footer')

    <!-- Mobile menu script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
