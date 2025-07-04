<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                        Suppliers
                    </x-nav-link>
                    <x-nav-link :href="route('medicines.index')" :active="request()->routeIs('medicines.*')">
                        Medicines
                    </x-nav-link>
                     <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                        Customers
                    </x-nav-link>
                    <x-nav-link :href="route('purchase_bills.index')" :active="request()->routeIs('purchase_bills.*')">
                        Purchase Bills
                    </x-nav-link>
                     <x-nav-link :href="route('bills.index')" :active="request()->routeIs('bills.*')">
                        Bills
                    </x-nav-link>
                    <x-nav-link :href="route('company_details.edit')" :active="request()->routeIs('company_details.*')">
                        Our Details
                    </x-nav-link>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                Suppliers
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('medicines.index')" :active="request()->routeIs('medicines.*')">
                Medicines
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                Customers
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('purchase_bills.index')" :active="request()->routeIs('purchase_bills.*')">
                Purchase Bills
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bills.index')" :active="request()->routeIs('bills.*')">
                Bills
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('company_details.edit')" :active="request()->routeIs('company_details.*')">
                Our Details
            </x-responsive-nav-link>
        </div>
    </div>
</nav>