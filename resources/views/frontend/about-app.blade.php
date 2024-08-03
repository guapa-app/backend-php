@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("about")) }}
@endsection
@section('content')

    <main>
        <section class="mobile-contain primary">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="contain">
                            <span> عن تطبيق جوابا </span>

                            <h1>تطبيق جوابا للمستخدمين</h1>

                            <p>
                                لوريم إيبسوم هو نص مؤقت يستخدم في التصميم والنشر لإظهار شكل
                                الوثيقة أو الخط دون الاعتماد على محتوى معنوي. قد يستخدم لوريم
                                إيبسوم كنص بديل قبل وضع النص النهائي المطلوب للتصميم. كما
                                يستخدم لإخفاء النص في عملية تسمى بالتغريق
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/mobile_1.svg') }}" loading="lazy" alt=""/>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq general-section border-shape">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="download primary-color">
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-3">
                                    <div class="contain-data">
                                        <h1>هل اعجبتك مميزات التطبيق ؟</h1>

                                        <p>لا تترد حمل التطبيق الان</p>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-12 mb-3">
                                    <div class="mobile-button">
                                        <a href="#" class="download-btn">
                                            <img
                                                src="{{ asset('frontend/assets/images/icons/app-store.svg') }}"
                                                loading="lazy"
                                                alt=""
                                            />
                                        </a>

                                        <a href="#" class="download-btn">
                                            <img
                                                src="{{ asset('frontend/assets/images/icons/google_play.svg') }}"
                                                loading="lazy"
                                                alt=""
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="heading-shape">
                            <img
                                src="{{ asset('frontend/assets/images/crown.svg') }}"
                                class="primary"
                                loading="lazy"
                                alt=""
                            />

                            <h1>ميزة التطبيق رقم واحد</h1>
                        </div>

                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button
                                        class="btn"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseOne"
                                        aria-expanded="true"
                                        aria-controls="collapseOne"
                                    >
                                        <span class="number"> 1 </span>

                                        <p>يكتب هنا عنوان السؤال الاول ؟</p>
                                    </button>
                                </div>

                                <div
                                    id="collapseOne"
                                    class="collapse show"
                                    aria-labelledby="headingOne"
                                    data-parent="#accordionExample"
                                >
                                    <div class="card-body">
                                        <p>
                                            الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر
                                            ... لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم
                                            التي سيتم عرضها على العميل. وبعد موافقة العميل على بداية
                                            التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص
                                            النهائية المطلوبة للتصميم.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <button
                                        class="btn collapsed"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="collapseTwo"
                                    >
                                        <span class="number"> 2 </span>

                                        <p>يكتب هنا عنوان السؤال الرابع ؟</p>
                                    </button>
                                </div>

                                <div
                                    id="collapseTwo"
                                    class="collapse"
                                    aria-labelledby="headingTwo"
                                    data-parent="#accordionExample"
                                >
                                    <div class="card-body">
                                        <p>
                                            الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر
                                            ... لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم
                                            التي سيتم عرضها على العميل. وبعد موافقة العميل على بداية
                                            التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص
                                            النهائية المطلوبة للتصميم.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <button
                                        class="btn collapsed"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseThree"
                                        aria-expanded="false"
                                        aria-controls="collapseThree"
                                    >
                                        <span class="number"> 3 </span>

                                        <p>يكتب هنا عنوان السؤال الثالث ؟</p>
                                    </button>
                                </div>

                                <div
                                    id="collapseThree"
                                    class="collapse"
                                    aria-labelledby="headingThree"
                                    data-parent="#accordionExample"
                                >
                                    <div class="card-body">
                                        <p>
                                            الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر
                                            ... لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم
                                            التي سيتم عرضها على العميل. وبعد موافقة العميل على بداية
                                            التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص
                                            النهائية المطلوبة للتصميم.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="headingFour">
                                    <button
                                        class="btn collapsed"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseFour"
                                        aria-expanded="false"
                                        aria-controls="collapseFour"
                                    >
                                        <span class="number"> 4 </span>

                                        <p>ما هي وسائل الدفع التي تقبلها المنصة؟</p>
                                    </button>
                                </div>

                                <div
                                    id="collapseFour"
                                    class="collapse"
                                    aria-labelledby="headingFour"
                                    data-parent="#accordionExample"
                                >
                                    <div class="card-body">
                                        <p>
                                            الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر
                                            ... لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم
                                            التي سيتم عرضها على العميل. وبعد موافقة العميل على بداية
                                            التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص
                                            النهائية المطلوبة للتصميم.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
@endsection

