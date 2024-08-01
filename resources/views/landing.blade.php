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
<nav class="navbar">
    <div class="container">
        <div class="contain">
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>

            <a href="index.html" class="brand-name">
                <img src="{{ asset('landing-v2/images/logo/logo.svg') }}" loading="lazy" alt=""/>
            </a>

            <div class="nav-contain">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="about.html" class="nav-link">
                            ماذا نقدم للمستخدم
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="download-app.html" class="nav-link">
                            مساعدة مزود خدمة
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="about-app.html" class="nav-link">
                            التطبيق
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="about.html" class="nav-link">
                            عن قوابا
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="questions.html" class="nav-link">
                            الاسئلة الشائعة
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('blogs.index')}}" class="nav-link">
                            المدونة
                        </a>
                    </li>
                </ul>

                <div class="button-contain">
                    <a href="{{route('register.form')}}" class="btn-signup">
                        تسجيل كمزود خدمة
                    </a>

                    <a href="{{route('filament.user.auth.login')}}" class="custom-btn primary-btn">
                <span>
                  تسجيل الدخول
                </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<main>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 mb-3">
                    <div class="contain">
                        <h1>
                            قوابا - عالم الجمال
                        </h1>

                        <p>
                            قوابا منصة تهدف الي تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على افضل العروض الخاصة بالاجراءات التجميلة الجراحية وغير الجراحية، كما توفر منصة قوابا مركزا لطلب مختلف المنتجات والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء من المستخدمين او مزودي الخدمات المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي.
                        </p>

                        <div class="mobile-button">
                            <a href="#" class="download-btn">
                                <img src="{{ asset('landing-v2/images/icons/app-store.svg') }}" loading="lazy" alt="" />
                            </a>

                            <a href="#" class="download-btn">
                                <img src="{{ asset('landing-v2/images/icons/google_play.svg') }}" loading="lazy" alt="" />
                            </a>
                        </div>

                        <div class="flex-data">
                  <span>
                    تقييم
                    4.5 out of 5
                  </span>

                            <img src="{{ asset('landing-v2/images/icons/stars.svg') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-3">
                    <div class="image-contain">
                        <img src="{{ asset('landing-v2/images/intro/intro.svg') }}" loading="lazy" alt="" />
                    </div>
                </div>
            </div>
        </div>

        <a href="#features" class="down-arrow">
            <img src="{{ asset('landing-v2/images/icons/down_arrow.svg') }}" loading="lazy" alt="" />
        </a>
    </header>

    <section id="features" class="features general-section">
        <div class="container">
            <div class="custom-heading">
                <h1>
                    كيف يمكنك الاستفادة من قوابا إذا كنت مزود خدمة
                </h1>

                <p>
                    انها منصة تهدف الى تمكين وصول المهتمين بعالم الجمال والإطلاع على اخر العروض الخاصة بعالم الجمال سواء كانت اجراءات او منتجات
                </p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('landing-v2/images/feature/feature_1.svg') }}" loading="lazy" alt="" />

                        <h2>
                            الإستهداف
                        </h2>

                        <p>
                            احصل على المزيد من المرضى المهتمين بعمليات و منتجات التجميل عبر التطبيق. كن على تواصل دائم وزد من امكانية تواصل العديد من المهتمين بالاجراءات او المنتجات التجميلية
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('landing-v2/images/feature/feature_2.svg') }}" loading="lazy" alt="" />

                        <h2>
                            إدارة متجرك
                        </h2>

                        <p>
                            اضف منتجاتك و العروض المتاحة على المنتجات و اماكن البيع في دقائق من خلال التطبيق
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('landing-v2/images/feature/feature_3.svg') }}" loading="lazy" alt="" />

                        <h2>
                            عروض لعملائك
                        </h2>

                        <p>
                            قم بنشر عروضك و الاستفادة من قاعدة عملاء ضخمةـ وتحويل قاعدة العملاء الي مستهلك دائم لمنتجاتك
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('landing-v2/images/feature/feature_3.svg') }}" loading="lazy" alt="" />

                        <h2>
                            مجتمع خاص بك
                        </h2>

                        <p>
                            احصل على المزيد من المرضى المهتمين بعمليات و منتجات مجتمع قوابا هو مرجعك الى كل مايخص الجمال تقدر تشارك من خلاله مقاطع وارشادات، شارك المهتمين بعالم الجمال وكن على تواصل معهم وزودهم بالمعلومات الصحيحة وزيد من تقيمك.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="take general-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 mb-3">
                    <div class="image-contain">
                        <img src="{{ asset('landing-v2/images/take/take_it.svg') }}" loading="lazy" alt="" />
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-3">
                    <div class="contain">
                        <ul class="list">
                            <li>
                                <img src="{{ asset('landing-v2/images/take/take_1.svg') }}" loading="lazy" alt="" />

                                <div class="data">
                                    <h2>
                                        احجز و اكد موعدك
                                    </h2>

                                    <p>
                                        ابحث واطلع على جميع الاجراءات التجميلية الجراحية وغير الجراحية في عدة مناطق و احجز و اكد موعدك.
                                    </p>
                                </div>
                            </li>

                            <li>
                                <img src="{{ asset('landing-v2/images/take/take_2.svg') }}" loading="lazy" alt="" />

                                <div class="data">
                                    <h2>
                                        سهولة الطلب والتوصيل
                                    </h2>

                                    <p>
                                        تصفح وابحث عن العديد من المنتجات والمستحضرات التجميلية و العروض الخاصة بها مع امكانية طلبها وتوصيلها عن طريق مزود الخدمة.
                                    </p>
                                </div>
                            </li>

                            <li>
                                <img src="{{ asset('landing-v2/images/take/take_3.svg') }}" loading="lazy" alt="" />
                                <div class="data">
                                    <h2>
                                        مجتمع خاص بعالم الجمال
                                    </h2>

                                    <p>
                                        مجتمع قوابا هو مرجعك الى كل مايخص عالم الجمال تقدر تشوف من خلاله مقاطع وارشادات مزودي الخدمات والمنتجات مع امكانية مشاركة ارائك وتجاربك
                                    </p>
                                </div>
                            </li>
                        </ul>


                        <a href="{{route('filament.user.auth.login')}}" class="custom-btn secondary-btn">
                  <span>
                    سجل الان
                  </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="info general-section">
        <div class="container">
            <div class="contain">
                <h1>
                    لنبدأ شيئاً عظيماً
                    <br />
                    حملو التطبيق وشاركوه مع اصحابكم
                </h1>

                <ul class="nav nav-pills links mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
                            للمستخدمين
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
                            لمزودي الخدمات
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="mobile-button">
                            <a href="#" class="download-btn">
                                <img src="{{ asset('landing-v2/images/icons/app-store.svg') }}" loading="lazy" alt="" />
                            </a>

                            <a href="#" class="download-btn">
                                <img src="{{ asset('landing-v2/images/icons/google_play.svg') }}" loading="lazy" alt="" />
                            </a>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="mobile-button">
                            <a href="#" class="download-btn">
                                <img src="{{ asset('landing-v2/images/icons/app-store.svg') }}" loading="lazy" alt="" />
                            </a>

                            <a href="#" class="download-btn">
                                <img src="{{ asset('landing-v2/images/icons/google_play.svg') }}" loading="lazy" alt="" />
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 mb-4">
                <div class="contain">
                    <a href="#" class="brand-name">
                        <img src="{{ asset('landing-v2/images/logo/logo.svg') }}" loading="lazy" alt=""/>
                    </a>

                    <p>
                        سجل باستخدام عنوان بريدك الإلكتروني لتبقى على اطلاع بالخصومات والتحديثات الجديدة من جميع الحملات!
                    </p>

                    <form action="" class="form-icon">
                        <div class="form-group">
                            <img src="{{ asset('landing-v2/images/icons/search.svg') }}" loading="lazy" alt="" />
                            <input
                                type="text"
                                class="form-control"
                                placeholder="ادخل بريدك الالكتروني" />

                            <a href="#" class="custom-btn secondary-btn">
                    <span>
                      سجل الان
                    </span>
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <div class="col-lg-4 col-12 mb-4">
                <div class="flex-data">
                    <div class="contain">
                        <h2>
                            روابط مهمة
                        </h2>

                        <ul class="links">
                            <li>
                                <a href="download-app.html">
                      <span>
                        تطبيق مزودي الخدمة
                      </span>
                                </a>
                            </li>

                            <li>
                                <a href="about-app.html">
                      <span>
                        تطبيق المستخدمين
                      </span>
                                </a>
                            </li>

                            <li>
                                <a href="about.html">
                      <span>
                        عن قوابا
                      </span>
                                </a>
                            </li>

                            <li>
                                <a href="blogs.html">
                      <span>
                        المدونة
                      </span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="contain">
                        <h2>
                            للتواصل
                        </h2>

                        <ul class="links">
                            <li>
                                <a href="tel:5314343889" class="block">
                      <span>
                        للتواصل تليفونياً
                      </span>

                                    <span>
                        9665314343889
                      </span>
                                </a>
                            </li>

                            <li>
                                <a href="mailto:info@guapa.com.sa" class="block">
                      <span>
                        البريد الالكتروني
                      </span>

                                    <span>
                        info@guapa.com.sa
                      </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12 mb-4">
                <div class="contain padding-contain">
                    <h2>
                        تابعنا
                    </h2>

                    <ul class="links">
                        <li>
                            <a href="#">
                                <img src="{{ asset('landing-v2/images/footer/youtube.svg') }}" loading="lazy" alt="" />
                                <span>
                      موقع YouTube
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <img src="{{ asset('landing-v2/images/footer/x.svg') }}" loading="lazy" alt="" />
                                <span>
                      منصة إكس
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <img src="{{ asset('landing-v2/images/footer/linked.svg') }}" loading="lazy" alt="" />
                                <span>
                      لينكدين
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <img src="{{ asset('landing-v2/images/footer/insta.svg') }}" loading="lazy" alt="" />
                                <span>
                      انستغرام
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <img src="{{ asset('landing-v2/images/footer/facebook.svg') }}" loading="lazy" alt="" />
                                <span>
                      فيسبوك
                    </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="copyrights">
            <p>
                © 2024 جوابا. جميع الحقوق محفوظة.
            </p>

            <ul class="links">
                <li>
                    <a href="#">
                        إعدادات ملفات تعريف الارتباط
                    </a>
                </li>

                <li>
                    <a href="use-terms.html">
                        شروط الخدمة
                    </a>
                </li>

                <li>
                    <a href="privacy-policy.html">
                        سياسة الخصوصية
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<script src={{asset("landing-v2/js/lib/jquery4.js")}}></script>
<script src={{asset("landing-v2/js/lib/popper.js")}}></script>
<script src={{asset("landing-v2/js/lib/bootstrap.js")}}></script>
<script src={{asset("landing-v2/js/lib/swiper-bundle.min.js")}}></script>
<script src={{asset("landing-v2/js/main.js")}}></script>
</body>
</html>
