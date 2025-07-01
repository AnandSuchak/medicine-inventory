<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Customer
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('customers.update', $customer->id) }}" method="POST" id="customer-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="shop_name" class="block font-medium text-sm text-gray-700">Shop Name</label> <input type="text" name="shop_name" id="shop_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('shop_name', $customer->shop_name) }}" required>
                                @error('shop_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="phone" class="block font-medium text-sm text-gray-700">Phone</label>
                                <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email', $customer->email) }}">
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="gst" class="block font-medium text-sm text-gray-700">GST Number</label> <input type="text" name="gst" id="gst" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('gst', $customer->gst) }}">
                                @error('gst')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="pan" class="block font-medium text-sm text-gray-700">PAN Number</label> <input type="text" name="pan" id="pan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('pan', $customer->pan) }}">
                                @error('pan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block font-medium text-sm text-gray-700">Address</label>
                                <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $customer->address) }}</textarea>
                                @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('customers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3">
                                Update Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('customer-form');
            const gstInput = document.getElementById('gst');
            const panInput = document.getElementById('pan');

            form.addEventListener('submit', function (event) {
                if (gstInput.value.trim() === '' && panInput.value.trim() === '') {
                    event.preventDefault(); // Stop form submission
                    alert('Please provide either a GST Number or a PAN Number.');
                }
            });
        });
    </script>
</x-app-layout>