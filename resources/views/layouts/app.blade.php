<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Recruitment Agency') }} - @yield('title', 'Home')</title>

        <!-- Meta Tags -->
        <meta name="description" content="@yield('meta_description', 'Premier recruitment agency specializing in professional placements')">
        <meta name="keywords" content="@yield('meta_keywords', 'recruitment, jobs, career, hiring')">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100">
        <header class="bg-white shadow">
            <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                        {{ config('app.name', 'Recruitment Agency') }}
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">About</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Services</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Jobs</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Blog</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Contact</a>
                </div>
                
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-blue-600" x-data="{}" x-on:click="$dispatch('toggle-mobile-menu')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </nav>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden" x-data="{ open: false }" x-show="open" x-on:toggle-mobile-menu.window="open = !open">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Home</a>
                    <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600">About</a>
                    <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Services</a>
                    <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Jobs</a>
                    <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Blog</a>
                    <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Contact</a>
                </div>
            </div>
        </header>
        
        <main class="container mx-auto px-4 py-8">
            @yield('content')
        </main>
        
        <footer class="bg-gray-800 text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-4">{{ config('app.name', 'Recruitment Agency') }}</h3>
                        <p class="text-gray-400">Connecting top talent with premier organizations since 2010.</p>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Home</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Services</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Jobs</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                        <address class="not-italic text-gray-400">
                            <p>123 Recruitment Street</p>
                            <p>Business District, City</p>
                            <p>contact@example.com</p>
                            <p>+1 (555) 123-4567</p>
                        </address>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400">© {{ date('Y') }} {{ config('app.name', 'Recruitment Agency') }}. All rights reserved.</p>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Livewire Scripts -->
        @livewireScripts
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
