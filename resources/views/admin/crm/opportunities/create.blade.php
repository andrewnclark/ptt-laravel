@extends('layouts.admin')

@section('title', 'Add New Opportunity')
@section('header', 'Add New Opportunity')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page header section with reduced padding and consistent styling -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Add New Opportunity</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new sales opportunity</p>
            </div>
            <div>
                <a href="{{ route('admin.crm.opportunities.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Opportunities
                </a>
            </div>
        </div>
        
        <!-- Main card with consistent styling -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form action="#" method="POST">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opportunity Name</label>
                            <input type="text" name="name" id="name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                            <div class="relative rounded-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                </div>
                                <input type="text" name="amount" id="amount" class="block w-full pl-7 pr-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">USD</span>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company</label>
                            <select id="company_id" name="company_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="">Select a company</option>
                                <option value="1">Acme Inc</option>
                                <option value="2">Globex Corporation</option>
                                <option value="3">Initech</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="contact_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Contact</label>
                            <select id="contact_id" name="contact_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="">Select a contact</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Bob Johnson</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="stage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stage</label>
                            <select id="stage" name="stage" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="lead">Lead</option>
                                <option value="discovery">Discovery</option>
                                <option value="proposal">Proposal</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="won">Won</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="expected_close_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expected Close Date</label>
                            <input type="date" name="expected_close_date" id="expected_close_date" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Source</label>
                            <select id="source" name="source" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="social_media">Social Media</option>
                                <option value="email_campaign">Email Campaign</option>
                                <option value="cold_call">Cold Call</option>
                                <option value="event">Event/Conference</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="probability" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Probability (%)</label>
                            <input type="number" name="probability" id="probability" min="0" max="100" value="50" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Brief description of the opportunity and any notes.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.crm.opportunities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Create Opportunity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 