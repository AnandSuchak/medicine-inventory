<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customers
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-2xl font-semibold">Customer List</h3>
                        <a href="{{ route('customers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Customer
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Shop Name</th> <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Phone</th>
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">GST</th> <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">PAN</th> <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($customers as $customer)
                                    <tr class="border-b">
                                        <td class="text-left py-3 px-4">{{ $customer->shop_name }}</td> <td class="text-left py-3 px-4">{{ $customer->phone }}</td>
                                        <td class="text-left py-3 px-4">{{ $customer->email }}</td>
                                        <td class="text-left py-3 px-4">{{ $customer->gst ?? 'N/A' }}</td> <td class="text-left py-3 px-4">{{ $customer->pan ?? 'N/A' }}</td> <td class="text-left py-3 px-4">
                                            <a href="{{ route('customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-2">Edit</a>
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No customers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>