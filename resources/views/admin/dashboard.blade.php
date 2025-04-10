@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats Card 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Companies</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">128</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center">
                    <span class="text-green-600 dark:text-green-400 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        7.2%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Jobs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">64</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center">
                    <span class="text-green-600 dark:text-green-400 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        12.5%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">vs last month</span>
                </div>
            </div>
        </div>
        
        <!-- Stats Card 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New Candidates</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">87</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center">
                    <span class="text-green-600 dark:text-green-400 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        18.3%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">vs last month</span>
                </div>
            </div>
        </div>
        
        <!-- Stats Card 4 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Open Opportunities</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">42</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center">
                    <span class="text-red-600 dark:text-red-400 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                        4.6%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">vs last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
            </div>
            <div class="p-6">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">New candidate applied for Senior Developer position</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">2 hours ago</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">New job posted: Marketing Manager at ABC Corp</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">3 hours ago</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">New opportunity created: Software Development Project</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">5 hours ago</p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="mt-6">
                    <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">View all activity</a>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Interviews -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Upcoming Interviews</h3>
            </div>
            <div class="p-6">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-4">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">John Smith</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Frontend Developer</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Today</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">2:00 PM</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-4">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Sarah Johnson</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">UX Designer</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Tomorrow</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">10:30 AM</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-4">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Michael Lee</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">DevOps Engineer</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">May 24, 2023</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">3:15 PM</p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="mt-6">
                    <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">View all interviews</a>
                </div>
            </div>
        </div>
    </div>
@endsection 