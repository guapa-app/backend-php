<nav class="navbar">
    <div class="container">
        <div class="contain">
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>

            <a href="{{ route('landing') }}" class="brand-name">
                <img src="{{ asset('frontend/assets/images/logo/logo.svg') }}" loading="lazy" alt=""/>
            </a>

            <div class="nav-contain">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link">
                            ماذا نقدم للمستخدم
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('download-app') }}" class="nav-link">
                            مساعدة مزود خدمة
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('about-app') }}" class="nav-link">
                            التطبيق
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link">
                            عن قوابا
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('questions') }}" class="nav-link">
                            الاسئلة الشائعة
                        </a>
                    </li>
                </ul>

                <div class="button-contain">
                    <a href="{{ route('register') }}" class="btn-signup">
                        تسجيل كمزود خدمة
                    </a>

                    <a href="{{ route('login') }}" class="custom-btn primary-btn">
                <span>
                  تسجيل الدخول
                </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
