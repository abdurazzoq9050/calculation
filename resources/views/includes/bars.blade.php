<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="logo">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                {{-- <li class="pc-item {{ request()->routeIs(route('dashboard')) ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Главная</span>
                    </a>
                </li> --}}

                <li class="pc-item pc-caption">
                    <label>Данные</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item {{ request()->routeIs(route('employees.index')) ? 'active' : '' }}">
                    <a href="{{ route('employees.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Сотрудники</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs(route('products.index')) ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
                        <span class="pc-mtext">Продукты</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs(route('ingredients.index')) ? 'active' : '' }}">
                    <a href="{{ route('ingredients.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-bookmark"></i></span>
                        <span class="pc-mtext">Ингредиенты</span>
                    </a>
                </li>
                
                <li class="pc-item pc-caption">
                  <label>Другое</label>
                  <i class="ti ti-brand-chrome"></i>
                </li>
                <li class="pc-item {{ request()->routeIs(route('recipeHistory.index')) }}">
                    <a href="{{ route('recipeHistory.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-list"></i></span>
                        <span class="pc-mtext">История рецептов</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('logout') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-lock"></i></span>
                        <span class="pc-mtext">Выйти</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
<header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="dropdown pc-h-item d-inline-flex d-md-none">
                    <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-search"></i>
                    </a>
                    <div class="dropdown-menu pc-h-dropdown drp-search">
                        <form class="px-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <i data-feather="search"></i>
                                <input type="search" class="form-control border-0 shadow-none"
                                    placeholder="Search here. . .">
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
                        <span>{{ Auth::user()->name . " ". Auth::user()->surname }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex mb-1">
                                <div class="flex-shrink-0">
                                    <img src="../assets/images/user/avatar-2.jpg" alt="user-image"
                                        class="user-avtar wid-35">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ Auth::user()->name . " ". Auth::user()->surname }}</h6>
                                    <span>{{ Auth::user()->role }}</span>
                                </div>
                                <a href="{{ route('logout') }}" class="pc-head-link bg-transparent"><i
                                        class="ti ti-power text-danger"></i></a>
                            </div>
                        </div>
                        <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="drp-t1" data-bs-toggle="tab"
                                    data-bs-target="#drp-tab-1" type="button" role="tab"
                                    aria-controls="drp-tab-1" aria-selected="true"><i class="ti ti-user"></i>
                                    Профиль</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="mysrpTabContent">
                            <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                aria-labelledby="drp-t1" tabindex="0">
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-edit-circle"></i>
                                    <span>Изменить профиль</span>
                                </a>
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-user"></i>
                                    <span>Показать Profile</span>
                                </a>
                            </div>
                            <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2"
                                tabindex="0">
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-help"></i>
                                    <span>Support</span>
                                </a>
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-user"></i>
                                    <span>Account Settings</span>
                                </a>
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-lock"></i>
                                    <span>Privacy Center</span>
                                </a>
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-messages"></i>
                                    <span>Feedback</span>
                                </a>
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-list"></i>
                                    <span>History</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
