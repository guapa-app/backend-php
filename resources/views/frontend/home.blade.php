@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('welcome')) }}
@endsection
@section('content')
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
                                قوابا منصة تهدف الي تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على افضل العروض الخاصة
                                بالاجراءات التجميلة الجراحية وغير الجراحية، كما توفر منصة قوابا مركزا لطلب مختلف المنتجات
                                والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء
                                من المستخدمين او مزودي الخدمات المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي.
                            </p>

                            <div class="mobile-button">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                        alt="" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                        alt="" />
                                </a>
                            </div>

                            <div class="flex-data">
                                <span>
                                    تقييم
                                    4.5 out of 5
                                </span>

                                <img src="{{ asset('frontend/assets/images/icons/stars.svg') }}" loading="lazy"
                                    alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mb-3">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/intro/intro.svg') }}" loading="lazy"
                                alt="" />
                        </div>
                    </div>
                </div>
            </div>

            <a href="#features" class="down-arrow">
                <img src="{{ asset('frontend/assets/images/icons/down_arrow.svg') }}" loading="lazy" alt="" />
            </a>
        </header>

        <section id="features" class="features general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>
                        كيف يمكنك الاستفادة من قوابا إذا كنت مزود خدمة
                    </h1>

                    <p>
                        انها منصة تهدف الى تمكين وصول المهتمين بعالم الجمال والإطلاع على اخر العروض الخاصة بعالم الجمال سواء
                        كانت اجراءات او منتجات
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-12 mb-3">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/feature/feature_1.svg') }}" loading="lazy"
                                alt="" />

                            <h2>
                                الإستهداف
                            </h2>

                            <p>
                                احصل على المزيد من المرضى المهتمين بعمليات و منتجات التجميل عبر التطبيق. كن على تواصل دائم
                                وزد من امكانية تواصل العديد من المهتمين بالاجراءات او المنتجات التجميلية
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 mb-3">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/feature/feature_2.svg') }}" loading="lazy"
                                alt="" />

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
                            <img src="{{ asset('frontend/assets/images/feature/feature_3.svg') }}" loading="lazy"
                                alt="" />

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
                            <img src="{{ asset('frontend/assets/images/feature/feature_4.svg') }}" loading="lazy"
                                alt="" />

                            <h2>
                                مجتمع خاص بك
                            </h2>

                            <p>
                                احصل على المزيد من المرضى المهتمين بعمليات و منتجات مجتمع قوابا هو مرجعك الى كل مايخص الجمال
                                تقدر تشارك من خلاله مقاطع وارشادات، شارك المهتمين بعالم الجمال وكن على تواصل معهم وزودهم
                                بالمعلومات الصحيحة وزيد من تقيمك.
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
                            <img src="{{ asset('frontend/assets/images/take/take_it.svg') }}" loading="lazy"
                                alt="" />
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mb-3">
                        <div class="contain">
                            <ul class="list">
                                <li>
                                    <img src="{{ asset('frontend/assets/images/take/take_1.svg') }}" loading="lazy"
                                        alt="" />

                                    <div class="data">
                                        <h2>
                                            قم بحجز وتأكيد الإجراء أو العرض أو المنتج الخاص بك.
                                        </h2>

                                        <p>
                                            ابحث واطلع على جميع الاجراءات التجميلية الجراحية وغير الجراحية في عدة مناطق و
                                            احجز و اكد موعدك.
                                        </p>
                                    </div>
                                </li>

                                <li>
                                    <img src="{{ asset('frontend/assets/images/take/take_2.svg') }}" loading="lazy"
                                        alt="" />

                                    <div class="data">
                                        <h2>
                                            سهولة الطلب والتوصيل
                                        </h2>

                                        <p>
                                            تصفح وابحث عن العديد من المنتجات والمستحضرات التجميلية و العروض الخاصة بها مع
                                            امكانية طلبها وتوصيلها عن طريق مزود الخدمة.
                                        </p>
                                    </div>
                                </li>

                                <li>
                                    <img src="{{ asset('frontend/assets/images/take/take_3.svg') }}" loading="lazy"
                                        alt="" />

                                    <div class="data">
                                        <h2>
                                            مجتمع خاص بعالم الجمال
                                        </h2>

                                        <p>
                                            مجتمع قوابا هو مرجعك الى كل مايخص عالم الجمال تقدر تشوف من خلاله مقاطع وارشادات
                                            مزودي الخدمات والمنتجات مع امكانية مشاركة ارائك وتجاربك
                                        </p>
                                    </div>
                                </li>
                            </ul>


                            <a href="{{ route('login') }}" class="custom-btn secondary-btn">
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
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">
                                للمستخدمين
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">
                                لمزودي الخدمات
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="mobile-button">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                        alt="" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                        alt="" />
                                </a>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="mobile-button">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                        alt="" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                        alt="" />
                                </a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </section>
    </main>
@endsection
