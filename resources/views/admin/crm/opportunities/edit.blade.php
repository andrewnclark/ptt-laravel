@extends('layouts.admin')

@section('title', 'Edit Opportunity')
@section('header', 'Edit Opportunity')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page header section with reduced padding and consistent styling -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Opportunity</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update opportunity information</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.crm.opportunities.show', 1) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Opportunity
                </a>
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
                            <input type="text" name="name" id="name" value="Enterprise Software Package" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                            <div class="relative rounded-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                </div>
                                <input type="text" name="amount" id="amount" value="75000" class="block w-full pl-7 pr-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">USD</span>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company</label>
                            <select id="company_id" name="company_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="">Select a company</option>
                                <option value="1" selected>Acme Inc</option>
                                <option value="2">Globex Corporation</option>
                                <option value="3">Initech</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="contact_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Contact</label>
                            <select id="contact_id" name="contact_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="">Select a contact</option>
                                <option value="1" selected>John Doe</option>
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
                                <option value="negotiation" selected>Negotiation</option>
                                <option value="won">Won</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="expected_close_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expected Close Date</label>
                            <input type="date" name="expected_close_date" id="expected_close_date" value="2023-04-30" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Source</label>
                            <select id="source" name="source" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                                <option value="website">Website</option>
                                <option value="referral" selected>Referral</option>
                                <option value="social_media">Social Media</option>
                                <option value="email_campaign">Email Campaign</option>
                                <option value="cold_call">Cold Call</option>
                                <option value="event">Event/Conference</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="probability" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Probability (%)</label>
                            <input type="number" name="probability" id="probability" min="0" max="100" value="75" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">Enterprise software implementation for accounting and financial reporting systems, including migration from legacy systems, staff training, and ongoing support.

Client is looking for a complete solution that integrates with their existing CRM and ERP systems.</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.crm.opportunities.show', 1) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Next Steps Section -->
        <div class="mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Next Steps</h2>
                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-900/30 hover:bg-cyan-200 dark:hover:bg-cyan-900/50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Step
                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-grow mr-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="step-1" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
                                    <label for="step-1" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Schedule follow-up call
                                    </label>
                                </div>
                                <div class="mt-1 flex items-center ml-7">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 dark:text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Due in 2 days</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 flex space-x-2">
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-grow mr-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="step-2" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
                                    <label for="step-2" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Send revised proposal
                                    </label>
                                </div>
                                <div class="mt-1 flex items-center ml-7">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 dark:text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Due in 5 days</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 flex space-x-2">
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-grow mr-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="step-3" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
                                    <label for="step-3" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Get final approval
                                    </label>
                                </div>
                                <div class="mt-1 flex items-center ml-7">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 dark:text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Due in 10 days</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 flex space-x-2">
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Danger Zone with updated styling -->
        <div class="mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete this opportunity</h3>
                    <div class="max-w-xl text-sm text-gray-500 dark:text-gray-400">
                        <p>Once you delete an opportunity, it cannot be recovered. All related data will be permanently removed.</p>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-red-700 dark:text-red-300 uppercase tracking-widest bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Opportunity
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 