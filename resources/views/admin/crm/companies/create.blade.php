@extends('layouts.admin')

@section('title', 'Create Company')
@section('header', 'Create Company')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page header section with reduced padding and consistent styling -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Create New Company</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a new company to your CRM</p>
            </div>
            <div>
                <a href="{{ route('admin.crm.companies.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Companies
                </a>
            </div>
        </div>
        
        <!-- Livewire component for company creation -->
        <livewire:admin.crm.create-company-form />
    </div>
@endsection 