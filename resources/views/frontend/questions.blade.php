@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("questions")) }}
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
                    <span>
                      الاسئلة الشائعة
                    </span>
                                </li>
                            </ul>

                            <h1>
                                الاسئلة الشائعة
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
                    src="{{ asset('frontend/assets/images/questions/question_pattern.svg') }}"
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

        <section class="faq general-section border-shape">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-12 mb-4">
                        <div class="contain">
                            <h2>
                                الاسئلة العامة
                            </h2>

                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                       role="tab" aria-controls="pills-home" aria-selected="true">
                                        الاسعار
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                       role="tab" aria-controls="pills-profile" aria-selected="false">
                                        المدفوعات
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact"
                                       role="tab" aria-controls="pills-contact" aria-selected="false">
                                        الاسترجاع
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-service-tab" data-toggle="pill" href="#pills-service"
                                       role="tab" aria-controls="pills-service" aria-selected="false">
                                        الخدمات
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-8 col-12 mx-auto">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <button class="btn" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                          <span class="number">
                            1
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الاول ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseTwo" aria-expanded="false"
                                                    aria-controls="collapseTwo">
                          <span class="number">
                            2
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الرابع ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseThree" aria-expanded="false"
                                                    aria-controls="collapseThree">
                          <span class="number">
                            3
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الثالث ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingFour">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseFour" aria-expanded="false"
                                                    aria-controls="collapseFour">
                          <span class="number">
                            4
                          </span>

                                                <p>
                                                    ما هي وسائل الدفع التي تقبلها المنصة؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                 aria-labelledby="pills-profile-tab">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <button class="btn" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                          <span class="number">
                            1
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الاول ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseTwo" aria-expanded="false"
                                                    aria-controls="collapseTwo">
                          <span class="number">
                            2
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الرابع ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseThree" aria-expanded="false"
                                                    aria-controls="collapseThree">
                          <span class="number">
                            3
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الثالث ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingFour">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseFour" aria-expanded="false"
                                                    aria-controls="collapseFour">
                          <span class="number">
                            4
                          </span>

                                                <p>
                                                    ما هي وسائل الدفع التي تقبلها المنصة؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                 aria-labelledby="pills-contact-tab">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <button class="btn" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                          <span class="number">
                            1
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الاول ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseTwo" aria-expanded="false"
                                                    aria-controls="collapseTwo">
                          <span class="number">
                            2
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الرابع ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseThree" aria-expanded="false"
                                                    aria-controls="collapseThree">
                          <span class="number">
                            3
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الثالث ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingFour">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseFour" aria-expanded="false"
                                                    aria-controls="collapseFour">
                          <span class="number">
                            4
                          </span>

                                                <p>
                                                    ما هي وسائل الدفع التي تقبلها المنصة؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-service" role="tabpanel"
                                 aria-labelledby="pills-service-tab">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <button class="btn" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                          <span class="number">
                            1
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الاول ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseTwo" aria-expanded="false"
                                                    aria-controls="collapseTwo">
                          <span class="number">
                            2
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الرابع ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseThree" aria-expanded="false"
                                                    aria-controls="collapseThree">
                          <span class="number">
                            3
                          </span>

                                                <p>
                                                    يكتب هنا عنوان السؤال الثالث ؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingFour">
                                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                                    data-target="#collapseFour" aria-expanded="false"
                                                    aria-controls="collapseFour">
                          <span class="number">
                            4
                          </span>

                                                <p>
                                                    ما هي وسائل الدفع التي تقبلها المنصة؟
                                                </p>
                                            </button>
                                        </div>

                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>
                                                    الاجابة تظهر هنا وهذا شكل الاجابة قد يكون سطر او اكثر ... لوريم
                                                    إيبسوم
                                                    هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل.
                                                    وبعد
                                                    موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم
                                                    وضع
                                                    النصوص النهائية المطلوبة للتصميم.
                                                </p>
                                            </div>
                                        </div>
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
