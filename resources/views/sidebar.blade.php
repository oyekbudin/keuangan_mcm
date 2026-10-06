<!-- Sidebar -->
<aside class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="index.html" class="sidebar-logo">
            <span class="sidebar-logo-mark">
                <img src="{{ asset('images/logo.png') }}" alt="GRC MCM">
            </span>
            <!--span class="sidebar-logo-text">
                    <span class="sidebar-logo-name">AppDashboard</span>
                    <span class="sidebar-logo-label">Admin suite</span>
                </span-->
        </a>
        <button class="sidebar-close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a class="nav-link active" href="index.html">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <li class="nav-item">

                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('Log Out') }}</span>
                    </a>

                </li>
            </form>
            <!--li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                                this.closest('form').submit();"
                        class="nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('Log Out') }}</span>
                    </x-dropdown-link>
                </form>
            </li-->



        </ul>
    </nav>

</aside>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay"></div>
