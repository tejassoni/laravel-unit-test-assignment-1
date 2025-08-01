@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">Create Edit Customer</h1>

        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="firstname" class="block text-gray-700 text-sm font-bold mb-2">Firstname*:</label>
                <input type="text" name="firstname" id="firstname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('firstname') border-red-500 @enderror" value="{{ old('firstname', $customer->firstname) }}" placeholder="Enter Firstname" required>
                @error('firstname')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">Lastname*:</label>
                <input type="text" name="lastname" id="lastname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lastname') border-red-500 @enderror" value="{{ old('lastname', $customer->lastname) }}" placeholder="Enter Lastname" required>
                @error('lastname')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email*:</label>
                <input type="text" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email', $customer->email) }}" placeholder="Enter Email" required>
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="mobile" class="block text-gray-700 text-sm font-bold mb-2">Mobile*:</label>
                <input type="text" name="mobile" id="mobile" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('mobile') border-red-500 @enderror" value="{{ old('mobile', $customer->mobile) }}" placeholder="Enter Mobile" required>
                @error('mobile')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- GENDER FIELD --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Gender*:</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="male" class="form-radio" {{ old('gender', $customer->gender) == 'male' ? 'checked' : '' }} required>
                        <span class="ml-2 text-gray-700">Male</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="gender" value="female" class="form-radio" {{ old('gender', $customer->gender) == 'female' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Female</span>
                    </label>
                </div>
                @error('gender')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

              <div class="mb-6">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address (Optional):</label>
                <textarea name="address" id="address" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- HOBBIES FIELD --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Hobbies (Optional):</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
                    @php
                        $hobbiesOptions = ['Reading', 'Gaming', 'Sports', 'Cooking', 'Traveling', 'Photography', 'Music', 'Gardening', 'Coding'];
                        // Get selected hobbies, defaulting to an empty array if null
                        $selectedHobbies = old('hobbies', $customer->hobbies ?? []);
                    @endphp
                    @foreach ($hobbiesOptions as $hobby)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="hobbies[]" value="{{ $hobby }}" class="form-checkbox"
                                   {{ in_array($hobby, $selectedHobbies) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">{{ $hobby }}</span>
                        </label>
                    @endforeach
                </div>
                @error('hobbies')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
                @error('hobbies.*')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>       

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline btn-sm">
                    Update
                </button>
                <a href="{{ route('customers.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-sm btn-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection