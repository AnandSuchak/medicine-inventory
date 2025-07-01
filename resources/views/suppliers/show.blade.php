<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Supplier Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">{{ $supplier->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Phone:</strong> {{ $supplier->phone }}</p>
                            <p><strong>Email:</strong> {{ $supplier->email }}</p>
                        </div>
                        <div>
                            <p><strong>GSTIN:</strong> {{ $supplier->gstin ?? 'N/A' }}</p>
                            <p><strong>Drug License:</strong> {{ $supplier->drug_license ?? 'N/A' }}</p> </div>
                        <div class="md:col-span-2">
                            <p><strong>Address:</strong> {{ $supplier->address }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('suppliers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>