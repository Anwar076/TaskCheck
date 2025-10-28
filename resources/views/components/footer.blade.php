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
                <p class="text-gray-600 mb-4">Het ultieme taakbeheerplatform voor moderne teams.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Product</h3>
                <ul class="space-y-2 text-gray-600">
                    <li><a href="{{ route('features') }}" class="hover:text-blue-600 transition-colors">Functies</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-blue-600 transition-colors">Prijzen</a></li>
                    <li><a href="{{ route('integrations') }}" class="hover:text-blue-600 transition-colors">Integraties</a></li>
                    <li><a href="{{ route('api') }}" class="hover:text-blue-600 transition-colors">API</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Bedrijf</h3>
                <ul class="space-y-2 text-gray-600">
                    <li><a href="{{ route('about') }}" class="hover:text-blue-600 transition-colors">Over ons</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-blue-600 transition-colors">Blog</a></li>
                    <li><a href="{{ route('careers') }}" class="hover:text-blue-600 transition-colors">Vacatures</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-blue-600 transition-colors">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Ondersteuning</h3>
                <ul class="space-y-2 text-gray-600">
                    <li><a href="{{ route('help') }}" class="hover:text-blue-600 transition-colors">Helpcentrum</a></li>
                    <li><a href="{{ route('documentation') }}" class="hover:text-blue-600 transition-colors">Documentatie</a></li>
                    <li><a href="{{ route('status') }}" class="hover:text-blue-600 transition-colors">Status</a></li>
                    <li><a href="{{ route('security') }}" class="hover:text-blue-600 transition-colors">Beveiliging</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-blue-600 transition-colors">Privacybeleid</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-blue-600 transition-colors">Servicevoorwaarden</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-8 text-center text-gray-600">
            <p>© {{ date('Y') }} TaskCheck. Gebouwd met <span class="text-red-500">♥</span> in Nederland met Laravel & Tailwind CSS.</p>
        </div>
    </div>
</footer>