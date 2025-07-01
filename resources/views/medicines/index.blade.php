<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Medicines
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-2xl font-semibold">Medicine List</h3>
                        <a href="{{ route('medicines.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Medicine
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
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Name</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Mfg. Company</th> <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pack Size</th> <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Unit</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">GST (%)</th> <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($medicines as $medicine)
                                    <tr class="border-b">
                                        <td class="py-3 px-4">{{ $medicine->name }}</td>
                                        <td class="py-3 px-4">{{ $medicine->mfg_company_name }}</td> <td class="py-3 px-4">{{ $medicine->pack_size }}</td> <td class="py-3 px-4">{{ $medicine->unit }}</td>
                                        <td class="py-3 px-4">{{ $medicine->gst }}%</td> <td class="text-center py-3 px-4">
                                            <a href="{{ route('medicines.show', $medicine->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('medicines.edit', $medicine->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-2">Edit</a>
                                            <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No medicines found.</td>
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