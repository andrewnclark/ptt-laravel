@extends('layouts.admin')

@section('title', 'Opportunity Details')
@section('header', 'Opportunity Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Enterprise Software Package</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300">
                    Negotiation
                </span>
                <span class="ml-2">Created Jan 15, 2023</span>
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.crm.opportunities.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Opportunities
            </a>
            <a href="{{ route('admin.crm.opportunities.edit', 1) }}" class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 dark:bg-cyan-700 border border-transparent rounded-md font-medium text-sm text-white hover:bg-cyan-700 dark:hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Opportunity
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="md:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Opportunity Details
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        Information about this sales opportunity.
                    </p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Value</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                                <span class="font-semibold">$75,000</span>
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                                <a href="{{ route('admin.crm.companies.show', 1) }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300">
                                    Acme Inc
                                </a>
                            </dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Primary Contact</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                                <a href="{{ route('admin.crm.contacts.show', 1) }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300">
                                    John Doe
                                </a>
                                <p class="text-gray-500 dark:text-gray-400">Sales Manager</p>
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expected Close Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                                April 30, 2023
                            </dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Source</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                                Referral
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                                <p>Enterprise software implementation for accounting and financial reporting systems, including migration from legacy systems, staff training, and ongoing support.</p>
                                <p class="mt-2">Client is looking for a complete solution that integrates with their existing CRM and ERP systems.</p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Status
                    </h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-5">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stage</h4>
                            <div class="mt-1 flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300">
                                    Negotiation
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Probability</h4>
                            <div class="mt-1">
                                <span class="text-sm text-gray-900 dark:text-white font-medium">75%</span>
                                <div class="mt-1 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-green-500 dark:bg-green-400 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Days in Current Stage</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">14 days</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Age</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">45 days</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="button" class="w-full flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition">
                            Mark as Won
                        </button>
                        <button type="button" class="w-full mt-3 flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition">
                            Mark as Lost
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Next Steps
                    </h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-5">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li class="py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Schedule follow-up call</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Due in 2 days</p>
                                </div>
                                <input type="checkbox" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 dark:border-gray-600 rounded">
                            </div>
                        </li>
                        <li class="py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Send revised proposal</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Due in 5 days</p>
                                </div>
                                <input type="checkbox" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 dark:border-gray-600 rounded">
                            </div>
                        </li>
                        <li class="py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Get final approval</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Due in 10 days</p>
                                </div>
                                <input type="checkbox" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 dark:border-gray-600 rounded">
                            </div>
                        </li>
                    </ul>
                    
                    <div class="mt-4">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Next Step
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Activities</h2>
            <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-800/30 hover:bg-cyan-200 dark:hover:bg-cyan-700/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Log Activity
            </button>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li>
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-cyan-100 dark:bg-cyan-800/30 rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Email</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Sent pricing details to client</p>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="text-sm text-gray-500 dark:text-gray-400">3 days ago</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 dark:bg-green-800/30 rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Call</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Discussed implementation timeline with John Doe</p>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="text-sm text-gray-500 dark:text-gray-400">1 week ago</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection 