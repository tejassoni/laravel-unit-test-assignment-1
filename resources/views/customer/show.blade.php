@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-bold mb-6">Customer Details: {{ $customer->firstname }} {{ $customer->lastname }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Firstname --}}
            <div class="mb-4">
                <p class="block text-gray-700 text-sm font-bold mb-2">Firstname:</p>
                <p class="text-gray-900 text-lg">{{ $customer->firstname }}</p>
            </div>

            {{-- Lastname --}}
            <div class="mb-4">
                <p class="block text-gray-700 text-sm font-bold mb-2">Lastname:</p>
                <p class="text-gray-900 text-lg">{{ $customer->lastname }}</p>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <p class="block text-gray-700 text-sm font-bold mb-2">Email:</p>
                <p class="text-gray-900 text-lg">{{ $customer->email }}</p>
            </div>

            {{-- Mobile --}}
            <div class="mb-4">
                <p class="block text-gray-700 text-sm font-bold mb-2">Mobile:</p>
                <p class="text-gray-900 text-lg">{{ $customer->mobile }}</p>
            </div>

            {{-- Gender Field --}}
            <div class="mb-4">
                <p class="block text-gray-700 text-sm font-bold mb-2">Gender:</p>
                <p class="text-gray-900 text-lg capitalize">{{ $customer->gender }}</p> {{-- Capitalize for display --}}
            </div>

            {{-- Address Field --}}
            <div class="mb-6 col-span-1 md:col-span-2"> {{-- Make address span two columns on medium screens --}}
                <p class="block text-gray-700 text-sm font-bold mb-2">Address:</p>
                <p class="text-gray-900 text-lg break-words">{{ $customer->address ?? 'N/A' }}</p>
            </div>

            {{-- Hobbies Field --}}
            <div class="mb-4 col-span-1 md:col-span-2">
                <p class="block text-gray-700 text-sm font-bold mb-2">Hobbies:</p>
                @if (!empty($customer->hobbies))
                    @php
                        // Ensure hobbies is an array for iteration, even if it's stored as null/empty
                        $displayHobbies = is_array($customer->hobbies) ? $customer->hobbies : json_decode($customer->hobbies ?? '[]', true);
                    @endphp
                    <ul class="list-disc list-inside text-gray-900 text-lg">
                        @forelse ($displayHobbies as $hobby)
                            <li>{{ $hobby }}</li>
                        @empty
                            <li>No hobbies selected.</li>
                        @endforelse
                    </ul>
                @else
                    <p class="text-gray-600 text-lg">No hobbies selected.</p>
                @endif
            </div>
        </div> {{-- End of grid --}}

        <div class="mt-8 flex space-x-2"> {{-- Added flex and space-x-2 for button spacing --}}
            <a href="{{ route('customers.edit', $customer->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit Customer
            </a>
            <a href="{{ route('customers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Back to List
            </a>
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Delete Customer
                </button>
            </form>
        </div>
    </div>
@endsection