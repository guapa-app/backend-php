@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("about")) }}
@endsection
@section('content')

    <main>
    <section class="sub-header">
        <div class="container">
            <div class="data-contain">
            <span>
              عن شركة جوابا
            </span>

                <h1>
                    جوابا وسيلة ربط هي الاولى من نوعها في العالم العربي:
                </h1>

                <p>
                    لوريم إيبسوم هو نص مؤقت يستخدم في التصميم والنشر لإظهار شكل الوثيقة أو الخط دون الاعتماد على محتوى
                    معنوي. قد يستخدم لوريم إيبسوم كنص بديل قبل وضع النص النهائي المطلوب للتصميم. كما يستخدم لإخفاء النص
                    في عملية تسمى بالتغريق
                </p>

                <div class="row">
                    <div class="col-lg-4 col-12 mb-4">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/messages/message_1.svg') }}" loading="lazy" alt=""/>

                            <h2>
                                رسالتنا
                            </h2>

                            <p>
                                لوريم إيبسوم هو نص مؤقت يستخدم في التصميم والنشر لإظهار شكل الوثيقة أو الخط دون الاعتماد
                                على محتوى معنوي
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-12 mb-4">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/messages/message_2.svg') }}" loading="lazy" alt=""/>

                            <h2>
                                غايتنا
                            </h2>

                            <p>
                                لوريم إيبسوم هو نص مؤقت يستخدم في التصميم والنشر لإظهار شكل الوثيقة أو الخط دون الاعتماد
                                على محتوى معنوي
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-12 mb-4">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/messages/message_3.svg') }}" loading="lazy" alt=""/>

                            <h2>
                                رؤيتنا
                            </h2>

                            <p>
                                لوريم إيبسوم هو نص مؤقت يستخدم في التصميم والنشر لإظهار شكل الوثيقة أو الخط دون الاعتماد
                                على محتوى معنوي
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <img
            src="{{ asset('frontend/assets/images/icons/sub_header.png') }}"
            class="sub-header-img opcity-shape"
            loading="lazy"
            alt=""/>
    </section>

    <section class="sliders general-section">
        <div class="container">
            <div class="custom-heading">
                <h1>
                    موثوق به من قبل أكثر من 200 علامة تجارية
                </h1>
            </div>

            <div class="swiper swiper-brands">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                    </div>

                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="information general-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="contain">
                <span>
                  لمحة عن قوابا
                </span>

                        <h1>
                            لا نتوقف أبدًا عن التطور
                        </h1>

                        <p>
                            لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل. وبعد موافقة
                            العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص النهائية المطلوبة
                            للتصميم. لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل. وبعد
                            موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص النهائية
                            المطلوبة للتصميم. لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على
                            العميل. وبعد موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص
                            النهائية المطلوبة للتصميم.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <img src="{{ asset('frontend/assets/images/hero.png') }}" loading="lazy" alt=""/>
    </section>
</main>
@endsection
