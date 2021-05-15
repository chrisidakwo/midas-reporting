<header class="header">
    <a href="{{ route('dashboard') }}" class="logo">
        <img src="{{ asset('img/brand/iom_icon_logo_blue.png') }}" alt="" class="logo-text">
    </a>

    <nav class="navigation">
        <div class="navigation-wrapper">
            <a href="{{ route('movement.index') }}" class="nav-menu-item {{ request()->routeIs(['movement.index']) ? 'active' : '' }}">
                <div class="nav-item-wrapper">
                    <div class="nav-item">
                        <i class="icon nav-item-icon material-icons-outlined icon-no-color">insights</i>
                        <span class="nav-item-title">Movement Statistics</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('reports') }}" class="nav-menu-item {{ request()->routeIs(['reports']) ? 'active' : '' }}">
                <div class="nav-item-wrapper">
                    <div class="nav-item">
                        <i class="icon nav-item-icon material-icons-outlined icon-no-color">analytics</i>
                        <span class="nav-item-title">Report</span>
                    </div>
                </div>
            </a>
        </div>
    </nav>
</header>
