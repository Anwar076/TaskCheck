<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Servicevoorwaarden - {{ config('app.name', 'TaskCheck') }}</title>
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
                <a href="{{ url('/features') }}" class="text-gray-700 hover:text-primary-600 font-medium">Functies</a>
                <a href="{{ url('/pricing') }}" class="text-gray-700 hover:text-primary-600 font-medium">Prijzen</a>
                <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary-600 font-medium">Over</a>
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
                Service<span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">voorwaarden</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                Lees deze voorwaarden zorgvuldig door voordat je onze service gebruikt.
            </p>
            <p class="mt-4 text-sm text-gray-500 fade-up" style="animation-delay:0.6s;">
                Laatst bijgewerkt: {{ date('j F Y') }}
            </p>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="bg-white/70 backdrop-blur-md rounded-2xl p-8 md:p-12 fade-up">
            <div class="prose prose-lg max-w-none">
                
                <h2 class="text-2xl font-bold text-gray-900 mb-6">1. Acceptance of Terms</h2>
                <p class="text-gray-600 mb-6">
                    By accessing and using TaskCheck ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">2. Description of Service</h2>
                <p class="text-gray-600 mb-6">
                    TaskCheck is a task management and collaboration platform that allows teams to organize, track, and manage their work. The service includes web-based applications, mobile applications, and related services.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">3. User Accounts</h2>
                <p class="text-gray-600 mb-6">
                    To access certain features of the Service, you must register for an account. You agree to:
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Provide accurate, current, and complete information</li>
                    <li>Maintain and update your account information</li>
                    <li>Keep your password secure and confidential</li>
                    <li>Accept responsibility for all activities under your account</li>
                    <li>Notify us immediately of any unauthorized use</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">4. Acceptable Use</h2>
                <p class="text-gray-600 mb-6">
                    You agree to use the Service only for lawful purposes and in accordance with these Terms. You agree not to:
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Violate any applicable laws or regulations</li>
                    <li>Infringe on the rights of others</li>
                    <li>Transmit harmful or malicious code</li>
                    <li>Attempt to gain unauthorized access to the Service</li>
                    <li>Use the Service for any commercial purpose without permission</li>
                    <li>Interfere with the proper functioning of the Service</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">5. Subscription and Payment</h2>
                <p class="text-gray-600 mb-6">
                    The Service is offered on a subscription basis. By subscribing, you agree to:
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Pay all fees associated with your subscription</li>
                    <li>Provide accurate billing information</li>
                    <li>Authorize us to charge your payment method</li>
                    <li>Understand that fees are non-refundable except as required by law</li>
                    <li>Accept that prices may change with notice</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">6. Intellectual Property</h2>
                <p class="text-gray-600 mb-6">
                    The Service and its original content, features, and functionality are owned by TaskCheck and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                </p>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Your Content</h3>
                <p class="text-gray-600 mb-6">
                    You retain ownership of any content you create, upload, or share through the Service. By using the Service, you grant us a limited license to use, store, and process your content as necessary to provide the Service.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">7. Privacy and Data Protection</h2>
                <p class="text-gray-600 mb-6">
                    Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms by reference.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">8. Service Availability</h2>
                <p class="text-gray-600 mb-6">
                    We strive to maintain high service availability, but we do not guarantee that the Service will be available at all times. We may experience downtime for maintenance, updates, or other reasons.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">9. Termination</h2>
                <p class="text-gray-600 mb-6">
                    Either party may terminate this agreement at any time. Upon termination:
                </p>
                
                <ul class="list-disc list-inside text-gray-600 mb-6 space-y-2">
                    <li>Your right to use the Service will cease immediately</li>
                    <li>We may delete your account and data</li>
                    <li>You remain responsible for any outstanding fees</li>
                    <li>Provisions that by their nature should survive termination will remain in effect</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">10. Disclaimers and Limitations</h2>
                <p class="text-gray-600 mb-6">
                    THE SERVICE IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND. WE DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.
                </p>
                
                <p class="text-gray-600 mb-6">
                    IN NO EVENT SHALL TASKCHECK BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING WITHOUT LIMITATION, LOSS OF PROFITS, DATA, USE, GOODWILL, OR OTHER INTANGIBLE LOSSES.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">11. Indemnification</h2>
                <p class="text-gray-600 mb-6">
                    You agree to defend, indemnify, and hold harmless TaskCheck and its officers, directors, employees, and agents from and against any claims, damages, obligations, losses, liabilities, costs, or debt, and expenses (including attorney's fees).
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">12. Governing Law</h2>
                <p class="text-gray-600 mb-6">
                    These Terms shall be governed by and construed in accordance with the laws of the State of New York, without regard to its conflict of law provisions.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">13. Dispute Resolution</h2>
                <p class="text-gray-600 mb-6">
                    Any disputes arising out of or relating to these Terms or the Service shall be resolved through binding arbitration in accordance with the rules of the American Arbitration Association.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">14. Changes to Terms</h2>
                <p class="text-gray-600 mb-6">
                    We reserve the right to modify these Terms at any time. We will notify users of any material changes by posting the new Terms on this page and updating the "Last updated" date.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-6 mt-12">15. Contact Information</h2>
                <p class="text-gray-600 mb-6">
                    If you have any questions about these Terms of Service, please contact us:
                </p>
                
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <p class="text-gray-600 mb-2"><strong>Email:</strong> legal@taskcheck.com</p>
                    <p class="text-gray-600 mb-2"><strong>Address:</strong> 123 Business Street, Suite 100, New York, NY 10001</p>
                    <p class="text-gray-600"><strong>Phone:</strong> +1 (555) 123-4567</p>
                </div>

                <div class="border-t border-gray-200 pt-8 mt-12">
                    <p class="text-sm text-gray-500 text-center">
                        These terms of service are effective as of {{ date('F j, Y') }} and will remain in effect except with respect to any changes in its provisions in the future.
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
