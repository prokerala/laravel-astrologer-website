<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Messages</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $counts['total'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12h6m-6 4h6m2-6V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6h14z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Unread</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $counts['unread'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Unresponded</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $counts['unresponded'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">New</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $counts['new'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    <div>
                        <label for="read_status" class="block text-sm font-medium text-gray-700">Read Status</label>
                        <select name="read_status" id="read_status" class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All</option>
                            <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                    </div>

                    <div>
                        <label for="response_status" class="block text-sm font-medium text-gray-700">Response Status</label>
                        <select name="response_status" id="response_status" class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All</option>
                            <option value="unresponded" {{ request('response_status') === 'unresponded' ? 'selected' : '' }}>Unresponded</option>
                            <option value="responded" {{ request('response_status') === 'responded' ? 'selected' : '' }}>Responded</option>
                        </select>
                    </div>

                    <div>
                        <x-primary-button type="submit">Filter</x-primary-button>
                        <a href="{{ route('admin.contacts.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <form id="bulk-form" method="POST" action="{{ route('admin.contacts.bulk-action') }}">
                    @csrf
                    <div class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="bulk-action" class="block text-sm font-medium text-gray-700">Bulk Actions</label>
                            <select name="action" id="bulk-action" class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Action</option>
                                <option value="mark_read">Mark as Read</option>
                                <option value="mark_unread">Mark as Unread</option>
                                <option value="change_status">Change Status</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>

                        <div id="status-field" style="display: none;">
                            <label for="bulk-status" class="block text-sm font-medium text-gray-700">New Status</label>
                            <select name="status" id="bulk-status" class="mt-1 block border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="new">New</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>

                        <div>
                            <x-secondary-button type="submit" onclick="return confirm('Are you sure you want to perform this action?')">
                                Apply
                            </x-secondary-button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Messages Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($contacts as $contact)
                            <tr class="hover:bg-gray-50 {{ !$contact->is_read ? 'bg-blue-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" form="bulk-form" class="contact-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                                {{ $contact->name }}
                                                @if(!$contact->is_read)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        New
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                                            @if($contact->phone)
                                                <div class="text-sm text-gray-500">{{ $contact->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $contact->subject }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $contact->status === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $contact->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $contact->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $contact->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($contact->hasResponse())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Responded
                                        </span>
                                        @if($contact->responded_at)
                                            <div class="text-xs text-gray-500">{{ $contact->responded_at->diffForHumans() }}</div>
                                        @endif
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            No Response
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contact->created_at->format('M d, Y') }}
                                    <div class="text-xs">{{ $contact->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No contact messages found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4">
                    {{ $contacts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.contact-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Show/hide status field based on action selection
        document.getElementById('bulk-action').addEventListener('change', function() {
            const statusField = document.getElementById('status-field');
            if (this.value === 'change_status') {
                statusField.style.display = 'block';
            } else {
                statusField.style.display = 'none';
            }
        });
    </script>
    @endpush
</x-app-layout>
