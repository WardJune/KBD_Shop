<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="./pages/dashboards/dashboard.html">
                <img src="{{ asset('assets/img/brand/blue.png') }}" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="ni ni-tv-2 text-primary"></i><span
                                class="nav-link-text">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button"
                            aria-expanded="true" aria-controls="navbar-examples">
                            <i class="fab fa-laravel text-danger"></i>
                            <span class="nav-link-text">{{ __('Laravel Examples') }}</span>
                        </a>

                        <div class="collapse hide" id="navbar-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('profile.edit') }}">
                                        <span class="nav-link-text">{{ __('User profile') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.index') }}">
                                        <span class="nav-link-text">{{ __('User Management') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#order" data-toggle="collapse" role="button" aria-expanded="true"
                            aria-controls="order">
                            <i class="ni ni-bullet-list-67 text-danger"></i>
                            <span class="nav-link-text">{{ __('Orders') }}</span>
                        </a>

                        <div class="collapse hide" id="order">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.index') }}">
                                        <span class="nav-link-text">{{ __('Orders Management') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#navbar-multilevel" class="nav-link" data-toggle="collapse" role="button"
                                        aria-expanded="true" aria-controls="navbar-multilevel">Report</a>
                                    <div class="collapse show" id="navbar-multilevel" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('orders.report') }}">
                                                    <span class="nav-link-text">{{ __('Orders Report') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('orders.report-return') }}">
                                                    <span
                                                        class="nav-link-text">{{ __('Return Orders Report') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/category') }}">
                            <i class="ni ni-collection text-info"></i> <span
                                class="nav-link-text">{{ __('Category') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/merk') }}">
                            <i class="ni ni-archive-2 text-warning"></i> <span
                                class="nav-link-text">{{ __('Merk') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/product') }}">
                            <i class="ni ni-shop text-success"></i><span
                                class="nav-link-text">{{ __('Product') }}</span>
                        </a>
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">Documentation</h6>
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                </ul>
            </div>
        </div>
    </div>
</nav>
