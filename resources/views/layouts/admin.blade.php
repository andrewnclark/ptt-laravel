<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Additional Styles -->
    @livewireStyles
    @stack('styles')
</head>
<body class="antialiased font-sans bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 transition duration-300 transform bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-50 lg:translate-x-0 -translate-x-full">
            <!-- Sidebar header -->
            <div class="flex items-center justify-between h-16 px-6 bg-cyan-600 dark:bg-cyan-700">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="ml-2 text-lg font-bold text-white">{{ config('app.name', 'PTT') }}</span>
                    </a>
                </div>
                <button class="lg:hidden text-white focus:outline-none" x-data="" x-on:click="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Sidebar content -->
            <div class="flex flex-col flex-grow py-4 overflow-y-auto">
                <div class="px-4 mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" class="w-full pl-10 pr-4 py-2 rounded-lg text-sm bg-gray-100 dark:bg-gray-700 border-transparent focus:border-cyan-500 focus:bg-white dark:focus:bg-gray-600 focus:ring-cyan-500" placeholder="Search...">
                    </div>
                </div>
                
                <nav class="space-y-1 px-2">
                    <div class="py-2">
                        <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Main
                        </p>
                    </div>

                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                        <svg class="{{ request()->routeIs('admin.dashboard') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <div class="py-2">
                        <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Modules
                        </p>
                    </div>

                    <div x-data="{ open: {{ request()->is('admin/crm*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="{{ request()->is('admin/crm*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                            <div class="flex items-center">
                                <svg class="{{ request()->is('admin/crm*') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                CRM
                            </div>
                            <svg :class="{'transform rotate-180': open}" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('admin.crm.contacts.index') }}" class="{{ request()->is('*crm*contacts*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Contacts
                            </a>
                            <a href="{{ route('admin.crm.companies.index') }}" class="{{ request()->is('*crm*companies*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Companies
                            </a>
                            <a href="{{ route('admin.crm.opportunities.index') }}" class="{{ request()->is('*crm*opportunities*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Opportunities
                            </a>
                            <a href="#" class="{{ request()->is('*crm*tasks*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Tasks
                            </a>
                        </div>
                    </div>

                    <div x-data="{ open: {{ request()->is('admin/jobs*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="{{ request()->is('admin/jobs*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                            <div class="flex items-center">
                                <svg class="{{ request()->is('admin/jobs*') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Job Board
                            </div>
                            <svg :class="{'transform rotate-180': open}" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('dashboard') }}?module=jobs&view=listings" class="{{ request()->is('*jobs*listings*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Job Listings
                            </a>
                            <a href="{{ route('dashboard') }}?module=jobs&view=applications" class="{{ request()->is('*jobs*applications*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Applications
                            </a>
                        </div>
                    </div>

                    <div x-data="{ open: {{ request()->is('admin/ats*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="{{ request()->is('admin/ats*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                            <div class="flex items-center">
                                <svg class="{{ request()->is('admin/ats*') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                ATS
                            </div>
                            <svg :class="{'transform rotate-180': open}" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('dashboard') }}?module=ats&view=candidates" class="{{ request()->is('*ats*candidates*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Candidates
                            </a>
                            <a href="{{ route('dashboard') }}?module=ats&view=pipelines" class="{{ request()->is('*ats*pipelines*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Pipeline
                            </a>
                            <a href="{{ route('dashboard') }}?module=ats&view=interviews" class="{{ request()->is('*ats*interviews*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Interviews
                            </a>
                        </div>
                    </div>
                    
                    <div x-data="{ open: {{ request()->is('admin/marketing*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="{{ request()->is('admin/marketing*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                            <div class="flex items-center">
                                <svg class="{{ request()->is('admin/marketing*') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                Marketing
                            </div>
                            <svg :class="{'transform rotate-180': open}" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('dashboard') }}?module=marketing&view=pages" class="{{ request()->is('*marketing*pages*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Pages
                            </a>
                            <a href="{{ route('dashboard') }}?module=marketing&view=posts" class="{{ request()->is('*marketing*posts*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Blog
                            </a>
                            <a href="{{ route('dashboard') }}?module=marketing&view=media" class="{{ request()->is('*marketing*media*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md">
                                Media
                            </a>
                        </div>
                    </div>
                    
                    <div class="py-2">
                        <p class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Settings
                        </p>
                    </div>
                    
                    <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.edit') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                        <svg class="{{ request()->routeIs('admin.profile.edit') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Profile Settings
                    </a>
                    
                    <a href="{{ route('dashboard') }}?view=settings" class="{{ request()->is('*settings*') ? 'bg-cyan-50 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} flex items-center px-3 py-2 text-sm font-medium rounded-md group transition-colors">
                        <svg class="{{ request()->is('*settings*') ? 'text-cyan-500 dark:text-cyan-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        System Settings
                    </a>
                </nav>
            </div>
            
            <!-- Sidebar footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 text-sm font-semibold">
                            {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'A' }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                            {{ auth()->user() ? auth()->user()->name : 'Admin User' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ auth()->user() ? auth()->user()->email : 'admin@example.com' }}
                        </p>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 rounded-full">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm lg:fixed lg:w-full lg:top-0 lg:z-40" style="left: 16rem;">
                <div class="h-16 flex items-center justify-between px-4 lg:px-8">
                    <button class="text-gray-500 dark:text-gray-400 lg:hidden focus:outline-none focus:ring-2 focus:ring-cyan-500 rounded p-1" x-data="" x-on:click="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <div class="flex-1 flex justify-center lg:justify-start">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200 truncate">
                            @yield('header', 'Dashboard')
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Dark mode toggle -->
                        <button 
                            x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
                            x-init="
                                if (darkMode) {
                                    document.documentElement.classList.add('dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                }
                            "
                            @click="
                                darkMode = !darkMode;
                                localStorage.setItem('darkMode', darkMode);
                                if (darkMode) {
                                    document.documentElement.classList.add('dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                }
                            "
                            class="rounded-full p-1 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        >
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                        
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                class="rounded-full p-1 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 relative"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100" 
                                x-transition:enter-start="transform opacity-0 scale-95" 
                                x-transition:enter-end="transform opacity-100 scale-100" 
                                x-transition:leave="transition ease-in duration-75" 
                                x-transition:leave-start="transform opacity-100 scale-100" 
                                x-transition:leave-end="transform opacity-0 scale-95" 
                                class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-50"
                            >
                                <div class="px-4 py-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Notifications</p>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <span class="inline-block h-8 w-8 rounded-full bg-cyan-100 dark:bg-cyan-900 text-cyan-500 flex items-center justify-center">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-200">New message received</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">5 minutes ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <span class="inline-block h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 text-green-500 flex items-center justify-center">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Task completed</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-cyan-600 dark:text-cyan-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-center">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                class="rounded-full h-8 w-8 overflow-hidden bg-gray-200 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            >
                                <span class="sr-only">Open user menu</span>
                                <span class="h-full w-full flex items-center justify-center text-gray-700 dark:text-gray-300 text-sm font-semibold">
                                    {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'A' }}
                                </span>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100" 
                                x-transition:enter-start="transform opacity-0 scale-95" 
                                x-transition:enter-end="transform opacity-100 scale-100" 
                                x-transition:leave="transition ease-in duration-75" 
                                x-transition:leave-start="transform opacity-100 scale-100" 
                                x-transition:leave-end="transform opacity-0 scale-95" 
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            >
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="pt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html> 