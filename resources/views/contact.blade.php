<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Contact - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')

    <!-- Hero -->
    <section class="pt-32 pb-20 text-center relative overflow-hidden">
        <div class="absolute top-10 -left-20 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-10 -right-20 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl float" style="animation-delay:3s;"></div>

        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight fade-up">
                Neem <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Contact</span> Op
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                We horen graag van je. Stuur ons een bericht en we reageren zo snel mogelijk.
            </p>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Stuur ons een Bericht</h2>
                <form class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">Voornaam</label>
                            <input type="text" id="firstName" name="firstName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Jan">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Achternaam</label>
                            <input type="text" id="lastName" name="lastName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Janssen">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Adres</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="jan@voorbeeld.nl">
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Bedrijf (Optioneel)</label>
                        <input type="text" id="company" name="company" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Jouw Bedrijf">
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Onderwerp</label>
                        <select id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Selecteer een onderwerp</option>
                            <option value="general">Algemene Vraag</option>
                            <option value="support">Technische Ondersteuning</option>
                            <option value="sales">Verkoop Vraag</option>
                            <option value="billing">Factuur Probleem</option>
                            <option value="feature">Functie Verzoek</option>
                            <option value="partnership">Partnerschap</option>
                        </select>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Bericht</label>
                        <textarea id="message" name="message" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Vertel ons hoe we je kunnen helpen..."></textarea>
                    </div>
                    <button type="submit" class="w-full btn-gradient text-white py-3 rounded-lg font-semibold text-lg">
                        Bericht Versturen
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8 fade-up" style="animation-delay:0.2s;">
                <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Information</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email</h4>
                                <p class="text-gray-600">hello@taskcheck.com</p>
                                <p class="text-gray-600">support@taskcheck.com</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Phone</h4>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                                <p class="text-gray-600">Mon-Fri 9AM-6PM EST</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Address</h4>
                                <p class="text-gray-600">123 Business Street<br>Suite 100<br>New York, NY 10001</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Business Hours</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Monday - Friday</span>
                            <span class="font-medium text-gray-900">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Saturday</span>
                            <span class="font-medium text-gray-900">10:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sunday</span>
                            <span class="font-medium text-gray-900">Closed</span>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Links</h3>
                    <div class="space-y-3">
                        <a href="{{ url('/help') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Help Center</a>
                        <a href="{{ url('/features') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Feature Documentation</a>
                        <a href="{{ url('/pricing') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Pricing Plans</a>
                        <a href="{{ url('/about') }}" class="block text-blue-600 hover:text-blue-700 font-medium">About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600">Quick answers to common questions</p>
        </div>
        
        <div class="space-y-6">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.2s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How quickly do you respond to inquiries?</h3>
                <p class="text-gray-600">We typically respond to all inquiries within 24 hours during business days. For urgent matters, please call our support line.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.4s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you offer phone support?</h3>
                <p class="text-gray-600">Yes! Phone support is available for Professional, Enterprise, and Custom plan customers. Basic plan users can reach us via email.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.6s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I schedule a demo?</h3>
                <p class="text-gray-600">Absolutely! Contact our sales team to schedule a personalized demo of TaskCheck features and capabilities.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.8s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">What's the best way to get technical support?</h3>
                <p class="text-gray-600">For technical issues, email support@taskcheck.com with detailed information about your problem. Include screenshots if possible.</p>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
