@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('download')) }}
@endsection
@section('content')
    <main>
        <section class="mobile-contain secondary">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="contain">
                            <span> عن تطبيق قوابا </span>

                            <h1>
                                تطبيق قوابا لمزودي الخدمة
                            </h1>

                            <p>
                                في "قوابا" هو أداة متكاملة تُمكن مقدمي خدمات التجميل من إدارة أعمالهم بكفاءة، من خلال أدوات
                                متطورة لتقديم العروض، متابعة التقييمات، وتحليل الأداء. صُمم التطبيق ليدعم نموهم وتعزيز
                                تفاعلهم مع العملاء، مما يساهم في تحسين جودة الخدمات وتوسيع قاعدة عملائهم.
                            </p>

                            <a href="{{ route('register.form') }}" class="custom-btn secondary-btn mt-4">
                                <span>
                                    تسجيل كمزود خدمة
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/mobile_1.svg') }}" loading="lazy" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="application-features general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>
                        مميزات التطبيق
                    </h1>

                    <p>
                        في منصة "قوابا" يتيح لمقدمي خدمات التجميل الاستفادة من مجموعة من الميزات المصممة لدعم وتعزيز
                        أعمالهم. إليك أبرز هذه المميزات:
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-4 mx-auto mb-3">
                        <div class="box">
                            <div class="image-contain">
                                <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="secondary" loading="lazy"
                                    alt="" />
                            </div>

                            <h2>
                                ميزة التطبيق رقم 1
                            </h2>

                            <p>
                                **واجهة إدارة سهلة الاستخدام:** يوفر التطبيق واجهة مستخدم بسيطة وسهلة للتنقل، مما يسهل على
                                مقدمي الخدمات إدارة عروضهم، تحديث بياناتهم، ومعالجة طلباتهم بكفاءة.
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-4 col-12 mb-3 right-data">
                                <div class="data-contain">
                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="secondary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 2
                                        </h2>

                                        <p>
                                            **إدارة الملف الشخصي والعروض:** يتيح التطبيق لمقدمي الخدمات إنشاء وتحديث ملفاتهم
                                            الشخصية، بما في ذلك المعلومات الأساسية، الصور، والتفاصيل حول الخدمات المقدمة.
                                            كما يمكنهم عرض العروض والخصومات الخاصة لجذب العملاء.
                                        </p>
                                    </div>

                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="secondary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 3
                                        </h2>

                                        <p>
                                            **نظام تقييم ومراجعات:** يوفر التطبيق نظامًا لتلقي تقييمات ومراجعات من العملاء،
                                            مما يساعد في بناء سمعة قوية ويعزز من الثقة والشفافية بين مقدمي الخدمات والعملاء.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-3">
                                <div class="image-app">
                                    <img src="{{ asset('frontend/assets/images/app.png') }}" loading="lazy"
                                        alt="" />
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-3 left-data">
                                <div class="data-contain">
                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="secondary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 4
                                        </h2>

                                        <p>
                                            **تحليلات وتقارير:** يقدم التطبيق تقارير وتحليلات شاملة حول أداء الخدمات، بما في
                                            ذلك عدد الحجوزات، الإيرادات، وتفضيلات العملاء، مما يمكن مقدمي الخدمات من اتخاذ
                                            قرارات مدروسة لتحسين أدائهم.
                                        </p>
                                    </div>

                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="secondary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 5
                                        </h2>

                                        <p>
                                            **إشعارات وتنبيهات فورية:** يتلقى مقدمو الخدمات إشعارات فورية حول الحجوزات
                                            الجديدة، التعديلات، والتعليقات من العملاء، مما يساعد في متابعة التحديثات
                                            والتفاعل بسرعة.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mx-auto mb-3">
                        <div class="box">
                            <div class="image-contain">
                                <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="secondary" loading="lazy"
                                    alt="" />
                            </div>

                            <h2>
                                ميزة التطبيق رقم 6
                            </h2>

                            <p>
                                **دعم وتواصل مباشر:** يوفر التطبيق وسائل للتواصل المباشر مع فريق دعم "قوابا" لحل أي مشكلات
                                أو استفسارات بسرعة وفعالية.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="box">
                        <p>
                            هذه الميزات تساهم في تحسين تجربة مقدمي الخدمات، وتعزيز كفاءتهم، وزيادة رضا العملاء.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
