<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Privacybeleid - {{ config('app.name', 'TaskCheck') }}</title>
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
        @keyframes fadeUp { from { opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
        @keyframes fadeIn { from { opacity:0;} to {opacity:1;} }

        .fade-up { animation: fadeUp 0.9s ease-out forwards; }
        .fade-in { animation: fadeIn 1s ease-out forwards; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-md border-b border-gray-200/40">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold">TaskCheck</span>
                </a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary-600 font-medium">Home</a>
                <a href="{{ url('/features') }}" class="text-gray-700 hover:text-primary-600 font-medium">Features</a>
                <a href="{{ url('/pricing') }}" class="text-gray-700 hover:text-primary-600 font-medium">Pricing</a>
                <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary-600 font-medium">About</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-primary-600 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-32 pb-20 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight fade-up">
                Privacy <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Policy</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                Your privacy is important to us. This policy explains how we collect, use, and protect your information.
            </p>
            <p class="mt-4 text-sm text-gray-500 fade-up" style="animation-delay:0.6s;">
                Last updated: {{ date('F j, Y') }}
            </p>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="bg-white/70 backdrop-blur-md rounded-2xl p-8 md:p-12 fade-up">
            <div class="prose prose-lg max-w-none">
                
                <h2 class="text-2xl font-bold text-gray-900 mb-6">1. Information We Collect</h2>
                <p class="text-gray-600 mb-6">
                    We collect information you provide directly to us, such as when you create an account, use our services, or contact us for support.
                </p>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h3>
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Name and email address</li>
                    <li>Company information</li>
                    <li>Payment information (processed securely by third-party providers)</li>
                    <li>Profile information and preferences</li>
                </ul>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Usage Information</h3>
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Tasks, projects, and team data you create</li>
                    <li>How you interact with our platform</li>
                    <li>Device and browser information</li>
                    <li>IP address and location data</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">2. How We Use Your Information</h2>
                <p class="text-gray-600 mb-6">
                    We use the information we collect to provide, maintain, and improve our services.
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Provide and maintain our task management platform</li>
                    <li>Process transactions and send related information</li>
                    <li>Send technical notices, updates, and support messages</li>
                    <li>Respond to your comments and questions</li>
                    <li>Improve our services and develop new features</li>
                    <li>Monitor and analyze usage and trends</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">3. Information Sharing</h2>
                <p class="text-gray-600 mb-6">
                    We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.
                </p>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">We may share information with:</h3>
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Service providers who assist us in operating our platform</li>
                    <li>Other users in your team or organization (as part of the service)</li>
                    <li>Legal authorities when required by law</li>
                    <li>Business partners with your explicit consent</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">4. Data Security</h2>
                <p class="text-gray-600 mb-6">
                    We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Encryption of data in transit and at rest</li>
                    <li>Regular security audits and assessments</li>
                    <li>Access controls and authentication</li>
                    <li>Secure data centers and infrastructure</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">5. Your Rights</h2>
                <p class="text-gray-600 mb-6">
                    You have certain rights regarding your personal information, including:
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Access to your personal information</li>
                    <li>Correction of inaccurate information</li>
                    <li>Deletion of your personal information</li>
                    <li>Portability of your data</li>
                    <li>Objection to processing</li>
                    <li>Withdrawal of consent</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">6. Cookies and Tracking</h2>
                <p class="text-gray-600 mb-6">
                    We use cookies and similar tracking technologies to enhance your experience on our platform.
                </p>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Types of cookies we use:</h3>
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Essential cookies for platform functionality</li>
                    <li>Analytics cookies to understand usage patterns</li>
                    <li>Preference cookies to remember your settings</li>
                    <li>Marketing cookies (with your consent)</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">7. Data Retention</h2>
                <p class="text-gray-600 mb-6">
                    We retain your personal information for as long as necessary to provide our services and fulfill the purposes outlined in this policy.
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Account information: Until account deletion</li>
                    <li>Usage data: Up to 2 years</li>
                    <li>Payment information: As required by law</li>
                    <li>Support communications: Up to 3 years</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">8. International Transfers</h2>
                <p class="text-gray-600 mb-6">
                    Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">9. Children's Privacy</h2>
                <p class="text-gray-600 mb-6">
                    Our services are not intended for children under 13. We do not knowingly collect personal information from children under 13.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">10. Changes to This Policy</h2>
                <p class="text-gray-600 mb-6">
                    We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">11. Contact Us</h2>
                <p class="text-gray-600 mb-6">
                    If you have any questions about this privacy policy or our data practices, please contact us:
                </p>
                
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <p class="text-gray-600 mb-2"><strong>Email:</strong> privacy@taskcheck.com</p>
                    <p class="text-gray-600 mb-2"><strong>Address:</strong> 123 Business Street, Suite 100, New York, NY 10001</p>
                    <p class="text-gray-600"><strong>Phone:</strong> +1 (555) 123-4567</p>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-12">
                    <p class="text-sm text-gray-500 text-center">
                        This privacy policy is effective as of {{ date('F j, Y') }} and will remain in effect except with respect to any changes in its provisions in the future.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">TaskCheck</span>
                    </div>
                    <p class="text-gray-600">The ultimate task management platform for modern teams.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ url('/features') }}" class="hover:text-blue-600 transition-colors">Features</a></li>
                        <li><a href="{{ url('/pricing') }}" class="hover:text-blue-600 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ url('/about') }}" class="hover:text-blue-600 transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Careers</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-blue-600 transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Legal</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ url('/privacy') }}" class="hover:text-blue-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms') }}" class="hover:text-blue-600 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-8 text-center text-gray-600">
                <p>© {{ date('Y') }} TaskCheck. Built with <span class="text-red-500">♥</span> Laravel & Tailwind CSS.</p>
            </div>
        </div>
    </footer>
</body>
</html>
