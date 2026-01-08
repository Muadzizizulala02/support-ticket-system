<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ $ticket->id }}
            </h2>
            <a 
                href="{{ route('tickets.index') }}" 
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
                Back to Tickets
            </a>
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
                    
                    <!-- Ticket Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                    {{ $ticket->title }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Created {{ $ticket->created_at->format('F j, Y \a\t g:i A') }}
                                    by {{ $ticket->user->name }}
                                </p>
                            </div>
                            <div>
                                @if ($ticket->status === 'open')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Open
                                    </span>
                                @elseif ($ticket->status === 'in_progress')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        In Progress
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Closed
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-6"></div>

                    <!-- Ticket Description -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Description</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-6"></div>

                    <!-- Change Status -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Change Status</h4>
                        <form method="POST" action="{{ route('tickets.update-status', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            
                            <div class="flex items-center space-x-4">
                                <select 
                                    name="status" 
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                
                                <button 
                                    type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>