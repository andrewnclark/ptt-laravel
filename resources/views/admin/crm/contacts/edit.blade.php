@extends('layouts.admin')

@section('title', 'Edit Contact')
@section('header', 'Edit Contact')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page header section with reduced padding and consistent styling -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Contact</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update contact information</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.crm.contacts.show', $contact->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Contact
                </a>
                <a href="{{ route('admin.crm.contacts.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Contacts
                </a>
            </div>
        </div>
        
        <!-- Main card with Livewire component -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @livewire('admin.crm.edit-contact-form', ['contact' => $contact])
            </div>
        </div>

        <!-- Danger Zone with updated styling -->
        <div class="mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete this contact</h3>
                    <div class="max-w-xl text-sm text-gray-500 dark:text-gray-400">
                        <p>Once you delete a contact, it cannot be recovered. All related data will be permanently removed.</p>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('admin.crm.contacts.destroy', $contact->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-red-700 dark:text-red-300 uppercase tracking-widest bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                               onclick="return confirm('Are you sure you want to delete this contact?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Contact
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 