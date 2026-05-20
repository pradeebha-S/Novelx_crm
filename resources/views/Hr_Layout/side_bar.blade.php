<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo ">
        <a href="https://novelx.in/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="{{ asset('/assets/img') }}/logo_sidebar.png" alt="Logo">
                </span>
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item @if (Route::is('hr.dashboard')) active @endif">
            <a href="{{ route('hr.dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <li class="menu-item @if (Route::is('candidates')) active @endif">
            <a href="{{ route('candidates') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Candidates">Candidates</div>
            </a>
        </li>
        <li class="menu-item @if (Route::is('follow_up_table')) active @endif">
            <a href="{{ route('follow_up_table') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div data-i18n="Follow Up">Follow Up</div>
            </a>
        </li>
        <li class="menu-item @if (Route::is('hr.reset_password')) active @endif">
            <a href="{{ route('hr.reset_password') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-lock"></i>
                <div data-i18n="Reset Password">Reset Password</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link" data-bs-toggle="modal" data-bs-target="#logout">
                <i class="menu-icon icon-base ti tabler-logout"></i>
                <div data-i18n="Logout">Logout</div>
            </a>
        </li>
    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="ti tabler-menu icon-base"></i>
        <i class="ti tabler-chevron-right icon-base"></i>
    </a>
</div>