<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.index') }}" class="app-brand-link" style="margin-left: -10px">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/logo/logo.png') }}" alt="" width="40">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2" style="text-transform: none">CoffeeBrew</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.menu') ? 'active' : '' }}">
            <a href="{{ route('admin.menu') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div data-i18n="Analytics">Menu</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.category') ? 'active' : '' }}">
            <a href="{{ route('admin.category') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Analytics">Menu Categories</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.table') ? 'active' : '' }}">
            <a href="{{ route('admin.table') }}" class="menu-link">
                <i class="menu-icon tf-icons">
                    <img src="{{ asset('img/svg/table.svg') }}" alt="" width="20">
                </i>
                <div data-i18n="Analytics">Table</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.order') ? 'active' : '' }}">
            <a href="{{ route('admin.order') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task"></i>
                <div data-i18n="Analytics">Order</div>
            </a>
        </li>

        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li> --}}
    </ul>
</aside>
