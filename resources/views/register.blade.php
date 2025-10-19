<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قوابا </title>
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/slick.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">
    @include('vendor.nova.partials.meta')
    <style>
        .with-errors, label>span {
            color: #FF0000;
        }
        .nav-link{
            background-color: rgba(255, 213, 211, 1);
        }
        .nav-tabs {
            margin-bottom: 3%;
        }
        .alert-danger {
            text-align: initial;
        }
        .alert-notification {
            -webkit-animation: seconds 1.0s forwards;
            -webkit-animation-iteration-count: 1;
            -webkit-animation-delay: 5s;
            animation: seconds 1.0s forwards;
            animation-iteration-count: 1;
            animation-delay: 3s;
            position: relative;
            text-align: center;
        }
        @-webkit-keyframes seconds {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                left: -9999px;
                position: absolute;
            }
        }
        @keyframes seconds {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                left: -9999px;
                position: absolute;
            }
        }
    </style>
</head>
<body>

<section dir="rtl" id="services">
    <div class="container">
        <div class="row">
            <div class="col-12">

                @if ($message = Session::get('success'))
                    <div class="alert-notification">
                        <div class="alert alert-success alert-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    </div>
                @endif

                <div class="service-motto text-center">
                    <h2 class="wow slideInDown" data-wow-delay="1s">تسجيل كمزود خدمات</h2>
                    <div class="text-service">
                        <p class=" wow slideInDown" data-wow-delay="1s">
                            تسجيل حساب كمزود خدمات يمكنك من الدخول الي حسابك من الهاتف او من الويب
                        </p>
                    </div>

                </div>
            </div>

            <div class="services-show">
                <div class="container">
                    <ul class="nav nav-tabs">
                        <li class="m-3 nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab1">البيانات الاساسية</a>
                        </li>
                        <li class="m-3 nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab2">بيانات النشاط</a>
                        </li>
                        <li class="m-3 nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab3">سوشيال ميديا</a>
                        </li>
                        <li class="m-3 nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab4">العناوين</a>
                        </li>
                    </ul>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="tab-content text-right">
                            <div id="tab1" class="tab-pane fade show active">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>الاسم الاول<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your name" id="user[firstname]" name="user[firstname]" type="text" value="{{ old('user.firstname') }}">
                                            @error('user.firstname')
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>الاسم الاخير<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your name" id="user[lastname]" name="user[lastname]" type="text" value="{{ old('user.lastname') }}">
                                            @error('user.lastname')
                                                <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>البريد الاكتروني<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your email" id="user[email]" name="user[email]" type="email" value="{{ old('user.email') }}">
                                            @error('user.email')
                                                <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>رقم الهاتف<span>*</span></label>
                                            <input class="form-control" placeholder="966xxxxxxxxx" data-error="Please enter your phone number" id="user[phone_number]" name="user[phone]" type="text" value="{{ old('user.phone') }}">
                                            @error('user.phone')
                                                <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>الرقم السري<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your password" id="user[password]" name="user[password]" type="password" value="{{ old('user.password') }}">
                                            @error('user.password')
                                                <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>تأكيد الرقم السري<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your password confirmation" id="user[password_confirmation]" name="user[password_confirmation]" type="password" value="{{ old('user.password_confirmation') }}">
                                            @error('user.password_confirmation')
                                                <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tab2" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>اسم النشاط<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your activity name" id="vendor[name]" name="vendor[name]" type="text" value="{{ old('vendor.name') }}">
                                            @error("vendor.name")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>المجال<span>*</span></label>
                                            <select class="form-control" data-error="Please enter your field" name="vendor[type]" id="vendor[type]">
                                                @foreach($vendor_types as $vendor_type)
                                                    <option value="{{ $vendor_type['id'] }}">{{ $vendor_type['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error("vendor.type")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>البريد الاكتروني للتواصل<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your email" id="vendor[email]" name="vendor[email]" type="email" value="{{ old('vendor.email') }}">
                                            @error("vendor.email")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>رقم الهاتف للتواصل<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your phone" id="vendor[phone]" name="vendor[phone]" type="text" value="{{ old('vendor.phone') }}">
                                            @error("vendor.phone")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>وصف النشاط</label>
                                            <textarea class="form-control" data-error="Please enter your description" id="vendor[about]" name="vendor[about]" type="text">{{ old('vendor.about') }}</textarea>
                                            @error("vendor.about")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>رقم ضريبي</label>
                                            <input class="form-control" data-error="Please enter your tax number" id="vendor[tax_number]" name="vendor[tax_number]" type="text" value="{{ old('vendor.tax_number') }}">
                                            @error("vendor.tax_number")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>رقم التصنيف</label>
                                            <input class="form-control" data-error="Please enter your cat number" id="vendor[cat_number]" name="vendor[cat_number]" type="text" value="{{ old('vendor.cat_number') }}">
                                            @error("vendor.cat_number")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>رقم السجل التجاري</label>
                                            <input class="form-control" data-error="Please enter your reg number" id="vendor[reg_number]" name="vendor[reg_number]" type="text" value="{{ old('vendor.reg_number') }}">
                                            @error("vendor.reg_number")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>شعار</label>
                                            <input class="form-control" data-error="Please enter your activity name" id="vendor[logo]" name="vendor[logo]" type="file" value="{{ old('vendor.logo') }}">
                                            @error("vendor.logo")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tab3" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>واتساب</label>
                                            <input class="form-control" data-error="Please enter your whatsapp" id="vendor[whatsapp]" name="vendor[whatsapp]" type="text" value="{{ old('vendor.whatsapp') }}">
                                            @error("vendor.whatsapp")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>تويتر</label>
                                            <input class="form-control" data-error="Please enter your twitter" id="vendor[twitter]" name="vendor[twitter]" type="text" value="{{ old('vendor.twitter') }}">
                                            @error("vendor.twitter")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>انستجرام</label>
                                            <input class="form-control" data-error="Please enter your instagram" id="vendor[instagram]" name="vendor[instagram]" type="text" value="{{ old('vendor.instagram') }}">
                                            @error("vendor.instagram")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>سناب شات</label>
                                            <input class="form-control" data-error="Please enter your snapchat" id="vendor[snapchat]" name="vendor[snapchat]" type="text" value="{{ old('vendor.snapchat') }}">
                                            @error("vendor.snapchat")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>موقع الكتروني</label>
                                            <input class="form-control" data-error="Please enter your website url" id="vendor[website_url]" name="vendor[website_url]" type="text" value="{{ old('vendor.website_url') }}">
                                            @error("vendor.website_url")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>رابط معروف</label>
                                            <input class="form-control" data-error="Please enter your known_url" id="vendor[known_url]" name="vendor[known_url]" type="text" value="{{ old('vendor.known_url') }}">
                                            @error("vendor.known_url")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tab4" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>نوع العنوان<span>*</span></label>
                                            <select class="form-control" data-error="Please enter your address type" name="vendor[address][type]" id="vendor[address][type]">
                                                @foreach($address_types as $address_type)
                                                    <option value="{{ $address_type['id'] }}">{{ $address_type['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error("vendor.address.type")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>المدينه<span>*</span></label>
                                            <select class="form-control" data-error="Please enter your city" name="vendor[address][city_id]" id="vendor[address][city_id]">
                                                @foreach($cities as $city)
                                                    <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error("vendor.address.city_id")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>العنوان 1<span>*</span></label>
                                            <input class="form-control" data-error="Please enter your address_1" id="vendor[address][address_1]" name="vendor[address][address_1]" type="text" value="{{ old("vendor.address.address_1") }}">
                                            @error("vendor.address.address_1")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>العنوان 2</label>
                                            <input class="form-control" data-error="Please enter your address_2" id="vendor[address][address_2]" name="vendor[address][address_2]" type="text" value="{{ old("vendor.address.address_2") }}">
                                            @error("vendor.address.address_2")
                                            <div class="help-block with-errors">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float">تسجيل</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- services -->

<!-- benefits-->
<section id="recommend">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-md-6 col-sm-12">
                <div class="img-recommend">
                    <img src="{{ asset('landing/img/Refer_and_Earn.png') }}" alt="Refer_and_earn">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="text-recommend">
                    <h2 class=" wow slideInDown" data-wow-delay="1s">حملو التطبيق وشاركوه مع اصحابكم </h2>
                    <p class=" wow slideInDown" data-wow-delay="1s">
                    </p>
                    <p class=" wow slideInDown" data-wow-delay="1s">حمل التطبيق الأن </p>

                    <h4> للمستخدمين</h4>
                    <div class="downloads-app d-flex">

                        <a href="https://apps.apple.com/sa/app/%D9%82%D9%88%D8%A7%D8%A8%D8%A7-%D8%B9%D8%A7%D9%84%D9%85-%D8%A7%D9%84%D8%AC%D9%85%D8%A7%D9%84/id1552554758?l=ar"
                           class="m-3">
                            <img src="{{ asset('landing/img/Download_on_the_App_Store.png') }}" class="apple"
                                 alt="Apple">
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.guapa.app" class="m-3">
                            <img src="{{ asset('landing/img/google-play.png') }}" class="google-play" alt="google-play">
                        </a>
                    </div>

                    <h4> لمزودي الخدمات</h4>

                    <div class="downloads-app d-flex">

                        <a href="https://apps.apple.com/us/app/%D9%82%D9%88%D8%A7%D8%A8%D8%A7-%D9%85%D9%82%D8%AF%D9%85%D9%8A-%D8%A7%D9%84%D8%AE%D8%AF%D9%85%D8%A7%D8%AA/id1549047437"
                           class="m-3">
                            <img src="{{ asset('landing/img/Download_on_the_App_Store.png') }}" class="apple"
                                 alt="Apple">
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.app.guapa_provider" class="m-3">
                            <img src="{{ asset('landing/img/google-play.png') }}" class="google-play" alt="google-play">
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <!--         row -->
    </div>
    <!--    container-->
</section>

<!--testimonials-->
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">

                <div class="social-media d-flex">
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape_3.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape_2.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('landing/img/Combined_Shape_1.png') }}" alt=""></a>
                </div>


                <!--                socical-->
            </div>
            <!--            col-->
            <div class="col-md-4">
                <p>
                    966 53 1434 3889

                    <i class="fa fa-mobile-alt"></i>
                </p>
            </div>
            <!--            col -->
            <div class="col-md-4">
                <p>
                    info@guapa.com.sa
                    <i class="fa fa-envelope-square"></i>
                </p>
            </div>
            <!--            col -->
        </div>
    </div>
</footer>
<script type="text/javascript" src="{{ asset('landing/js/jquery-3.0.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('landing/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('landing/js/slick.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('landing/js/app.js') }}"></script>


</body>
</html>
