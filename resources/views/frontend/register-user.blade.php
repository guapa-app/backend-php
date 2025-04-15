@extends('frontend.layouts.interest')

@section('title')
    {{ ucfirst(__('register')) }}
@endsection

@section('content')

    <section class="subscribe">
        <div class="container">
            <div class="contain-data">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="contain">
                            <h1>guapa</h1>

                            @if ($message = Session::get('success'))
                                <div class="alert-notification">
                                    <div class="alert alert-success alert-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                </div>
                            @endif

                            <p>
                                مهتمة بالتجميل وتبحثين عن عروض، إجراءات، منتجات أو معلومات
                                تجميلية مناسبة لك يمكنك من
                            </p>

                            <ul class="list">
                                <li>مشاركة تجاربك التجميلية</li>
                                <li>خصومات إضافية</li>
                                <li>جمع النقاط</li>
                            </ul>

                            <form class="form-contain register-form" method="POST" action="{{ route('registerInterest') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="form-icon">
                                        <img src="{{ asset('interest/assets/images/forms/user.svg') }}" loading="lazy"
                                            class="icon" />
                                        <input type="text" name="firstname" class="form-control" placeholder="الاسم الاول"
                                            value="{{ old('firstname') }}" />
                                    </div>

                                    @error('firstname')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-icon">
                                        <img src="{{ asset('interest/assets/images/forms/user.svg') }}" loading="lazy"
                                            class="icon" />
                                        <input type="text" name="lastname" class="form-control" placeholder="الاسم الاخير"
                                            value="{{ old('lastname') }}" />
                                    </div>

                                    @error('lastname')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-icon">
                                        <img src="{{ asset('interest/assets/images/forms/sms.svg') }}" loading="lazy"
                                            class="icon" />
                                        <input type="text" name="email" class="form-control" placeholder="البريد الالكتروني"
                                            value="{{ old('email') }}" />
                                    </div>

                                    @error('email')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-icon phone-input-group">
                                        <input type="number" name="phone_number" class="form-control phone-number"
                                            placeholder="xxxxxxxxx" value="{{ old('phone_number') }}" />
                                        <span class="country-code">966</span>
                                        <input type="hidden" name="phone" id="full_phone" value="{{ old('phone') }}">
                                    </div>

                                    @error('phone')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="wrapper">
                                    <input type="checkbox" name="terms" id="terms" class="radio-check" />
                                    <label class="radio-title" for="terms">
                                        بالتسجيل، أنتِ توافقين على
                                        <a href="#" class="link">
                                            الشروط والأحكام و سياسة الخصوصية
                                        </a>
                                    </label>
                                </div>

                                <div>
                                    @error('terms')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <a href="javascript:$('.register-form').submit();" class="custom-btn primary-btn">
                                    <span> تسجيل </span>
                                </a>
                            </form>

                            <div class="button-contain">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn"
                                    target="_blank">
                                    <img src="{{ asset('interest/assets/images/info/app_store.svg') }}" loading="lazy" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1"
                                    class="download-btn" target="_blank">
                                    <img src="{{ asset('interest/assets/images/info/play_store.svg') }}" loading="lazy" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="image-contain">
                            <img src="{{ asset('interest/assets/images/subscribe-intro.png') }}" loading="lazy" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.querySelector('input[name="phone_number"]');
            const fullPhoneInput = document.querySelector('#full_phone');

            phoneInput.addEventListener('input', function () {
                const phoneNumber = phoneInput.value;
                fullPhoneInput.value = '966' + phoneNumber;
            });
        });
    </script>

    <style>
        .phone-input-group {
            display: flex;
            align-items: center;
            position: relative;
            direction: rtl; 
        }

        .country-code {
            background: #f0f0f0;
            padding: 10px;
            border: 1px solid #ccc;
            border-left: none;
            border-radius: 0 5px 5px 0;
            font-size: 16px;
            color: #333;
        }

        .phone-number {
            border-radius: 5px 0 0 5px;
            border: 1px solid #ccc;
            border-left: none;
            flex: 1;
            padding-right: 10px;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
@endsection