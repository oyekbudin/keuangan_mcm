
@include('head')
<body>
    <!-- Header -->
    <header class="header">
        <!-- Header Left -->
        <div class="header-left">
            <a href="index.html" class="header-logo">
                <span class="header-logo-mark">
                    <img src="{{ asset('images/logo.webp') }}" alt="AppDashboard">
                </span>
                <span>AppDashboard</span>
            </a>
            <!--button class="sidebar-toggle" title="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button-->
            <div class="header-context">
                <!--span>Workspace</span-->
                <strong>Aplikasi Keuangan CV Mentari Cahaya Mandiri</strong>
            </div>
        </div>

        <!-- Header Search (Desktop) 
        <div class="header-search">
            <form class="search-form" action="search-results.html" method="GET">
                <button type="submit"><i class="bi bi-search"></i></button>
                <input type="search" name="q" placeholder="Search workspace..." autocomplete="off">
            </form>
        </div>-->

        <!-- Header Right -->
        <div class="header-right">
            <!-- Desktop Actions (hidden on mobile, shown in mobile menu) -->
            <div class="header-actions-desktop">
                <!-- Theme Toggle -->
                <!--button class="header-action theme-toggle" title="Toggle Theme">
                    <i class="bi bi-moon icon-dark"></i>
                    <i class="bi bi-sun icon-light"></i>
                </button>

                
                <button class="header-action fullscreen-toggle" onclick="toggleFullscreen()" title="Fullscreen">
                    <i class="bi bi-fullscreen icon-enter"></i>
                    <i class="bi bi-fullscreen-exit icon-exit"></i>
                </button>

                
                <div class="header-action dropdown notification-dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="notification-header">
                            <div>
                                <span>Today</span>
                                <h6>Notifications</h6>
                            </div>
                            <a href="#" data-notification-action="mark-all-read">Mark all read</a>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item unread">
                                <div class="notification-icon success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Order Completed</div>
                                    <div class="notification-text">Your order #12345 has been delivered</div>
                                    <div class="notification-time">5 min ago</div>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Low Storage</div>
                                    <div class="notification-text">Server storage is running low (85% used)</div>
                                    <div class="notification-time">1 hour ago</div>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon info">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">New Feature</div>
                                    <div class="notification-text">Dark mode is now available</div>
                                    <div class="notification-time">2 hours ago</div>
                                </div>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="notifications.html">View all notifications</a>
                        </div>
                    </div>
                </div-->

                <!-- User Dropdown -->
                <div class="header-action dropdown user-dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('images/profile-img.webp') }}" alt="User" class="avatar">
                        <span class="user-name">
                            <strong>John Doe</strong>
                            <small>Administrator</small>
                        </span>
                    </button>
                    <!--ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <img src="{{ asset('images/profile-img.webp') }}" alt="User">
                            <h6>John Doe</h6>
                            <span>Administrator</span>
                        </li>
                        <li>
                            <a class="dropdown-item" href="profile.html">
                                <i class="bi bi-person"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="settings.html">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="activity.html">
                                <i class="bi bi-activity"></i> Activity Log
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="auth-login.html">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </a>
                        </li>
                    </ul-->
                </div>
            </div>

            <!-- Mobile Actions (visible only on mobile) -->
            <div class="header-actions-mobile">
                <!-- Search Toggle (Mobile) -->
                <button class="header-action search-toggle" title="Search">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Mobile Menu Toggle -->
                <button class="header-action mobile-menu-toggle" title="More">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Search -->
    <div class="mobile-search">
        <form class="search-form" action="search-results.html" method="GET">
            <input type="search" name="q" placeholder="Search..." autocomplete="off">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <!-- Mobile Header Menu -->
    <div class="mobile-header-menu">
        <div class="mobile-header-menu-content">
            <!-- Theme Toggle -->
            <button class="mobile-menu-item theme-toggle" title="Toggle Theme">
                <i class="bi bi-moon icon-dark"></i>
                <i class="bi bi-sun icon-light"></i>
                <span class="mobile-menu-label">Theme</span>
            </button>

            <!-- Fullscreen Toggle -->
            <button class="mobile-menu-item fullscreen-toggle" onclick="toggleFullscreen()" title="Fullscreen">
                <i class="bi bi-fullscreen icon-enter"></i>
                <i class="bi bi-fullscreen-exit icon-exit"></i>
                <span class="mobile-menu-label">Fullscreen</span>
            </button>

            <!-- Notifications -->
            <a href="notifications.html" class="mobile-menu-item">
                <i class="bi bi-bell"></i>
                <span class="badge">3</span>
                <span class="mobile-menu-label">Notifications</span>
            </a>

            <!-- Profile -->
            <a href="profile.html" class="mobile-menu-item">
                <i class="bi bi-person"></i>
                <span class="mobile-menu-label">Profile</span>
            </a>

            <!-- Settings -->
            <a href="settings.html" class="mobile-menu-item">
                <i class="bi bi-gear"></i>
                <span class="mobile-menu-label">Settings</span>
            </a>

            <!-- Sign Out -->
            <a href="auth-login.html" class="mobile-menu-item mobile-menu-item-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span class="mobile-menu-label">Sign Out</span>
            </a>
        </div>
    </div>
