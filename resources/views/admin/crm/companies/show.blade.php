@extends('layouts.admin')

@section('title', $company->name)
@section('header', 'Company Details')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View detailed information about this company</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.crm.companies.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Companies
                </a>
                <a href="{{ route('admin.crm.companies.edit', $company) }}" class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 dark:bg-cyan-700 border border-transparent rounded-md font-medium text-sm text-white hover:bg-cyan-700 dark:hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Company
                </a>
            </div>
        </div>
            
        <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Company Information</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Details about the company profile</p>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Company Name</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $company->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($company->email)
                                <a href="mailto:{{ $company->email }}" class="text-cyan-600 dark:text-cyan-400 hover:underline">{{ $company->email }}</a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Not provided</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($company->phone)
                                <a href="tel:{{ $company->phone }}" class="text-cyan-600 dark:text-cyan-400 hover:underline">{{ $company->phone }}</a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Not provided</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank" class="text-cyan-600 dark:text-cyan-400 hover:underline">{{ $company->website }}</a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Not provided</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'lead' => 'bg-blue-100 dark:bg-blue-800/30 text-blue-800 dark:text-blue-300',
                                    'prospect' => 'bg-yellow-100 dark:bg-yellow-800/30 text-yellow-800 dark:text-yellow-300',
                                    'customer' => 'bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300',
                                    'churned' => 'bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300',
                                ];
                                $statusColor = $statusColors[$company->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ \App\Models\Crm\Company::getStatusOptions()[$company->status] }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Industry</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $company->industry ?: 'Not specified' }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created On</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->created_at->format('F j, Y') }}</dd>
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $company->description ?: 'No description provided.' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- Related Contacts Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Contacts</h2>
                <livewire:admin.crm.add-contact-modal :company-id="$company->id" />
            </div>
            <livewire:admin.crm.company-contacts-list :company-id="$company->id" />
        </div>

        <!-- Company Activities Section -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Activities</h2>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
                @if($company->getRecentActivities()->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($company->getRecentActivities() as $activity)
                            <li class="px-4 py-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-cyan-100 dark:bg-cyan-800/30">
                                            @switch($activity->type)
                                                @case('created')
                                                    <svg class="h-5 w-5 text-cyan-600 dark:text-cyan-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    @break
                                                @case('updated')
                                                    <svg class="h-5 w-5 text-cyan-600 dark:text-cyan-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    @break
                                                @case('status_changed')
                                                    <svg class="h-5 w-5 text-cyan-600 dark:text-cyan-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                    @break
                                                @case('note_added')
                                                    <svg class="h-5 w-5 text-cyan-600 dark:text-cyan-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    @break
                                                @default
                                                    <svg class="h-5 w-5 text-cyan-600 dark:text-cyan-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $activity->description }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                            @if($activity->user)
                                                <span>{{ $activity->user->name }}</span>
                                            @else
                                                <span>System</span>
                                            @endif
                                            <span class="mx-1">•</span>
                                            <span>{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-4 py-5 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No activities recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 