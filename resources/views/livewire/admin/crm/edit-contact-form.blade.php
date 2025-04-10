<div>
    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First name</label>
                <input type="text" wire:model="first_name" id="first_name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last name</label>
                <input type="text" wire:model="last_name" id="last_name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email address</label>
                <input type="email" wire:model="email" id="email" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone number</label>
                <input type="text" wire:model="phone" id="phone" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company</label>
                <select wire:model="company_id" id="company_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    <option value="">Select a company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Job title</label>
                <input type="text" wire:model="job_title" id="job_title" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                @error('job_title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select wire:model="is_active" id="is_active" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="ml-6">
                        <label for="is_primary" class="flex items-center space-x-3 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <input type="checkbox" wire:model="is_primary" id="is_primary" class="rounded border-gray-300 text-cyan-600 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50">
                            <span>Primary Contact</span>
                        </label>
                        @error('is_primary') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="sm:col-span-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                <textarea wire:model="notes" id="notes" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600"></textarea>
                @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('admin.crm.contacts.show', $contact->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Save Changes
            </button>
        </div>
    </form>
</div> 