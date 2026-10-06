@include('head')

<body>
    <div class="auth-layout">
        <div class="auth-shell">
            <aside class="auth-brand-panel">
                <a href="index.html" class="auth-logo">
                    <img src="images/logo.png" alt="AppDashboard">
                    <!--span>AppDashboard</span-->
                </a>
            </aside>

            <main class="auth-main">
                <div class="auth-main-inner">
                    <a href="index.html" class="auth-logo auth-logo-mobile">
                        <img src="images/logo.webp" alt="AppDashboard">
                        <span>AppDashboard</span>
                    </a>

                    <div class="auth-card">
                        <div class="auth-card-header">
                            <!--span class="auth-card-kicker">Secure sign in</span-->
                            <h1 class="auth-title">Masuk</h1>
                            <p class="auth-subtitle">Aplikasi Keuangan CV Mentari Cahaya Mandiri</p>
                        </div>


                        <x-guest-layout>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}" class="auth-form">
                                @csrf

                                <!-- Email Address -->
                                <div class="form-group">
                                    <x-input-label for="email" :value="__('Email')" class="form-label" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autofocus autocomplete="username"
                                        class="form-control" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" class="invalid-feedback" />
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <div class="auth-helper-row">
                                        <x-input-label for="password" :value="__('Password')" class="form-label" />
                                        @if (Route::has('password.request'))
                                            <a class="auth-link small" href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </div>

                                    <div class="input-group">
                                        <x-text-input id="password" class="form-control" type="password"
                                            name="password" required autocomplete="current-password" />
                                    </div>


                                    <x-input-error :messages="$errors->get('password')" class="invalid-feedback" />
                                </div>

                                <!-- Remember Me -->
                                <div class="form-check">

                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">
                                        {{ __('Remember me') }}
                                    </label>
                                </div>

                                <div class="form-group">


                                    <x-primary-button class="btn btn-primary btn-block"><i
                                            class="bi bi-box-arrow-in-right"></i>
                                        {{ __('Log in') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </x-guest-layout>

                    </div>

                    <footer class="footer-centered">
                        <div class="footer-copyright">
                            © 2026 <a href="#">Aplikasi Keuangan CV Mentari Cahaya Mandiri</a>.
                        </div>
                        <!--div class="footer-links">
              <a href="#">Privacy Policy</a>
              <a href="#">Terms of Service</a>
              <a href="#">Help</a>
            </div-->

                    </footer>
                </div>
                @include('footer')
