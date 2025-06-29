<aside class="sidebar">
    {{-- User Profile Display at the top --}}
    @auth
    <div class="sidebar-profile" style="padding: 20px 25px; text-align: center; border-bottom: 1px solid var(--sidebar-hover);">
        <img src="{{ asset('storage/avatars/' . Auth::user()->profile_picture) }}" alt="User Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 3px solid var(--primary-color);">
        <p style="color: var(--sidebar-text); margin: 0; font-weight: 600; font-size: 1.1em;">{{ Auth::user()->username }}</p>
    </div>
    @endauth

    <ul class="sidebar-menu">

        @auth
            @php
                // We add the new mockup routes to these arrays so the menus will open correctly
                $reportRoutes = ['sales.report', 'reports.product_performance', 'reports.end_of_day'];
                $catalogRoutes = ['products.index', 'products.create', 'products.edit', 'categories.index'];
                $inventoryRoutes = ['inventory.stock_count', 'inventory.stock_adjustment'];
                $purchasingRoutes = ['suppliers.index', 'purchase_orders.index'];
                $userRoutes = ['users.index', 'users.edit'];
                $employeeRoutes = ['employees.index'];
                $settingRoutes = ['settings.store', 'settings.promotions'];
            @endphp

            @if (Auth::user()->role === 'admin')
                {{-- ==================== ADMIN SIDEBAR ==================== --}}
                <li class="menu-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="{{ route('pos.terminal') }}"><i class="fa-solid fa-calculator"></i> New Sale</a></li>

                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $reportRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-chart-line"></i> Reports <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="{{ route('sales.report') }}"><i class="fa-solid fa-file-invoice-dollar" style="width: 20px; margin-right: 10px;"></i>Sales Report</a></li>
                        <li class="submenu-item"><a href="{{ route('reports.product_performance')}}"><i class="fa-solid fa-star" style="width: 20px; margin-right: 10px;"></i>Product Performance</a></li>
                        <li class="submenu-item"><a href="{{ route('reports.end_of_day')}}"><i class="fa-solid fa-sun" style="width: 20px; margin-right: 10px;"></i>End of Day Report</a></li>
                    </ul>
                </li>

                <li class="menu-header">MANAGEMENT</li>
                
                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $catalogRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-box-archive"></i> Catalog <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="{{ route('products.index') }}"><i class="fa-solid fa-boxes-stacked" style="width: 20px; margin-right: 10px;"></i>Products</a></li>
                        <li class="submenu-item"><a href="{{ route('categories.index') }}"><i class="fa-solid fa-tags" style="width: 20px; margin-right: 10px;"></i>Categories</a></li>
                    </ul>
                </li>

                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $purchasingRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-truck"></i> Purchasing <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="{{ route('suppliers.index') }}"><i class="fa-solid fa-parachute-box" style="width: 20px; margin-right: 10px;"></i>Suppliers</a></li>
                        <li class="submenu-item"><a href="#"><i class="fa-solid fa-receipt" style="width: 20px; margin-right: 10px;"></i>Purchase Orders</a></li>
                    </ul>
                </li>
                
                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $inventoryRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-warehouse"></i> Inventory <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="{{ route('inventory.stock_count') }}"><i class="fa-solid fa-clipboard-list" style="width: 20px; margin-right: 10px;"></i>Stock Count</a></li>
                        <li class="submenu-item"><a href="{{ route('inventory.stock_adjustment') }}"><i class="fa-solid fa-right-left" style="width: 20px; margin-right: 10px;"></i>Stock Adjustment</a></li>
                    </ul>
                </li>

                <li class="menu-header">ADMINISTRATION</li>
                <li class="menu-item"><a href="{{ route('customers.index') }}"><i class="fa-solid fa-users"></i> Customer Management</a></li>

                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $employeeRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-id-badge"></i> Employees <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="{{route('employees.index')}}"><i class="fa-solid fa-address-book" style="width: 20px; margin-right: 10px;"></i>Employee List</a></li>
                    </ul>
                </li>
                
                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $userRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-users-cog"></i> Users <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="{{ route('users.index') }}"><i class="fa-solid fa-user-gear" style="width: 20px; margin-right: 10px;"></i>Manage Users</a></li>
                    </ul>
                </li>

                <li class="menu-item has-submenu {{ in_array(Route::currentRouteName(), $settingRoutes) ? 'open' : '' }}">
                    <a href="#"><i class="fa-solid fa-cogs"></i> Settings <i class="fa fa-angle-down float-right"></i></a>
                    <ul class="submenu">
                        {{-- THE FIX: Changed the href to the new, real route --}}
                        <li class="submenu-item"><a href="{{ route('settings.store') }}"><i class="fa-solid fa-store" style="width: 20px; margin-right: 10px;"></i>Store Settings</a></li>
                        <li class="submenu-item"><a href="#"><i class="fa-solid fa-percent" style="width: 20px; margin-right: 10px;"></i>Promotions</a></li>
                    </ul>
                </li>

            @else
                {{-- ==================== CASHIER SIDEBAR ==================== --}}
                <li class="menu-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="{{ route('pos.terminal') }}"><i class="fa-solid fa-calculator"></i> New Sale</a></li>
                <li class="menu-item"><a href="{{ route('customers.index') }}"><i class="fa-solid fa-user-plus"></i> Register Customer</a></li>
                <li class="menu-item"><a href="{{ route('inventory.stock_count') }}"><i class="fa-solid fa-warehouse"></i> View Stock</a></li>
            @endif
        @endauth
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.sidebarScriptLoaded) return;
        window.sidebarScriptLoaded = true;
        document.querySelectorAll('.sidebar-menu .has-submenu > a').forEach(function(menu) {
            menu.addEventListener('click', function(e) {
                e.preventDefault();
                let parent = this.parentElement;
                document.querySelectorAll('.sidebar-menu .has-submenu.open').forEach(function(openMenu) {
                    if (openMenu !== parent) {
                        openMenu.classList.remove('open');
                    }
                });
                parent.classList.toggle('open');
            });
        });
    });
</script>
