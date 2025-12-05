{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@if(backpack_user()->user_type === 'provider')
    {{-- Service Providers see their services --}}
    <x-backpack::menu-item title="My Services" icon="la la-briefcase" :link="backpack_url('service')" />
    <x-backpack::menu-item title="My Bookings" icon="la la-calendar-check" :link="url('/provider-bookings')" />
@elseif(backpack_user()->user_type === 'client')
    {{-- Clients see booking-related items --}}
    <x-backpack::menu-item title="My Bookings" icon="la la-calendar-check" :link="url('/my-bookings')" />
    <x-backpack::menu-item title="Search Services" icon="la la-search" :link="url('/')" />
@elseif(backpack_user()->user_type === 'admin')
    {{-- Admin users see everything --}}
    <x-backpack::menu-dropdown title="Users" icon="la la-users">
        <x-backpack::menu-dropdown-item title="All Persons" icon="la la-user" :link="backpack_url('user')" />
        <x-backpack::menu-dropdown-item title="Registered Clients" icon="la la-user-check" :link="backpack_url('client')" />
        <x-backpack::menu-dropdown-item title="Registered Providers" icon="la la-id-badge" :link="backpack_url('provider')" />
    </x-backpack::menu-dropdown>

    <x-backpack::menu-item title="Selections" icon="la la-check-circle" :link="backpack_url('selection')" />
    <x-backpack::menu-item title="Services" icon="la la-briefcase" :link="backpack_url('service')" />
    <x-backpack::menu-item title="All Bookings" icon="la la-calendar" :link="backpack_url('booking')" />
    <!-- <x-backpack::menu-item title="Bookings" icon="la la-question" :link="backpack_url('booking')" /> -->
@endif


<li class="nav-item">
    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="la la-sign-out nav-icon"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>




