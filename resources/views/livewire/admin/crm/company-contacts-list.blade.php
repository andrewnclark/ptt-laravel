<div>
    <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        @if($company->contacts->count() > 0)
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($company->contacts as $contact)
                    <li>
                        <a href="{{ route('admin.crm.contacts.show', $contact) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-cyan-100 dark:bg-cyan-800/30 rounded-full flex items-center justify-center">
                                            <span class="text-cyan-700 dark:text-cyan-300 text-sm font-medium">
                                                {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $contact->first_name }} {{ $contact->last_name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $contact->job_title ?: 'No title' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-5 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">No contacts found for this company.</p>
            </div>
        @endif
    </div>
</div> 