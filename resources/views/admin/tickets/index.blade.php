<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - All Tickets') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($tickets->count() > 0)
                        <!-- Tickets Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                #{{ $ticket->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $ticket->user->name }}
                                                <span class="text-gray-500 text-xs block">
                                                    {{ $ticket->user->email }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $ticket->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- Status form with inline update -->
                                                {{-- routes to update ticket status are defined in web.php --}}
                                                <form method="POST" action="{{ route('admin.tickets.update-status', $ticket) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select 
                                                        name="status" 
                                                        onchange="this.form.submit()"
                                                        class="text-sm rounded-full border-0 focus:ring-2 focus:ring-blue-500
                                                            @if($ticket->status === 'open') bg-green-100 text-green-800
                                                            @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif"
                                                    >
                                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>
                                                            Open
                                                        </option>
                                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>
                                                            In Progress
                                                        </option>
                                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>
                                                            Closed
                                                        </option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <p class="text-gray-500">No tickets in the system yet.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>