@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('register')) }}
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
                                        <img src="{{ asset('frontend/assets/images/logo/logo.svg') }}" loading="lazy"
                                            alt="" />
                                    </a>

                                    @include('alert-message')
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="userDetailes-tab" data-toggle="tab"
                                                href="#userDetailes" role="tab" aria-controls="userDetailes"
                                                aria-selected="true">
                                                <div class="number">01</div>

                                                <span> البيانات الاساسية </span>
                                            </a>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="choosePackage-tab" data-toggle="tab"
                                                href="#choosePackage" role="tab" aria-controls="choosePackage"
                                                aria-selected="false">
                                                <div class="number">02</div>

                                                <span> بيانات النشاط </span>
                                            </a>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment"
                                                role="tab" aria-controls="payment" aria-selected="false">
                                                <div class="number">03</div>

                                                <span> سوشيال ميديا </span>
                                            </a>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="allDone-tab" data-toggle="tab" href="#allDone"
                                                role="tab" aria-controls="allDone" aria-selected="false">
                                                <div class="number">04</div>

                                                <span> العناوين </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>


                                <form id="register-form" method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="col-12">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="userDetailes" role="tabpanel"
                                                aria-labelledby="userDetailes-tab">
                                                <div class="row">
                                                    <div class="col-lg-10 col-12 mx-auto">
                                                        <div class="contain">
                                                            <div class="form-contain">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="name"> الاسم الاول </label>

                                                                            <div class="form-icon">
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="الاسم الاول"
                                                                                    id="user[firstname]"
                                                                                    name="user[firstname]"
                                                                                    value="{{ old('user.firstname') }}" />
                                                                            </div>
                                                                            @error('user.firstname')
                                                                                <small
                                                                                    class="error">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="Birthdate">
                                                                                الاسم الاخير
                                                                            </label>

                                                                            <div class="form-icon">
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="الاسم الاخير"
                                                                                    id="user[lastname]"
                                                                                    name="user[lastname]"
                                                                                    value="{{ old('user.lastname') }}" />
                                                                            </div>
                                                                            @error('user.lastname')
                                                                                <small class="error">
                                                                                    {{ $message }}
                                                                                </small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-12 px-2">
                                                                        <div class="form-group">
                                                                            <label for="email">
                                                                                البريد الالكتروني
                                                                            </label>

                                                                            <div class="form-icon">
                                                                                <input class="form-control"
                                                                                    placeholder="البريد الالكتروني"
                                                                                    id="user[email]" name="user[email]"
                                                                                    type="email"
                                                                                    value="{{ old('user.email') }}" />
                                                                            </div>
                                                                            @error('user.email')
                                                                                <small class="error">
                                                                                    {{ $message }}
                                                                                </small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-12 px-2">
                                                                        <div class="form-group">
                                                                            <label for="whatsappNumber">
                                                                                رقم الهاتف
                                                                            </label>

                                                                            <div class="form-icon">
                                                                                <input type="number" class="form-control"
                                                                                    placeholder="966xxxxxxxxx"
                                                                                    id="user[phone_number]"
                                                                                    name="user[phone]" type="text"
                                                                                    value="{{ old('user.phone') }}" />
                                                                            </div>
                                                                            @error('user.phone')
                                                                                <small class="error">
                                                                                    {{ $message }}
                                                                                </small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="password">
                                                                                كلمة المرور
                                                                            </label>

                                                                            <div class="show_hide_password">
                                                                                <input type="password"
                                                                                    class="form-control"
                                                                                    placeholder="كلمة المرور"
                                                                                    id="user[password]"
                                                                                    name="user[password]" type="password"
                                                                                    value="{{ old('user.password') }}" />

                                                                                <div class="show-pass">
                                                                                    <img src="{{ asset('frontend/assets/images/icons/password.svg') }}"
                                                                                        class="icon" loading="lazy"
                                                                                        alt="" />
                                                                                    <img src="{{ asset('frontend/assets/images/icons/show-pass.svg') }}"
                                                                                        class="slash-icon" loading="lazy"
                                                                                        alt="" />
                                                                                </div>
                                                                            </div>
                                                                            @error('user.password')
                                                                                <small class="error">
                                                                                    {{ $message }}
                                                                                </small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="passwordAgain">
                                                                                تأكيد كلمة المرور
                                                                            </label>

                                                                            <div class="show_hide_password">
                                                                                <input type="password"
                                                                                    class="form-control"
                                                                                    placeholder="تأكيد كلمة المرور"
                                                                                    id="user[password_confirmation]"
                                                                                    name="user[password_confirmation]"
                                                                                    value="{{ old('user.password_confirmation') }}" />

                                                                                <div class="show-pass">
                                                                                    <img src="{{ asset('frontend/assets/images/icons/password.svg') }}"
                                                                                        class="icon" loading="lazy"
                                                                                        alt="" />
                                                                                    <img src="{{ asset('frontend/assets/images/icons/show-pass.svg') }}"
                                                                                        class="slash-icon" loading="lazy"
                                                                                        alt="" />
                                                                                </div>
                                                                            </div>
                                                                            @error('user.password_confirmation')
                                                                                <small class="error">
                                                                                    {{ $message }}
                                                                                </small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <a href="#"
                                                                    class="custom-btn primary-btn next-step">
                                                                    <span> متابعة </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade" id="choosePackage" role="tabpanel"
                                                aria-labelledby="choosePackage-tab">
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
                                                                            <input type="text" class="form-control"
                                                                                placeholder="اسم النشاط" id="name"
                                                                                id="vendor[name]" name="vendor[name]"
                                                                                value="{{ old('vendor.name') }}" />
                                                                        </div>
                                                                        @error('vendor.name')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12">
                                                                    <div class="form-group">
                                                                        <label for="Birthdate">
                                                                            المجال
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <select class="form-control"
                                                                                data-error="Please enter your field"
                                                                                name="vendor[type]" id="vendor[type]">
                                                                                @foreach ($vendor_types as $vendor_type)
                                                                                    <option
                                                                                        value="{{ $vendor_type['id'] }}">
                                                                                        {{ $vendor_type['name'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @error('vendor.type')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="email">
                                                                            البريد الالكتروني للتواصل
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <input type="email" class="form-control"
                                                                                placeholder="البريد الالكتروني للتواصل"
                                                                                id="vendor[email]" name="vendor[email]"
                                                                                value="{{ old('vendor.email') }}" />
                                                                        </div>
                                                                        @error('vendor.email')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            رقم الهاتف للتواصل
                                                                        </label>

                                                                        <div class="form-icon ">
                                                                            <input type="number"
                                                                                placeholder="رقم الهاتف للتواصل"
                                                                                class="form-control" id="vendor[phone]"
                                                                                name="vendor[phone]"
                                                                                value="{{ old('vendor.phone') }}" />
                                                                        </div>

                                                                        @error('vendor.phone')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-12">
                                                                    <div class="form-group">
                                                                        <label for="password">
                                                                            وصف النشاط
                                                                        </label>

                                                                        <div class="form-icon text-area">
                                                                            <textarea class="form-control" placeholder="وصف النشاط" id="vendor[about]" name="vendor[about]" type="text">{{ old('vendor.about') }}</textarea>
                                                                        </div>
                                                                        @error('vendor.about')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>


                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            الرقم الضريبي
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <input type="number"
                                                                                placeholder="الرقم الضريبي"
                                                                                class="form-control"
                                                                                id="vendor[tax_number]"
                                                                                name="vendor[tax_number]"
                                                                                value="{{ old('vendor.tax_number') }}">
                                                                        </div>
                                                                        @error('vendor.tax_number')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>


                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            رقم التصنيف
                                                                        </label>

                                                                        <div class="form-icon ">
                                                                            <input type="number"
                                                                                placeholder="رقم التصنيف"
                                                                                class="form-control"
                                                                                id="vendor[cat_number]"
                                                                                name="vendor[cat_number]" type="text"
                                                                                value="{{ old('vendor.cat_number') }}" />
                                                                        </div>
                                                                        @error('vendor.cat_number')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            رقم السجل التجاري
                                                                        </label>
                                                                        <div class="form-icon ">
                                                                            <input type="number"
                                                                                placeholder="رقم السجل التجاري"
                                                                                class="form-control"
                                                                                name="vendor[reg_number]" type="text"
                                                                                value="{{ old('vendor.reg_number') }}" />
                                                                        </div>

                                                                        @error('vendor.req_number')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            تصريح الصحة
                                                                        </label>
                                                                        <div class="form-icon ">
                                                                            <input type="number"
                                                                                placeholder="تصريح الصحة"
                                                                                class="form-control"
                                                                                name="vendor[health_declaration]"
                                                                                value="{{ old('vendor.health_declaration') }}" />
                                                                        </div>
                                                                        @error('vendor.health_declaration')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="form-group file-input-group">
                                                                        <div class="file__input" id="file__input_worker">
                                                                            <input type="file" name="vendor[logo]"
                                                                                value="{{ old('vendor.logo') }}
                                                                            class="form-control
                                                                                file__input--file_worker"
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
                                                                            @error('vendor.known_url')
                                                                                <span class="error" id="worker_resume_error">
                                                                                    {{ $message }}
                                                                                </span>
                                                                            @enderror

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="button-contain">
                                                                <a href="#"
                                                                    class="custom-btn secondary-btn prev-step">

                                                                    <span> تراجع </span>
                                                                </a>

                                                                <a href="#"
                                                                    class="custom-btn primary-btn next-step">

                                                                    <span> متابعة </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="payment" role="tabpanel"
                                                aria-labelledby="payment-tab">
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
                                                                            <input type="number" class="form-control"
                                                                                placeholder="واتساب" id="vendor[whatsapp]"
                                                                                name="vendor[whatsapp]"
                                                                                value="{{ old('vendor.whatsapp') }}" />
                                                                        </div>

                                                                        @error('vendor.whatsapp')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12">
                                                                    <div class="form-group">
                                                                        <label for="Birthdate">
                                                                            تويتر
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="تويتر" name="vendor[twitter]"
                                                                                value="{{ old('vendor.twitter') }}" />
                                                                        </div>

                                                                        @error('vendor.twitter')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="instagram">
                                                                            انستجرام
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="انستجرام"
                                                                                name="vendor[instagram]"
                                                                                value="{{ old('vendor.instagram') }}" />
                                                                        </div>

                                                                        @error('vendor.instagram')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="snapchat">
                                                                            سناب شات
                                                                        </label>

                                                                        <div class="form-icon ">
                                                                            <input type="text" placeholder="سناب شات"
                                                                                class="form-control"
                                                                                name="vendor[snapchat]"
                                                                                value="{{ old('vendor.snapchat') }}" />
                                                                        </div>

                                                                        @error('vendor.snapchat')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            موقع الكتروني
                                                                        </label>

                                                                        <div class="form-icon ">
                                                                            <input type="text"
                                                                                placeholder="موقع الكتروني"
                                                                                class="form-control"
                                                                                id="vendor[website_url]"
                                                                                name="vendor[website_url]"
                                                                                value="{{ old('vendor.website_url') }}" />
                                                                        </div>

                                                                        @error('vendor.website_url')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror

                                                                    </div>
                                                                </div>


                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            رابط معروف
                                                                        </label>

                                                                        <div class="form-icon ">
                                                                            <input type="text" placeholder="رابط معروف"
                                                                                class="form-control"
                                                                                id="vendor[known_url]"
                                                                                name="vendor[known_url]"
                                                                                value="{{ old('vendor.known_url') }}" />
                                                                        </div>
                                                                        @error('vendor.known_url')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="button-contain">
                                                                <a href="#"
                                                                    class="custom-btn secondary-btn prev-step">

                                                                    <span> تراجع </span>
                                                                </a>

                                                                <a href="#"
                                                                    class="custom-btn primary-btn next-step">

                                                                    <span> متابعة </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="allDone" role="tabpanel"
                                                aria-labelledby="allDone-tab">
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
                                                                            <select class="form-control"
                                                                                name="vendor[address][type]"
                                                                                id="vendor[address][type]">
                                                                                @foreach ($address_types as $address_type)
                                                                                    <option
                                                                                        value="{{ $address_type['id'] }}">
                                                                                        {{ $address_type['name'] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        @error('vendor.address.type')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12">
                                                                    <div class="form-group">
                                                                        <label for="Birthdate">
                                                                            المدينة
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <select class="form-control"
                                                                                data-error="Please enter your city"
                                                                                name="vendor[address][city_id]"
                                                                                id="vendor[address][city_id]">
                                                                                @foreach ($cities as $city)
                                                                                    <option value="{{ $city['id'] }}">
                                                                                        {{ $city['name'] }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        @error('vendor.address.city_id')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="address 1">
                                                                            العنوان 1
                                                                        </label>

                                                                        <div class="form-icon">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="العنوان 1"
                                                                                id="vendor[address][address_1]"
                                                                                name="vendor[address][address_1]"
                                                                                value="{{ old('vendor.address.address_1') }}" />
                                                                        </div>
                                                                        @error('vendor.address.address_1')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-12 px-2">
                                                                    <div class="form-group">
                                                                        <label for="whatsappNumber">
                                                                            العنوان 2
                                                                        </label>

                                                                        <div class="form-icon ">
                                                                            <input type="text" placeholder="العنوان 2"
                                                                                class="form-control"
                                                                                id="vendor[address][address_2]"
                                                                                name="vendor[address][address_2]"
                                                                                value="{{ old('vendor.address.address_2') }}" />
                                                                        </div>
                                                                        @error('vendor.address.address_2')
                                                                            <small class="error">
                                                                                {{ $message }}
                                                                            </small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="button-contain">
                                                                <a href="#"
                                                                    class="custom-btn secondary-btn prev-step">

                                                                    <span> تراجع </span>
                                                                </a>

                                                                <a href="javascript:$('#register-form').submit();"
                                                                    class="custom-btn primary-btn next-step">
                                                                    <span> تسجيل </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6 col-12 px-0">
                        <div class="register-contain">
                            <div class="image-contain">
                                <img src="{{ asset('frontend/assets/images/intro/intro.svg') }}" loading="lazy"
                                    alt="">
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
