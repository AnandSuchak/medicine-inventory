<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customer Details
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">{{ $customer->shop_name }}</h3> <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                            <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong>GST:</strong> {{ $customer->gst ?? 'N/A' }}</p> <p><strong>PAN:</strong> {{ $customer->pan ?? 'N/A' }}</p> </div>
                        <div class="md:col-span-2">
                            <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('customers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>