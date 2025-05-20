<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-dark.png" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-light.png" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

            </div>

            <div class="d-flex align-items-center">

                <div class="ms-1 header-item d-none d-sm-flex">

                                        <!-- Hidden Logout Form -->
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>

                    <!-- Logout Button -->
                    <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" onclick="logout()">
                        <i class='bx bx-log-out fs-22'></i>
                    </button>

                    <!-- JavaScript Function -->
                    <script>
                        function logout() {
                            event.preventDefault();
                            document.getElementById('logout-form').submit();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</header>


        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
        <div class="navbar-brand-box">
            <!-- Dark & Light Mode Logo -->
            <a href="{{route('dashboard')}}" class="logo">
                <span class="logo-text">EzyPay</span>
            </a>

            <!-- Vertical Hover Button -->
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

    
            <div class="dropdown sidebar-user m-1 rounded">
                <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                        <h5>EZP</h5>
                    </span>
                </button>
            </div>
            <div id="scrollbar">
                <div class="container-fluid">


                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('/dashboard')}}" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                            </a>
                        </li>
                        @can('product-list')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('product')}}" role="button">
                                <i class="ri-shopping-bag-3-line"></i> <span data-key="t-products">Products</span>
                            </a>
                        </li>
                        @endcan
                        @can('market-list')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('markets')}}" role="button">
                                <i class="ri-store-2-line"></i> <span data-key="t-markets">Markets</span>
                            </a>
                        </li>
                        @endcan
                        @can('state-list')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('states')}}" role="button">
                                <i class="ri-map-pin-line"></i> <span data-key="t-states">States</span>
                            </a>
                        </li>
                        @endcan
                        @can('record-list')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('prices')}}" role="button">
                                <i class="ri-file-list-3-line"></i> <span data-key="t-records">Records</span>
                            </a>
                        </li>
                        @endcan
                        @can('user-list')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{url('users')}}" role="button">
                                <i class="ri-user-settings-line"></i> <span data-key="t-users">Users Management</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>





        
