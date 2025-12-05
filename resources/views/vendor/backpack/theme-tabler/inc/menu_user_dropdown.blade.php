@if (backpack_auth()->check())
    <li class="nav-item dropdown">
        <a class="nav-link d-flex lh-1 text-reset p-0" href="#" data-bs-toggle="dropdown" aria-label="Open user menu">
            <span class="avatar avatar-sm rounded-circle">
                <img src="{{ backpack_avatar_url(backpack_auth()->user()) }}" 
                     alt="{{ backpack_auth()->user()->first_name }} {{ backpack_auth()->user()->last_name }}" 
                     onerror="this.style.display='none'">
                <span class="avatar avatar-sm rounded-circle bg-primary text-white">
                    {{ backpack_user()->getAttribute('first_name') ? mb_substr(backpack_user()->first_name, 0, 1, 'UTF-8') : 'A' }}
                </span>
            </span>
            <div class="d-none d-xl-block ps-2">
                <div>{{ backpack_user()->first_name }} {{ backpack_user()->last_name }}</div>
                <div class="mt-1 small text-muted">{{ backpack_user()->email }}</div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            @if(config('backpack.base.setup_my_account_routes'))
                <a class="dropdown-item" href="{{ route('backpack.account.info') }}">
                    <i class="la la-user"></i> {{ trans('backpack::base.my_account') }}
                </a>
                <div class="dropdown-divider"></div>
            @endif
            <a class="dropdown-item" href="{{ backpack_url('logout') }}">
                <i class="la la-lock"></i> {{ trans('backpack::base.logout') }}
            </a>
        </div>
    </li>
@endif
