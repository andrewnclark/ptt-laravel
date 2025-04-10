<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <form wire:submit.prevent="saveCompany">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                    <input type="text" id="name" wire:model="companyData.name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('companyData.name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" wire:model="companyData.email" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('companyData.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" id="phone" wire:model="companyData.phone" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('companyData.phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                    <input type="url" id="website" wire:model="companyData.website" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('companyData.website') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="industry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Industry</label>
                    <input type="text" id="industry" wire:model="companyData.industry" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('companyData.industry') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select id="status" wire:model="companyData.status" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('companyData.status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Active Status</label>
                    <select id="is_active" wire:model="companyData.is_active" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('companyData.is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea id="description" wire:model="companyData.description" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600"></textarea>
                    @error('companyData.description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('admin.crm.companies.show', $companyModel->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
