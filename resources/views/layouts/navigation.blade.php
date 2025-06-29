<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block w-auto h-9 text-gray-800 fill-current" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Liens publics -->
                    <x-nav-link :href="route('properties.public')" :active="request()->routeIs('properties.public')">
                        {{ __('Annonces') }}
                    </x-nav-link>

                    @auth
                        <!-- Liens pour tous les utilisateurs connectés -->
                        @if(Auth::user()->role === 'client')
                            <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
                                {{ __('Mes Annonces') }}
                            </x-nav-link>
                            <x-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                                {{ __('Mes Demandes') }}
                            </x-nav-link>
                            <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                                {{ __('Mes RDV') }}
                            </x-nav-link>
                            <x-nav-link :href="route('invoices.client')" :active="request()->routeIs('invoices.client')">
                                {{ __('Mes Factures') }}
                            </x-nav-link>
                            <x-nav-link :href="route('property-appointments.my-properties')" :active="request()->routeIs('property-appointments.my-properties')">
                                {{ __('RDV Annonces') }}
                            </x-nav-link>
                        @elseif(Auth::user()->role === 'technicien')
                            <x-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                                {{ __('Mes Interventions') }}
                            </x-nav-link>
                            <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                                {{ __('Mon Planning') }}
                            </x-nav-link>
                        @elseif(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
                                {{ __('Gestion Annonces') }}
                            </x-nav-link>
                            <x-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                                {{ __('Gestion Services') }}
                            </x-nav-link>
                            <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                                {{ __('Tous les RDV') }}
                            </x-nav-link>
                            <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                                {{ __('Facturation') }}
                            </x-nav-link>
                            <x-nav-link :href="route('property-appointments.admin-index')" :active="request()->routeIs('property-appointments.admin-index')">
                                {{ __('RDV Annonces') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 bg-white rounded-md border border-transparent transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <!-- Liens pour invités -->
                <div class="hidden space-x-4 sm:flex sm:items-center sm:ml-6">
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">{{ __('Se connecter') }}</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">{{ __('S\'inscrire') }}</a>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="flex items-center -mr-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex justify-center items-center p-2 text-gray-400 rounded-md transition duration-150 ease-in-out hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('properties.public')" :active="request()->routeIs('properties.public')">
                {{ __('Annonces') }}
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->role === 'client')
                    <x-responsive-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
                        {{ __('Mes Annonces') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                        {{ __('Mes Demandes') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                        {{ __('Mes RDV') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('invoices.client')" :active="request()->routeIs('invoices.client')">
                        {{ __('Mes Factures') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('property-appointments.my-properties')" :active="request()->routeIs('property-appointments.my-properties')">
                        {{ __('RDV Annonces') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user()->role === 'technicien')
                    <x-responsive-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                        {{ __('Mes Interventions') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                        {{ __('Mon Planning') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
                        {{ __('Gestion Annonces') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                        {{ __('Gestion Services') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                        {{ __('Tous les RDV') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                        {{ __('Facturation') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('property-appointments.admin-index')" :active="request()->routeIs('property-appointments.admin-index')">
                        {{ __('RDV Annonces') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Se connecter') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('S\'inscrire') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
