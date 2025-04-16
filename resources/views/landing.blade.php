<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قوابا </title>
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/slick.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">
    @include('vendor.nova.partials.meta')
</head>
<body>
<section id="header-banner">
    <div class="banner p-lg-5">
        <div class="container">
            <div class="row ">
                <div class=" col-lg-5 col-sm-12">
                    <div class="content-logo">
                        <div class="img-logo d-md-inline-flex d-sm-block justify-content-center">
                            <div class="logo-1 mr-lg-2 mr-2 mb-sm-2">
                                <img src="{{ asset('landing/img/Logo-1.png') }}" alt="logo-1">
                            </div>
                            <div class="logo-2">
                                <img src="{{ asset('landing/img/Logo-2.png') }}" alt="logo-2">
                            </div>
                        </div>
                        <div class="logo-text">
                            <h2> قوابا - عالم الجمال</h2>
                            <p>
                                انها منصة تهدف الئ تمكين الوصول لجميع مايتعلق بعالم الجمال والاطلاع على افضل العروض
                                الخاصة بالاجراءات التجميلة الجراحية وغير الجراحية
                                .كما توفر منصة قوابا مركزا لطلب مختلف المنتجات والمستحضرات ذات العلاقة بعالم الجمال.
                                <br>
                                تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء من المستخدمين او مزودي الخدمات
                                المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي
                            </p>
                        </div>
                        <a href="{{ route('register.form') }}" class="btn btn-dark m-3">تسجيل كمزود خدمات</a>
                    </div>
                </div>
                <div class=" col-lg-7 col-sm-12">
                    <div class="mobile-img">
                        <img src="{{ asset('landing/img/MOBILE.png') }}" alt="mobile-img">
                    </div>
                </div>
            </div>
            <!--        row -->
        </div>

    </div>
    <!--     container -->
</section>
<!-- header-banner -->


<section id="services">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="service-motto text-center">
                    <h2 class=" wow slideInDown" data-wow-delay="1s">لو كنت مزود خدمة، كيف منصة قوابا ممكن تفيدك؟</h2>
                    <div class="text-service">
                        <p class=" wow slideInDown" data-wow-delay="1s">

                            انها منصة تهدف الى تمكين وصول المهتمين بعالم الجمال والإطلاع على اخر العروض الخاصة بعالم
                            الجمال سواء كانت اجراءات او منتجات

                        </p>
                    </div>
                </div>
            </div>
            <div class="services-show">
                <div class="flex-row-reverse row">
                    <div class=" col-lg-3 col-md-6 col-ms-12">
                        <div class="service-item">
                            <img src="{{ asset('landing/img/Group_5560@3x.png') }}" alt="service">
                            <p class=" wow slideInDown" data-wow-delay="1s">
                                احصل على المزيد من المرضى المهتمين بعمليات و منتجات التجميل عبر التطبيق.
                                كن على تواصل دائم وزد من امكانية تواصل العديد من المهتمين بالاجراءات او المنتجات
                                التجميلية
                            </p>
                        </div>
                    </div>
                    <!--                    col-md -4-->
                    <div class="col-lg-3 col-md-6  col-ms-12">
                        <div class="service-item">
                            <img src="{{ asset('landing/img/Mask_Group_26@3x.png') }}" alt="service">
                            <p class=" wow slideInDown" data-wow-delay="1s">
                                اضف منتجاتك و العروض المتاحة على المنتجات و اماكن البيع في دقائق من خلال التطبيق
                            </p>
                        </div>
                    </div>
                    <!--                    col-md -4-->
                    <div class="col-lg-3 col-md-6  col-ms-12">
                        <div class="service-item">
                            <img src="{{ asset('landing/img/Mask_Group_27@3x.png') }}" alt="service">
                            <p class=" wow slideInDown" data-wow-delay="1s">
                                قم بنشر عروضك و الاستفادة من قاعدة عملاء ضخمة.
                            </p>
                        </div>
                    </div>
                    <!--                    col-md -4-->
                    <div class="col-lg-3 col-md-6  col-ms-12">
                        <div class="service-item">
                            <img src="{{ asset('landing/img/Group.png') }}" alt="service">
                            <p class=" wow slideInDown" data-wow-delay="1s">
                                مجتمع قوابا هو مرجعك الى كل مايخص الجمال تقدر تشارك من خلاله مقاطع وارشادات .
                                في مجتمع قوابا ، شارك المهتمين بعالم الجمال وكن على تواصل معهم وزودهم بالمعلومات الصحيحة
                                وزيد من تقيمك

                            </p>
                        </div>
                    </div>
                    <!--                    col-md -4-->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- services -->

