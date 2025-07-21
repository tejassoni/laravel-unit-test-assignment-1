@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Customer List</h1>
            <a href="{{ route('customers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Customer
            </a>
        </div>

        @if ($customers->isEmpty())
            <p class="text-gray-600">No Customers yet. Why not create one?</p>
        @else
         <table id="tbl" class="w-full table-fixed display cell-border row-border stripe">
                    <thead>
                        <tr>
                            <th>FirstName</th>
                            <th>LastName</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                    </thead>
            <tbody> 
                @foreach ($customers as $customer)
                     <tr>
                                <td class="text-center">{{ $customer->firstname }}</td>
                                <td class="text-center">{{ $customer->lastname }}</td>
                                <td class="text-center">{{ $customer->email }}</td>
                                <td class="text-center">{{ $customer->mobile }}</td>
                                <td class="text-center">{{ $customer->gender }}</td>
                        <td>
                            <div class="flex space-x-2"> {{-- Add this flex container with space-x-2 --}}
                            <a href="{{ route('customers.edit', $customer->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-sm">Edit</a> 
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hovesr:bg-red-600 text-white py-1 px-3 rounded text-sm">Delete</button>
                            </form>
                            <a title="show" href="{{ route('customers.show', $customer->id) }}"
                                                    class="bg-green-500 hovesr:bg-red-600 text-white py-1 px-3 rounded text-sm">
                                                    Show
                             </a>
                              </div> {{-- End of flex container --}}
                        </td>
                    </tr>    
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection