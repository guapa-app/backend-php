@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("welcome")) }}
@endsection
@section('content')
<main>
    <section class="sub-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="contain">
                        <ul class="list">
                            <li>
                                <a href={{ route('landing')}} >
                                    الرئيسية
                                </a>
                            </li>

                            <li>
                    <span>
                      المدونة
                    </span>
                            </li>
                        </ul>

                        <h1>
                            المدونة
                        </h1>

                        <p>
                            لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل. وبعد موافقة
                            العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص النهائية المطلوبة
                            للتصميم.
                        </p>
                    </div>
                </div>
            </div>

            <img
                src="{{ asset('frontend/assets/images/blogs/blog.svg') }}"
                class="icon"
                loading="lazy"
                alt=""/>
        </div>

        <img
            src="{{ asset('frontend/assets/images/icons/sub_header.png') }}"
            class="sub-header-img"
            loading="lazy"
            alt=""/>
    </section>

    <section class="blogs general-section border-shape">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mb-3">
                    <div class="box">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/blogs/img_1.png') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="contain">
                            <div class="flex-data">
                    <span class="badge">
                      نضارة
                    </span>

                                <div class="user-img">
                                    <img
                                        src="{{ asset('frontend/assets/images/blogs/user.png') }}"
                                        loading="lazy"
                                        alt=""/>

                                    <span>
                        الدكتور ياسر الكبيسي
                      </span>
                                </div>
                            </div>

                            <h2>
                                ما سبب وجود الرؤوس السوداء بعد عملية الأنف...
                            </h2>

                            <p>
                                يمكن حدوث اختلافات كبيرة في نسيج الجلد بعد عملية تجميل الأنف أو أي لمس للوجه؟
                            </p>

                            <div class="flex-data">
                                <div class="date">
                                    <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                                    <span>
                        تاريخ النشر
                      </span>
                                </div>

                                <a href="{{ route('single-blog') }}">
                      <span>
                        إقرأ المزيد
                      </span>

                                    <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}" loading="lazy" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <ul class="pagintain">
                        <li>
                            <a href="#" class="active">
                                1
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                2
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                3
                            </a>
                        </li>

                        <li>
                  <span>
                    ...
                  </span>
                        </li>

                        <li>
                            <a href="#">
                                8
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
