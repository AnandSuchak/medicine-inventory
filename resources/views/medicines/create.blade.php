<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Medicine
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name') }}" required>
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="mfg_company_name" class="block font-medium text-sm text-gray-700">Manufacturing Company</label> <input type="text" name="mfg_company_name" id="mfg_company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('mfg_company_name') }}" required>
                                @error('mfg_company_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="pack_size" class="block font-medium text-sm text-gray-700">Pack Size</label> <input type="text" name="pack_size" id="pack_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('pack_size') }}" placeholder="e.g., 10 Strip, 100ml" required>
                                @error('pack_size')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="unit" class="block font-medium text-sm text-gray-700">Unit</label>
                                <input type="text" name="unit" id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('unit') }}" placeholder="e.g., Tablet, Bottle" required>
                                @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                             <div>
                                <label for="gst" class="block font-medium text-sm text-gray-700">GST (%)</label> <input type="number" step="0.01" name="gst" id="gst" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('gst') }}" placeholder="e.g., 5, 12, 18" required>
                                @error('gst')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('medicines.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3">
                                Save Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>