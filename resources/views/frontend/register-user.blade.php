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
                                <div class="form-icon phone-input-wrapper">
                                    <img src="{{ asset('interest/assets/images/forms/phone.svg') }}" loading="lazy"
                                        class="icon" />
                                    <div class="phone-prefix">966</div>
                                    <input type="number" name="phone_without_prefix" id="phone_without_prefix" class="form-control phone-with-prefix" 
                                        placeholder="5xxxxxxxx" value="{{ old('phone_without_prefix') }}" />
                                    <input type="hidden" name="phone" id="full_phone" value="{{ old('phone') }}" />
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

                            <a href="javascript:validateAndSubmit();" class="custom-btn primary-btn">
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

<style>
    .phone-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .phone-prefix {
        position: absolute;
        left: 40px;
        font-size: 14px;
        color: #666;
        z-index: 2;
        padding-right: 8px;
        border-right: 1px solid #ddd;
    }

    .phone-with-prefix {
        padding-left: 70px !important;
    }

    .error {
        font-size: 12px !important;
        margin-top: 5px;
        padding-right: 10px;
        display: inline-block;
    }
    
    .custom-btn.primary-btn {
        background: linear-gradient(90deg, #FF8A00, #FF5C5C);
        border: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone_without_prefix');
        const fullPhoneInput = document.getElementById('full_phone');
        
        // Update hidden full phone field when user types
        phoneInput.addEventListener('input', function() {
            fullPhoneInput.value = '966' + this.value;
        });
        
        // Initialize with any existing value
        if (phoneInput.value) {
            fullPhoneInput.value = '966' + phoneInput.value;
        }
    });
    
    function validateAndSubmit() {
        const phoneInput = document.getElementById('phone_without_prefix');
        let isValid = true;
        
        // Clear any existing error message
        let existingError = document.querySelector('.phone-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Validate phone number format (Saudi format)
        const phoneValue = phoneInput.value;
        const normalized = phoneValue.startsWith('0') ? phoneValue : '0' + phoneValue;
        const pattern = /^05[0-9]{8}$/;
        
        if (!pattern.test(normalized)) {
            isValid = false;
            const errorMsg = document.createElement('small');
            errorMsg.className = 'error phone-error';
            errorMsg.textContent = 'يرجى إدخال رقم جوال سعودي صحيح (5xxxxxxxx)';
            
            phoneInput.parentElement.parentElement.appendChild(errorMsg);
        }
        
        if (isValid) {
            document.querySelector('.register-form').submit();
        }
    }
</script>

@endsection