<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <form wire:submit.prevent="saveCompany">
            <!-- Company Information Section -->
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Company Information</h2>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                    <input type="text" id="name" wire:model="company.name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('company.name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" wire:model="company.email" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('company.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" id="phone" wire:model="company.phone" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('company.phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                    <input type="url" id="website" wire:model="company.website" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('company.website') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="industry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Industry</label>
                    <input type="text" id="industry" wire:model="company.industry" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                    @error('company.industry') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select id="status" wire:model="company.status" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('company.status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Active Status</label>
                    <select id="is_active" wire:model="company.is_active" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('company.is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea id="description" wire:model="company.description" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600"></textarea>
                    @error('company.description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Primary Contact Information Section -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Primary Contact Information</h2>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="contact_first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                        <input type="text" id="contact_first_name" wire:model="contact.first_name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                        <input type="text" id="contact_last_name" wire:model="contact.last_name" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" id="contact_email" wire:model="contact.email" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <input type="text" id="contact_phone" wire:model="contact.phone" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobile</label>
                        <input type="text" id="contact_mobile" wire:model="contact.mobile" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.mobile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Job Title</label>
                        <input type="text" id="contact_job_title" wire:model="contact.job_title" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.job_title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="contact_department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                        <input type="text" id="contact_department" wire:model="contact.department" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
                        @error('contact.department') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="contact_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea id="contact_notes" wire:model="contact.notes" rows="2" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600"></textarea>
                        @error('contact.notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end mt-6">
                <button type="button" wire:click="resetForm" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Create Company
                </button>
            </div>
        </form>
    </div>
</div>
