@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('about')) }}
@endsection
@section('content')
    <main>
        <section class="mobile-contain primary">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="contain">
                            <span> عن تطبيق قوابا </span>

                            <h1>تطبيق قوابا للمستخدمين</h1>

                            <p>
                                في "قوابا" يوفر تجربة سلسة وممتعة للعثورعلى عروض خدمات التجميل المفضلة، مع إمكانية الاطلاع
                                على تقييمات موثوقة ومحتوى تعليمي مميز. يهدف التطبيق إلى تسهيل الوصول إلى أفضل مقدمي الخدمات
                                وتعزيز تجربة العناية الشخصية بلمسة من الابتكار والراحة.
                            </p>
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
                        في منصة "قوابا" يتيح للمستخدمين الاستفادة من مجموعة من الميزات المصممة لتحسين تجربة التجميل والعناية
                        الشخصية. إليك أبرز هذه المميزات:
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-4 mx-auto mb-3">
                        <div class="box">
                            <div class="image-contain">
                                <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="primary" loading="lazy"
                                    alt="" />
                            </div>

                            <h2>
                                ميزة التطبيق رقم 1
                            </h2>

                            <p>
                                **بحث متقدم عن مقدمي الخدمات:** يمكن للمستخدمين البحث بسهولة عن مقدمي خدمات التجميل بناءً
                                على الموقع، التخصص، التقييمات، والأسعار، مما يساعدهم في العثور على الأنسب لاحتياجاتهم.
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-4 col-12 mb-3 right-data">
                                <div class="data-contain">
                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="primary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 2
                                        </h2>

                                        <p>
                                            **تقييم ومراجعة الخدمات:** يمكن للمستخدمين كتابة تقييمات ومراجعات حول الخدمات
                                            التي تلقوها، مما يساعد في بناء مجتمع شفّاف ويعزز الثقة بين المستخدمين ومقدمي
                                            الخدمات.
                                        </p>
                                    </div>

                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="primary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 3
                                        </h2>

                                        <p>
                                            **محتوى تعليمي وتوعوي:** يوفر التطبيق معلومات ونصائح تعليمية حول أحدث الاتجاهات
                                            في عالم التجميل والعناية الشخصية، مما يمكن المستخدمين من اتخاذ قرارات مستنيرة
                                            ويعزز تجربتهم.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-3">
                                <div class="image-app">
                                    <img src="{{ asset('frontend/assets/images/app-2.png') }}" loading="lazy"
                                        alt="" />
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-3 left-data">
                                <div class="data-contain">
                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="primary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 4
                                        </h2>

                                        <p>
                                            **إشعارات وتنبيهات مخصصة:** يتلقى المستخدمون إشعارات حول العروض والخصومات
                                            الخاصة، والمستجدات من مقدمي الخدمات، مما يبقيهم على اطلاع دائم بكل جديد.
                                        </p>
                                    </div>

                                    <div class="box">
                                        <div class="image-contain">
                                            <img src="{{ asset('frontend/assets/images/crown.svg') }}" class="primary"
                                                loading="lazy" alt="" />
                                        </div>

                                        <h2>
                                            ميزة التطبيق رقم 5
                                        </h2>

                                        <p>
                                            **استكشاف العروض والخصومات:** يوفر التطبيق للمستخدمين إمكانية استكشاف العروض
                                            الخاصة والخصومات الحصرية، مما يتيح لهم الاستفادة من فرص توفير المال على خدمات
                                            التجميل.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="box">
                        <p>
                            تعمل هذه المميزات على تحسين تجربة المستخدم، تسهيل الوصول إلى خدمات التجميل، وتعزيز راحة ورضا
                            المستخدمين في رحلة العناية الشخصية.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
