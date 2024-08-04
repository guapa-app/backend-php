@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("blog")) }}
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
                                    <a href="{{ route('landing') }}">
                                        الرئيسية
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('blogs') }}">
                                        المدونة
                                    </a>
                                </li>

                                <li>
                    <span>
                      عنوان المدونة
                    </span>
                                </li>
                            </ul>

                            <h1>
                                عنوان المدونة
                            </h1>

                            <p>
                                لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل. وبعد
                                موافقة
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

        <section class="single-blog general-section">
            <div class="container">
                <div class="image-contain">
                    <img src="{{ asset('frontend/assets/images/blogs/blog_3.png') }}" loading="lazy" alt=""/>
                </div>

                <div class="contain">
                    <div class="flex-data">

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
                        ما سبب وجود الرؤوس السوداء بعد عملية الأنف وطريقة علاجها
                    </h2>

                    <div class="flex-data">
              <span class="badge">
                نضارة
              </span>

                        <div class="date">
                            <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy" alt=""/>

                            <span>
                  تاريخ النشر
                </span>
                        </div>
                    </div>

                    <h2>
                        عنوان اخر
                    </h2>

                    <p>
                        هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى،
                        حيث
                        يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها
                        التطبيق.هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص
                        العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف
                        التى
                        يولدها التطبيق.
                    </p>

                    <h2>
                        عنوان اخر
                    </h2>

                    <p>
                        هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى،
                        حيث
                        يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها
                        التطبيق.هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص
                        العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف
                        التى
                        يولدها التطبيق.
                    </p>

                    <h2>
                        عنوان اخر
                    </h2>

                    <p>
                        هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى،
                        حيث
                        يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها
                        التطبيق.هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص
                        العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف
                        التى
                        يولدها التطبيق.
                    </p>
                </div>

            </div>
        </section>
    </main>
@endsection
