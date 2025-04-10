<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 flash-message transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 flash-message transition-opacity duration-500">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page header section with reduced padding and consistent styling -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Companies</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your client and prospect companies</p>
        </div>
        <div>
            <a href="{{ route('admin.crm.companies.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Company
            </a>
        </div>
    </div>
    
    <!-- Main card with consistent styling -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Filter section -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/3">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               id="search" 
                               placeholder="Search companies..." 
                               class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    </div>
                </div>
                
                <div class="w-full md:w-1/3">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="status" id="status" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        <option value="">All Statuses</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-1/3">
                    <label for="trashedFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Trashed 
                        @if($trashedCount > 0)
                            <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                {{ $trashedCount }}
                            </span>
                        @endif
                    </label>
                    <select wire:model.live="trashedFilter" id="trashedFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        <option value="exclude">Exclude Trashed</option>
                        <option value="include">Include Trashed</option>
                        <option value="only">Only Trashed</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Table section -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                            <div class="flex items-center space-x-1">
                                <span>Company</span>
                                @if($sortField === 'name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Contact Info
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                            <div class="flex items-center space-x-1">
                                <span>Status</span>
                                @if($sortField === 'status')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            <div class="flex items-center space-x-1">
                                <span>Created</span>
                                @if($sortField === 'created_at')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($companies as $company)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($company->logo)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <span class="text-blue-700 dark:text-blue-300 font-bold text-lg">{{ substr($company->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('admin.crm.companies.show', $company) }}" class="hover:text-cyan-600 dark:hover:text-cyan-400">
                                                {{ $company->name }}
                                            </a>
                                        </div>
                                        @if($company->industry)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->industry }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    @if($company->phone)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $company->phone }}
                                        </div>
                                    @endif
                                    @if($company->email)
                                        <div class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $company->email }}
                                        </div>
                                    @endif
                                    @if($company->website)
                                        <div class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            <a href="{{ $company->website }}" target="_blank" class="text-cyan-600 hover:text-cyan-700 dark:text-cyan-400 dark:hover:text-cyan-300 truncate max-w-xs">
                                                {{ $company->website }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $company->status === \App\Models\Crm\Company::STATUS_LEAD ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : '' }}
                                    {{ $company->status === \App\Models\Crm\Company::STATUS_PROSPECT ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200' : '' }}
                                    {{ $company->status === \App\Models\Crm\Company::STATUS_CUSTOMER ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200' : '' }}
                                    {{ $company->status === \App\Models\Crm\Company::STATUS_CHURNED ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                ">
                                    {{ $statusOptions[$company->status] }}
                                </span>
                                
                                @if($company->trashed())
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Deleted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $company->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    <a href="{{ route('admin.crm.companies.edit', $company) }}" class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    @if($company->trashed())
                                        <button 
                                            wire:click="directRestore({{ $company->id }}, {{ $trashedCount <= 1 ? 'true' : 'false' }})"
                                            wire:loading.attr="disabled"
                                            wire:target="directRestore({{ $company->id }}, {{ $trashedCount <= 1 ? 'true' : 'false' }})"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 relative" 
                                            title="Restore from trash"
                                        >
                                            <div wire:loading wire:target="directRestore({{ $company->id }}, {{ $trashedCount <= 1 ? 'true' : 'false' }})" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800 bg-opacity-60 dark:bg-opacity-60 rounded-full">
                                                <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    @else
                                        <button 
                                            wire:click="toggleDeletedState({{ $company->id }})" 
                                            wire:loading.attr="disabled"
                                            wire:target="toggleDeletedState({{ $company->id }})"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 relative"
                                            title="Move to trash"
                                        >
                                            <div wire:loading wire:target="toggleDeletedState({{ $company->id }})" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800 bg-opacity-60 dark:bg-opacity-60 rounded-full">
                                                <svg class="animate-spin h-4 w-4 text-cyan-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 dark:hover:text-cyan-300 focus:outline-none" title="More Actions">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             @click.away="open = false" 
                                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-gray-700 z-50 max-h-80 overflow-y-auto" 
                                             style="min-width: 200px;"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95">
                                            <div class="py-1">
                                                @if($company->trashed())
                                                    <button @click="open = false" wire:click="confirmForceDelete({{ $company->id }})" class="w-full text-left px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 dark:hover:bg-opacity-50">
                                                        Delete Permanently
                                                    </button>
                                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                                @endif
                                                <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400">Change Status</div>
                                                @foreach($statusOptions as $value => $label)
                                                    @if($value !== $company->status)
                                                        <button @click="open = false" wire:click="changeStatus({{ $company->id }}, '{{ $value }}')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 whitespace-normal">
                                                            {{ $label }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No companies found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination section -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete confirmation modal -->
    @if($deletingCompany)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Deletion</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete <span class="font-semibold">{{ $deletingCompany->name }}</span>? This company will be moved to trash.
                </p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="cancelDeletion" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800">
                        Cancel
                    </button>
                    <button wire:click="deleteCompany" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Force delete confirmation modal -->
    @if($forceDeleteCompanyId)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Permanent Deletion</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to permanently delete this company? This action <span class="font-bold text-red-600 dark:text-red-400">cannot be undone</span>.
                </p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="cancelForceDelete" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 dark:focus:ring-offset-gray-800">
                        Cancel
                    </button>
                    <button wire:click="forceDeleteCompany" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                        Permanently Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Enable x-data to access Livewire properties directly
            window.addEventListener('livewire:navigated', () => {
                Alpine.initTree(document.body);
            });
            
            // Add a simple flash message handler for the session flash messages
            if (document.querySelector('.flash-message')) {
                setTimeout(() => {
                    const flashMessages = document.querySelectorAll('.flash-message');
                    flashMessages.forEach(el => {
                        el.style.opacity = '0';
                        setTimeout(() => {
                            el.remove();
                        }, 500);
                    });
                }, 3000);
            }
        });
    </script>
</div> 