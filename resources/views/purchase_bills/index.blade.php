<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Purchase Bills
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-2xl font-semibold">Purchase Bill List</h3>
                        <a href="{{ route('purchase_bills.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Purchase Bill
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
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Bill Number</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Purchase Date</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Supplier</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($purchaseBills as $bill)
                                    <tr class="border-b">
                                        <td class="py-3 px-4">{{ $bill->batch_number }}</td>
                                        <td class="py-3 px-4">{{ $bill->purchase_date->format('d-m-Y') }}</td>
                                        <td class="py-3 px-4">{{ $bill->supplier->name }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bill->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('purchase_bills.show', $bill->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            {{-- Edit and Delete functionality will be more complex and added with the create/edit views --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No purchase bills found.</td>
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