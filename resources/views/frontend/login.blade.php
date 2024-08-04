@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("login")) }}
@endsection
@section('content')

    <main>
    <section class="register">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-12 mb-3 px-0">
                    <div class="data-contain">
                        <div class="row">
                            <div class="col-lg-10 col-12 mx-auto">
                                <a href="{{ route('landing') }}" class="brand-name">
                                    <img src="{{ asset('frontend/assets/images/logo/logo.svg') }}" loading="lazy" alt=""/>
                                </a>

                                <h1 class="head">
                                    تسجيل الدخول لحسابك
                                </h1>

                                <p class="desc">
                                    هلا بعودتك من جديد 👋
                                </p>

                                <div class="form-contain">
                                    <div class="row">
                                        <div class="col-lg-12 col-12 px-2">
                                            <div class="form-group">
                                                <label for="email">
                                                    البريد الالكتروني
                                                </label>

                                                <div class="form-icon">
                                                    <input
                                                        type="email"
                                                        class="form-control"
                                                        placeholder="البريد الالكتروني"
                                                        id="email"
                                                        name="email"
                                                    />
                                                </div>

                                                <!-- <small class="error">
                                                  error message
                                                </small> -->
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
                                                        class="form-control"
                                                        placeholder="كلمة المرور"
                                                        id="password"
                                                        name="password"
                                                    />

                                                    <div class="show-pass">
                                                        <img
                                                            src="{{ asset('frontend/assets/images/icons/password.svg') }}"
                                                            class="icon"
                                                            loading="lazy"
                                                            alt=""
                                                        />
                                                        <img
                                                            src="{{ asset('frontend/assets/images/icons/show-pass.svg') }}"
                                                            class="slash-icon"
                                                            loading="lazy"
                                                            alt=""
                                                        />
                                                    </div>
                                                </div>

                                                <!-- <small class="error">
                                                  error message
                                                </small> -->
                                            </div>
                                        </div>
                                    </div>

                                    <a href="#" class="custom-btn primary-btn next-step">
                                        <span> تسجيل الدخول </span>
                                    </a>

                                    <a href="{{ route('register.form') }}" class="link">
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
                            <img src="{{ asset('frontend/assets/images/intro/intro.svg') }}" loading="lazy" alt="">
                        </div>

                        <h1>
                            قوابا - عالم الجمال
                        </h1>

                        <p>
                            قوابا منصة تهدف الي تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على افضل العروض الخاصة
                            بالاجراءات التجميلة الجراحية وغير الجراحية، كما توفر منصة قوابا مركزا لطلب مختلف المنتجات
                            والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء
                            من المستخدمين او مزودي الخدمات المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
