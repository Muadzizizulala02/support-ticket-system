{{-- dashboard auto genrated from breeze (plug and play je) --}}


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


                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">


                    {{-- default dashboard content --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        {{-- <div class="p-6 text-gray-900">
                                {{ __("You're logged in!") }}
                            </div> --}}
                            
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">{{ __('Welcome to your dashboard!') }}</h3>
                                <div class="space-x-2">
                                    <a href="{{ route('tickets.index') }}"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        My Tickets
                                    </a>
                                    <a href="{{ route('tickets.create') }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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

</x-app-layout>
