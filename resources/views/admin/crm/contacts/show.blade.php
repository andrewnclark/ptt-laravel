@extends('layouts.admin')

@section('title', 'Contact Details - ' . $contact->full_name)
@section('header', 'Contact Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Details</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View detailed information about this contact</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.crm.contacts.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Contacts
            </a>
            <a href="{{ route('admin.crm.contacts.edit', $contact->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 dark:bg-cyan-700 border border-transparent rounded-md font-medium text-sm text-white hover:bg-cyan-700 dark:hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Contact
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 bg-cyan-100 dark:bg-cyan-800/30 rounded-full flex items-center justify-center">
                    <span class="text-cyan-700 dark:text-cyan-300 text-xl font-bold">
                        {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                    </span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ $contact->full_name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contact->is_active ? 'bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300' }}">
                            {{ $contact->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($contact->is_primary)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-800/30 text-blue-800 dark:text-blue-300">
                                Primary
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <dl>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        @if($contact->company)
                            <a href="{{ route('admin.crm.companies.show', $contact->company->id) }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300">
                                {{ $contact->company->name }}
                            </a>
                        @else
                            <span class="text-gray-500 dark:text-gray-400">No company associated</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white dark:bg-gray-800 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Job title</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        {{ $contact->job_title ?: 'Not specified' }}
                    </dd>
                </div>
                @if($contact->department)
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        {{ $contact->department }}
                    </dd>
                </div>
                @endif
                <div class="{{ $contact->department ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700/50' }} px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        @if($contact->email)
                            <a href="mailto:{{ $contact->email }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300">
                                {{ $contact->email }}
                            </a>
                        @else
                            <span class="text-gray-500 dark:text-gray-400">No email provided</span>
                        @endif
                    </dd>
                </div>
                <div class="{{ (!$contact->department && $contact->email) ? 'bg-gray-50 dark:bg-gray-700/50' : 'bg-white dark:bg-gray-800' }} px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        @if($contact->phone)
                            <a href="tel:{{ $contact->phone }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300">
                                {{ $contact->phone }}
                            </a>
                        @else
                            <span class="text-gray-500 dark:text-gray-400">No phone number provided</span>
                        @endif
                    </dd>
                </div>
                @if($contact->mobile)
                <div class="{{ (!$contact->department && !$contact->email && $contact->phone) ? 'bg-gray-50 dark:bg-gray-700/50' : 'bg-white dark:bg-gray-800' }} px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        <a href="tel:{{ $contact->mobile }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300">
                            {{ $contact->mobile }}
                        </a>
                    </dd>
                </div>
                @endif
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created on</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        {{ $contact->created_at->format('F j, Y') }}
                    </dd>
                </div>
                @if($contact->notes)
                <div class="bg-white dark:bg-gray-800 px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white md:mt-0 md:col-span-2">
                        {!! nl2br(e($contact->notes)) !!}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Related Opportunities Section -->
    @if($opportunities->count() > 0)
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Related Opportunities</h2>
            <a href="{{ route('admin.crm.opportunities.create') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-800/30 hover:bg-cyan-200 dark:hover:bg-cyan-700/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Opportunity
            </a>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($opportunities as $opportunity)
                <li>
                    <a href="{{ route('admin.crm.opportunities.show', $opportunity->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-cyan-600 dark:text-cyan-400">{{ $opportunity->title }}</p>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($opportunity->status === 'won')
                                            bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300
                                        @elseif($opportunity->status === 'lost')
                                            bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300
                                        @else
                                            bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300
                                        @endif">
                                        {{ ucfirst($opportunity->status) }}
                                    </span>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $opportunity->formatted_amount }}</p>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-between">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    @if($opportunity->status === 'won' || $opportunity->status === 'lost')
                                        <p>Closed: {{ $opportunity->closed_at ? $opportunity->closed_at->format('F j, Y') : 'N/A' }}</p>
                                    @else
                                        <p>Expected close: {{ $opportunity->expected_close_date ? $opportunity->expected_close_date->format('F j, Y') : 'Not set' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @else
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Related Opportunities</h2>
            <a href="{{ route('admin.crm.opportunities.create') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-800/30 hover:bg-cyan-200 dark:hover:bg-cyan-700/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Opportunity
            </a>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-500 dark:text-gray-400">No opportunities associated with this contact yet.</p>
        </div>
    </div>
    @endif
</div>
@endsection 