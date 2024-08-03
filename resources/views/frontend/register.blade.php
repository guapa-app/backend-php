@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__("register")) }}
@endsection
@section('content')
    <main>
    <section class="register">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-12 mb-3 px-0">
                    <div class="data-contain">
                        <div class="row">
                            <div class="col-lg-8 col-12 mx-auto">
                                <a href="{{ route('landing') }}" class="brand-name">
                                    <img src="{{ asset('frontend/assets/images/logo/logo.svg') }}" loading="lazy" alt=""/>
                                </a>

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a
                                            class="nav-link active"
                                            id="userDetailes-tab"
                                            data-toggle="tab"
                                            href="#userDetailes"
                                            role="tab"
                                            aria-controls="userDetailes"
                                            aria-selected="true"
                                        >
                                            <div class="number">01</div>

                                            <span> البيانات الاساسية </span>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a
                                            class="nav-link"
                                            id="choosePackage-tab"
                                            data-toggle="tab"
                                            href="#choosePackage"
                                            role="tab"
                                            aria-controls="choosePackage"
                                            aria-selected="false"
                                        >
                                            <div class="number">02</div>

                                            <span> بيانات النشاط </span>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a
                                            class="nav-link"
                                            id="payment-tab"
                                            data-toggle="tab"
                                            href="#payment"
                                            role="tab"
                                            aria-controls="payment"
                                            aria-selected="false"
                                        >
                                            <div class="number">03</div>

                                            <span> سوشيال ميديا </span>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a
                                            class="nav-link"
                                            id="allDone-tab"
                                            data-toggle="tab"
                                            href="#allDone"
                                            role="tab"
                                            aria-controls="allDone"
                                            aria-selected="false"
                                        >
                                            <div class="number">04</div>

                                            <span> العناوين </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-12">
                                <div class="tab-content" id="myTabContent">
                                    <div
                                        class="tab-pane fade show active"
                                        id="userDetailes"
                                        role="tabpanel"
                                        aria-labelledby="userDetailes-tab"
                                    >
                                        <div class="row">
                                            <div class="col-lg-10 col-12 mx-auto">
                                                <div class="contain">
                                                    <div class="form-contain">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="name"> الاسم الاول </label>

                                                                    <div class="form-icon">
                                                                        <input
                                                                            type="text"
                                                                            class="form-control"
                                                                            placeholder="الاسم الاول"
                                                                            id="name"
                                                                            name="name"
                                                                        />
                                                                    </div>

                                                                    <!-- <small class="error">
                                                                      error message
                                                                    </small> -->
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="Birthdate">
                                                                        الاسم الاخير
                                                                    </label>

                                                                    <div class="form-icon">
                                                                        <input
                                                                            type="text"
                                                                            class="form-control"
                                                                            placeholder="الاسم الاخير"
                                                                            id="Birthdate"
                                                                            name="Birthdate"
                                                                        />
                                                                    </div>

                                                                    <!-- <small class="error">
                                                                      error message
                                                                    </small> -->
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-12 px-2">
                                                                <div class="form-group">
                                                                    <label for="email">
                                                                        البريد الالكتروني
                                                                    </label>

                                                                    <div class="form-icon">
                                                                        <input
                                                                            type="email"
                                                                            class="form-control"
                                                                            placeholder="البريد الالكتروني"
                                                                            id="email"
                                                                            name="email"
                                                                        />
                                                                    </div>

                                                                    <!-- <small class="error">
                                                                      error message
                                                                    </small> -->
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-12 px-2">
                                                                <div class="form-group">
                                                                    <label for="whatsappNumber">
                                                                        رقم الهاتف
                                                                    </label>

                                                                    <div class="form-icon">
                                                                        <input
                                                                            type="number"
                                                                            class="form-control"
                                                                            placeholder="رقم الهاتف"
                                                                            name="mobileNumber"
                                                                        />
                                                                    </div>

                                                                    <!-- <small class="error">
                                                                      error message
                                                                    </small> -->
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="password">
                                                                        كلمة المرور
                                                                    </label>

                                                                    <div class="show_hide_password">
                                                                        <input
                                                                            type="password"
                                                                            class="form-control"
                                                                            placeholder="كلمة المرور"
                                                                            id="password"
                                                                            name="password"
                                                                        />

                                                                        <div class="show-pass">
                                                                            <img
                                                                                src="{{ asset('frontend/assets/images/icons/password.svg') }}"
                                                                                class="icon"
                                                                                loading="lazy"
                                                                                alt=""
                                                                            />
                                                                            <img
                                                                                src="{{ asset('frontend/assets/images/icons/show-pass.svg') }}"
                                                                                class="slash-icon"
                                                                                loading="lazy"
                                                                                alt=""
                                                                            />
                                                                        </div>
                                                                    </div>

                                                                    <!-- <small class="error">
                                                                      error message
                                                                    </small> -->
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="passwordAgain">
                                                                        تأكيد كلمة المرور
                                                                    </label>

                                                                    <div class="show_hide_password">
                                                                        <input
                                                                            type="password"
                                                                            class="form-control"
                                                                            placeholder="تأكيد كلمة المرور"
                                                                            id="passwordAgain"
                                                                            name="passwordAgain"
                                                                        />

                                                                        <div class="show-pass">
                                                                            <img
                                                                                src="{{ asset('frontend/assets/images/icons/password.svg') }}"
                                                                                class="icon"
                                                                                loading="lazy"
                                                                                alt=""
                                                                            />
                                                                            <img
                                                                                src="{{ asset('frontend/assets/images/icons/show-pass.svg') }}"
                                                                                class="slash-icon"
                                                                                loading="lazy"
                                                                                alt=""
                                                                            />
                                                                        </div>
                                                                    </div>

                                                                    <!-- <small class="error">
                                                                      error message
                                                                    </small> -->
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a href="#" class="custom-btn primary-btn next-step">
                                                            <span> حفظ ومتابعة </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div
                                        class="tab-pane fade"
                                        id="choosePackage"
                                        role="tabpanel"
                                        aria-labelledby="choosePackage-tab"
                                    >
                                        <div class="row">
                                            <div class="col-lg-10 col-12 mx-auto">
                                                <div class="form-contain">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="name">
                                                                    اسم النشاط
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        placeholder="اسم النشاط"
                                                                        id="name"
                                                                        name="name"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="Birthdate">
                                                                    المجال
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        placeholder="المجال"
                                                                        id="Birthdate"
                                                                        name="Birthdate"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="email">
                                                                    البريد الالكتروني للتواصل
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="email"
                                                                        class="form-control"
                                                                        placeholder="البريد الالكتروني للتواصل"
                                                                        id="email"
                                                                        name="email"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    رقم الهاتف للتواصل
                                                                </label>

                                                                <div class="form-icon ">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="رقم الهاتف للتواصل"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group">
                                                                <label for="password">
                                                                    وصف النشاط
                                                                </label>

                                                                <div class="form-icon text-area">
                                                                    <textarea name="" class="form-control"
                                                                              placeholder="وصف النشاط" id=""></textarea>
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    الرقم الضريبي
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="الرقم الضريبي"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    رقم التصنيف
                                                                </label>

                                                                <div class="form-icon ">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="رقم التصنيف"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group file-input-group">
                                                                <div class="file__input" id="file__input_worker">
                                                                    <input type="file" id="worker_resume"
                                                                           class="form-control file__input--file_worker"
                                                                           accept=".svg, .webp, .jpg, .png, .jpeg">
                                                                    <label for="worker_resume"
                                                                           class="file__input--label form-label-shape">
                                                                        <img src="{{ asset('frontend/assets/images/icons/upload.svg') }}"
                                                                             loading="lazy" alt="">

                                                                        <div class="data">
                                                                            <h2>
                                                                                الشعار
                                                                            </h2>
                                                                        </div>
                                                                    </label>
                                                                    <!-- <span class="error" id="worker_resume_error"></span> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="button-contain">
                                                        <a href="#" class="custom-btn secondary-btn prev-step">

                                                            <span> تراجع </span>
                                                        </a>

                                                        <a href="#" class="custom-btn primary-btn next-step">

                                                            <span> حفظ ومتابعة </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="tab-pane fade"
                                        id="payment"
                                        role="tabpanel"
                                        aria-labelledby="payment-tab"
                                    >
                                        <div class="row">
                                            <div class="col-lg-10 col-12 mx-auto">
                                                <div class="form-contain">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="name">
                                                                    واتساب
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        placeholder="واتساب"
                                                                        id="name"
                                                                        name="name"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="Birthdate">
                                                                    تويتر
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        placeholder="تويتر"
                                                                        id="Birthdate"
                                                                        name="Birthdate"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="email">
                                                                    انستجرام
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="email"
                                                                        class="form-control"
                                                                        placeholder="انستجرام"
                                                                        id="email"
                                                                        name="email"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    سناب شات
                                                                </label>

                                                                <div class="form-icon ">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="سناب شات"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    موقع الكتروني
                                                                </label>

                                                                <div class="form-icon ">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="موقع الكتروني"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    رابط معروف
                                                                </label>

                                                                <div class="form-icon ">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="رابط معروف"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="button-contain">
                                                        <a href="#" class="custom-btn secondary-btn prev-step">

                                                            <span> تراجع </span>
                                                        </a>

                                                        <a href="#" class="custom-btn primary-btn next-step">

                                                            <span> حفظ ومتابعة </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="tab-pane fade"
                                        id="allDone"
                                        role="tabpanel"
                                        aria-labelledby="allDone-tab"
                                    >
                                        <div class="row">
                                            <div class="col-lg-10 col-12 mx-auto">
                                                <div class="form-contain">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="name">
                                                                    نوع العنوان
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        placeholder="نوع العنوان"
                                                                        id="name"
                                                                        name="name"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label for="Birthdate">
                                                                    المدينة
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        placeholder="المدينة"
                                                                        id="Birthdate"
                                                                        name="Birthdate"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="email">
                                                                    العنوان 1
                                                                </label>

                                                                <div class="form-icon">
                                                                    <input
                                                                        type="email"
                                                                        class="form-control"
                                                                        placeholder="العنوان 1"
                                                                        id="email"
                                                                        name="email"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-12 px-2">
                                                            <div class="form-group">
                                                                <label for="whatsappNumber">
                                                                    العنوان 2
                                                                </label>

                                                                <div class="form-icon ">
                                                                    <input
                                                                        type="number"
                                                                        placeholder="العنوان 2"
                                                                        class="form-control"
                                                                        name="mobileNumber"
                                                                    />
                                                                </div>

                                                                <!-- <small class="error">
                                                                  error message
                                                                </small> -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="button-contain">
                                                        <a href="#" class="custom-btn secondary-btn prev-step">

                                                            <span> تراجع </span>
                                                        </a>

                                                        <a href="#" class="custom-btn primary-btn next-step">

                                                            <span> حفظ ومتابعة </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('login') }}" class="link">
                                لدي حساب بالفعل

                                <span>
                      تسجيل دخول
                    </span>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 col-12 px-0">
                    <div class="register-contain">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/intro/intro.svg') }}" loading="lazy" alt="">
                        </div>

                        <h1>
                            قوابا - عالم الجمال
                        </h1>

                        <p>
                            قوابا منصة تهدف الي تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على افضل العروض الخاصة
                            بالاجراءات التجميلة الجراحية وغير الجراحية، كما توفر منصة قوابا مركزا لطلب مختلف المنتجات
                            والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء
                            من المستخدمين او مزودي الخدمات المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
