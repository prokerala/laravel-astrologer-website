<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contact Message Details') }}
            </h2>
            <x-secondary-button onclick="window.location.href='{{ route('admin.contacts.index') }}'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Messages
            </x-secondary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Contact Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                        <div class="flex space-x-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $contact->status === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $contact->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $contact->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $contact->status)) }}
                            </span>
                            @if(!$contact->is_read)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Unread
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contact->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">{{ $contact->email }}</a>
                            </dd>
                        </div>
                        @if($contact->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="tel:{{ $contact->phone }}" class="text-blue-600 hover:text-blue-800">{{ $contact->phone }}</a>
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Original Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Original Message</h3>
                    
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-gray-500">Subject</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $contact->subject }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Message</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap bg-gray-50 p-4 rounded-md">{{ $contact->message }}</dd>
                    </div>
                </div>
            </div>

            <!-- Status Update -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                    
                    <form method="POST" action="{{ route('admin.contacts.update-status', $contact) }}" class="flex items-center space-x-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="new" {{ $contact->status === 'new' ? 'selected' : '' }}>New</option>
                                <option value="in_progress" {{ $contact->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $contact->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                        
                        <x-primary-button type="submit">
                            Update Status
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <!-- Admin Response -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Response</h3>
                    
                    @if($contact->hasResponse())
                        <!-- Existing Response -->
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-sm font-medium text-green-800">Previous Response</h4>
                                <span class="text-xs text-green-600">
                                    {{ $contact->responded_at->format('F j, Y \a\t g:i A') }}
                                    @if($contact->respondedBy)
                                        by {{ $contact->respondedBy->name }}
                                    @endif
                                </span>
                            </div>
                            <p class="text-sm text-green-700 whitespace-pre-wrap">{{ $contact->admin_response }}</p>
                        </div>
                    @endif
                    
                    <!-- Response Form -->
                    <form method="POST" action="{{ route('admin.contacts.respond', $contact) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-input-label for="admin_response" :value="__('Your Response')" />
                            <textarea id="admin_response" name="admin_response" rows="6" 
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      placeholder="Type your response here..." required>{{ old('admin_response') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('admin_response')" />
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="send_email" name="send_email" value="1" checked 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="send_email" class="ml-2 text-sm text-gray-700">
                                Send email notification to customer
                            </label>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <x-secondary-button type="button" onclick="document.getElementById('admin_response').value = '';">
                                Clear
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ $contact->hasResponse() ? 'Update Response' : 'Send Response' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="flex space-x-3">
                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Directly
                        </a>
                        
                        @if($contact->phone)
                        <a href="tel:{{ $contact->phone }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Call
                        </a>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this contact message?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>