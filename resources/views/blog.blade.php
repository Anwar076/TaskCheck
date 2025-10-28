<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Blog - {{ config('app.name', 'TaskCheck') }}</title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">TaskCheck Blog</span>
            </div>
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">← Back to TaskCheck</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Blog & News</h1>
            <p class="text-lg text-gray-600">Stay updated with the latest insights, tips, and news from TaskCheck</p>
        </div>

        <!-- Featured Post -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden mb-12">
            <div class="md:flex">
                <div class="md:w-1/2">
                    <div class="h-64 md:h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <div class="text-center text-white p-8">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h2 class="text-2xl font-bold mb-2">Featured</h2>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 p-8">
                    <div class="flex items-center mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Productivity</span>
                        <span class="text-gray-500 text-sm ml-4">March 10, 2024</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10 Ways to Boost Team Productivity with Task Management</h2>
                    <p class="text-gray-600 mb-6">Discover proven strategies to increase your team's productivity using effective task management techniques and tools.</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full mr-3"></div>
                        <div>
                            <div class="font-semibold text-gray-900">Sarah Johnson</div>
                            <div class="text-sm text-gray-600">Product Manager</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Post 1 -->
            <article class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-48 bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Collaboration</span>
                        <span class="text-gray-500 text-sm ml-4">March 8, 2024</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Building Effective Remote Teams</h3>
                    <p class="text-gray-600 text-sm mb-4">Learn how to create and manage high-performing remote teams using modern collaboration tools and best practices.</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gray-300 rounded-full mr-2"></div>
                        <span>Mike Chen</span>
                    </div>
                </div>
            </article>

            <!-- Post 2 -->
            <article class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Analytics</span>
                        <span class="text-gray-500 text-sm ml-4">March 5, 2024</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Measuring Team Performance</h3>
                    <p class="text-gray-600 text-sm mb-4">Discover key metrics and KPIs to track team performance and identify areas for improvement.</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gray-300 rounded-full mr-2"></div>
                        <span>Emily Rodriguez</span>
                    </div>
                </div>
            </article>

            <!-- Post 3 -->
            <article class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Time Management</span>
                        <span class="text-gray-500 text-sm ml-4">March 3, 2024</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Time Tracking Best Practices</h3>
                    <p class="text-gray-600 text-sm mb-4">Learn how to implement effective time tracking strategies that improve productivity without overwhelming your team.</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gray-300 rounded-full mr-2"></div>
                        <span>David Kim</span>
                    </div>
                </div>
            </article>

            <!-- Post 4 -->
            <article class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-48 bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded-full">Security</span>
                        <span class="text-gray-500 text-sm ml-4">March 1, 2024</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Data Security in Task Management</h3>
                    <p class="text-gray-600 text-sm mb-4">Understand the importance of data security and how to protect sensitive information in your task management workflows.</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gray-300 rounded-full mr-2"></div>
                        <span>Lisa Wang</span>
                    </div>
                </div>
            </article>

            <!-- Post 5 -->
            <article class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-48 bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM4 5h6V1H4v4zM10 3h4v4h-4V3z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">Workflow</span>
                        <span class="text-gray-500 text-sm ml-4">February 28, 2024</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Automating Workflows</h3>
                    <p class="text-gray-600 text-sm mb-4">Discover how to automate repetitive tasks and create efficient workflows that save time and reduce errors.</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gray-300 rounded-full mr-2"></div>
                        <span>Alex Thompson</span>
                    </div>
                </div>
            </article>

            <!-- Post 6 -->
            <article class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-48 bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded-full">Culture</span>
                        <span class="text-gray-500 text-sm ml-4">February 25, 2024</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Building Team Culture</h3>
                    <p class="text-gray-600 text-sm mb-4">Learn how to foster a positive team culture that promotes collaboration, innovation, and employee satisfaction.</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gray-300 rounded-full mr-2"></div>
                        <span>Maria Garcia</span>
                    </div>
                </div>
            </article>
        </div>

        <!-- Newsletter Signup -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-8 text-center text-white mb-12">
            <h2 class="text-2xl font-bold mb-4">Stay Updated</h2>
            <p class="text-blue-100 mb-6">Get the latest blog posts, product updates, and tips delivered to your inbox.</p>
            <div class="max-w-md mx-auto flex gap-4">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white focus:ring-opacity-50">
                <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    Subscribe
                </button>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white rounded-lg shadow-sm border p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Categories</h2>
            <div class="grid md:grid-cols-4 gap-4">
                <a href="#" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Productivity</div>
                        <div class="text-sm text-gray-600">12 posts</div>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Collaboration</div>
                        <div class="text-sm text-gray-600">8 posts</div>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Analytics</div>
                        <div class="text-sm text-gray-600">6 posts</div>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Time Management</div>
                        <div class="text-sm text-gray-600">9 posts</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p>Want to contribute to our blog? <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">Contact us</a></p>
            <p class="mt-2">Follow us on <a href="#" class="text-blue-600 hover:underline">Twitter</a> and <a href="#" class="text-blue-600 hover:underline">LinkedIn</a> for updates</p>
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