<section id="benefits">
    <div class="container">
        <div class="row">
            <div class=" col-lg-6 col-md-12 col-sm-12">
                <div class="app-photo">
                    <img src="{{ asset('landing/img/mockup.png') }}" alt="">
                </div>
                <!--                app-photo -->
            </div>
            <!--            col-->
            <div class=" col-lg-6 col-md-12 col-sm-12">
                <div class="benefits-text">
                    <h2 class=" wow slideInRight" data-wow-delay="1s"> اذا كنت مستخدم للخدمات ، كيف تساعدك منصة قوابا
                        ؟ </h2>
                    <div class="benefit-item d-flex">
                        <p class=" wow slideInDown" data-wow-delay="1s">
                            ابحث واطلع على جميع الاجراءات التجميلية الجراحية وغير الجراحية في عدة مناطق و احجز و اكد
                            موعدك
                        </p>
                        <img src="{{ asset('landing/img/Group_16.png') }}" alt="benefit-item">

                    </div>
                    <div class="benefit-item d-flex">
                        <p class=" wow slideInDown" data-wow-delay="1s">
                            تصفح وابحث عن العديد من المنتجات والمستحضرات التجميلية و العروض الخاصة بها مع امكانية طلبها
                            وتوصيلها عن طريق مزود الخدمة
                        </p>
                        <img src="{{ asset('landing/img/Group_16_Copy.png') }}" alt="benefit-item">

                    </div>
                    <div class="benefit-item d-flex">
                        <p class=" wow slideInDown" data-wow-delay="1s">
                            مجتمع قوابا هو مرجعك الى كل مايخص عالم الجمال تقدر تشوف من خلاله مقاطع وارشادات مزودي
                            الخدمات والمنتجات مع امكانية مشاركة ارائك وتجاربك
                        </p>
                        <img src="{{ asset('landing/img/Group_16_Copy_1.png') }}" alt="benefit-item">

                    </div>

                </div>
            </div>
            <!--            col -->
        </div>
        <!--        row-->
    </div>
    <!--    container-->
</section>
<!-- benefits-->
<section id="recommend">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-md-6 col-sm-12">
                <div class="img-recommend">
                    <img src="{{ asset('landing/img/Refer_and_Earn.png') }}" alt="Refer_and_earn">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="text-recommend">
                    <h2 class=" wow slideInDown" data-wow-delay="1s">حملو التطبيق وشاركوه مع اصحابكم </h2>
                    <p class=" wow slideInDown" data-wow-delay="1s">
                    </p>
                    <p class=" wow slideInDown" data-wow-delay="1s">حمل التطبيق الأن </p>

                    <h4> للمستخدمين</h4>
                    <div class="downloads-app d-flex">

                        <a href="https://apps.apple.com/sa/app/%D9%82%D9%88%D8%A7%D8%A8%D8%A7-%D8%B9%D8%A7%D9%84%D9%85-%D8%A7%D9%84%D8%AC%D9%85%D8%A7%D9%84/id1552554758?l=ar"
                           class="m-3">
                            <img src="{{ asset('landing/img/Download_on_the_App_Store.png') }}" class="apple"
                                 alt="Apple">
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.guapa.app" class="m-3">
                            <img src="{{ asset('landing/img/google-play.png') }}" class="google-play" alt="google-play">
                        </a>
                    </div>

                    <h4> لمزودي الخدمات</h4>

                    <div class="downloads-app d-flex">

                        <a href="https://apps.apple.com/us/app/%D9%82%D9%88%D8%A7%D8%A8%D8%A7-%D9%85%D9%82%D8%AF%D9%85%D9%8A-%D8%A7%D9%84%D8%AE%D8%AF%D9%85%D8%A7%D8%AA/id1549047437"
                           class="m-3">
                            <img src="{{ asset('landing/img/Download_on_the_App_Store.png') }}" class="apple"
                                 alt="Apple">
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.app.guapa_provider" class="m-3">
                            <img src="{{ asset('landing/img/google-play.png') }}" class="google-play" alt="google-play">
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <!--         row -->
    </div>
    <!--    container-->
</section>

<!--testimonials-->
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">

                <div class="social-media d-flex">
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape_3.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape_2.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape_1.png') }}" alt=""></a>
                </div>


                <!--                socical-->
            </div>
            <!--            col-->
            <div class="col-md-4">
                <p>
                    966 53 1434 3889

                    <i class="fa fa-mobile-alt"></i>
                </p>
            </div>
            <!--            col -->
            <div class="col-md-4">
                <p>
                    info@guapa.com.sa
                    <i class="fa fa-envelope-square"></i>
                </p>
            </div>
            <!--            col -->
        </div>
    </div>
</footer>
<script type="text/javascript" src="{{ asset('landing/js/jquery-3.0.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('landing/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('landing/js/slick.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('landing/js/app.js') }}"></script>


</body>
</html>
