<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- for view ticket --}}
            <div class="py-5">

                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    {{-- Content Section --}}
                    <div class="p-6 text-gray-900">
                        
                        {{-- Responsive Header Container --}}
                        {{-- Mobile: Stacked Vertical | Desktop: Row with space between --}}
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                            
                            <h3 class="text-lg font-semibold text-center md:text-left">
                                {{ __('Welcome to your dashboard!') }}
                            </h3>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('tickets.index') }}"
                                   class="text-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto transition">
                                    My Tickets
                                </a>
                                <a href="{{ route('tickets.create') }}"
                                   class="text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto transition">
                                    Create Ticket
                                </a>
                            </div>
                        </div>

                        <p class="text-gray-600">
                            You can view your tickets or create a new support ticket using the buttons above.
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>