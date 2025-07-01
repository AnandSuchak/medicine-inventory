<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Suppliers
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-2xl font-semibold">Supplier List</h3>
                        <a href="{{ route('suppliers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Supplier
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
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Name</th>
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Phone</th>
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                    <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Drug License</th> <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Address</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($suppliers as $supplier)
                                    <tr class="border-b">
                                        <td class="text-left py-3 px-4">{{ $supplier->name }}</td>
                                        <td class="text-left py-3 px-4">{{ $supplier->phone }}</td>
                                        <td class="text-left py-3 px-4">{{ $supplier->email }}</td>
                                        <td class="text-left py-3 px-4">{{ $supplier->drug_license ?? 'N/A' }}</td> <td class="text-left py-3 px-4">{{ $supplier->address }}</td>
                                        <td class="text-left py-3 px-4">
                                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-2">Edit</a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No suppliers found.</td>
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