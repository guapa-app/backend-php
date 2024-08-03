<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="...." />
    <meta name="author" content="misara adel" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>
        GUAPA
    </title>
    <link rel="shortcut icon" href="{{ asset('landing-v2/images/logo/icon.png') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('landing-v2/css/lib/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-v2/css/lib/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-v2/css/lib/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-v2/css/style.css') }}" />
</head>
<body>
<main>
    <section class="register">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-12 mb-3 px-0">
                    <div class="data-contain">
                        <div class="row">
                            <div class="col-lg-10 col-12 mx-auto">
                                <a href="index.html" class="brand-name">
                                    <img src="{{ asset('landing-v2/images/logo/logo.svg') }}" loading="lazy" alt="" />
                                </a>

                                <h1 class="head">
                                    تسجيل الدخول لحسابك
                                </h1>

                                <p class="desc">
                                    هلا بعودتك من جديد  👋
                                </p>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-contain">
                                    <form wire:submit.prevent="authenticate">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12 col-12 px-2">
                                                <div class="form-group">
                                                    <label for="email">
                                                        البريد الالكتروني
                                                    </label>

                                                    <div class="form-icon">
                                                        <input
                                                            type="email"
                                                            class="form-control @error('data.email') is-invalid @enderror"
                                                            placeholder="البريد الالكتروني"
                                                            id="email"
                                                            name="email"
                                                            wire:model.defer="data.email"
                                                        />
                                                        @error('data.email')
                                                        <small class="error text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-12">
                                                <div class="form-group">
                                                    <label for="password">
                                                        كلمة المرور
                                                    </label>

                                                    <div class="show_hide_password">
                                                        <input
                                                            type="password"
                                                            class="form-control @error('data.password') is-invalid @enderror"
                                                            placeholder="كلمة المرور"
                                                            id="password"
                                                            name="password"
                                                            wire:model.defer="data.password"
                                                        />
                                                        @error('data.password')
                                                        <small class="error text-danger">{{ $message }}</small>
                                                        @enderror

                                                        <div class="show-pass">
                                                            <img
                                                                src="{{ asset('landing-v2/images/icons/password.svg') }}"
                                                                class="icon"
                                                                loading="lazy"
                                                                alt=""
                                                            />
                                                            <img
                                                                src="{{ asset('landing-v2/images/icons/show-pass.svg') }}"
                                                                class="slash-icon"
                                                                loading="lazy"
                                                                alt=""
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="custom-btn primary-btn next-step">
                                            <span> تسجيل الدخول </span>
                                        </button>
                                    </form>

                                    <a href="register.html" class="link">
                                        ليس لديك حساب؟

                                        <span>
                          سجل حساب جديد الان
                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 px-0">
                    <div class="register-contain">
                        <div class="image-contain">
                            <img src="{{ asset('landing-v2/images/intro/intro.svg') }}" loading="lazy" alt="" >
                        </div>

                        <h1>
                            قوابا - عالم الجمال
                        </h1>

                        <p>
                            قوابا منصة تهدف الي تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على افضل العروض الخاصة بالاجراءات التجميلة الجراحية وغير الجراحية، كما توفر منصة قوابا مركزا لطلب مختلف المنتجات والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء من المستخدمين او مزودي الخدمات المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('landing-v2/js/lib/jquery4.js') }}"></script>
<script src="{{ asset('landing-v2/js/lib/popper.js') }}"></script>
<script src="{{ asset('landing-v2/js/lib/bootstrap.js') }}"></script>
<script src="{{ asset('landing-v2/js/lib/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('landing-v2/js/main.js') }}"></script>
</body>
</html>
