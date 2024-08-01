<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="...." />
    <meta name="author" content="misara adel" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>GUAPA</title>
    <link rel="shortcut icon" href="{{ asset('landing-v2/images/logo/icon.png') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('landing-v2/css/lib/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-v2/css/lib/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-v2/css/lib/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing-v2/css/style.css') }}" />
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="contain">
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
            <a href="{{ url('/') }}" class="brand-name">
                <img src="{{ asset('landing-v2/images/logo/logo.svg') }}" loading="lazy" alt=""/>
            </a>
            <div class="nav-contain">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ url('/about') }}" class="nav-link">ماذا نقدم للمستخدم</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/download-app') }}" class="nav-link">مساعدة مزود خدمة</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/about-app') }}" class="nav-link">التطبيق</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/about') }}" class="nav-link">عن قوابا</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/questions') }}" class="nav-link">الاسئلة الشائعة</a>
                    </li>
                </ul>
                <div class="button-contain">
                    <a href="{{ route('register.form') }}" class="btn-signup">تسجيل كمزود خدمة</a>
                    <a href="{{ route('filament.user.auth.login') }}" class="custom-btn primary-btn">
                        <span>تسجيل الدخول</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<main>
    <section class="sub-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="contain">
                        <ul class="list">
                            <li><a href="{{ url('/') }}">الرئيسية</a></li>
                            <li><span>المدونة</span></li>
                        </ul>
                        <h1>المدونة</h1>
                        <p>لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل. وبعد موافقة العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص النهائية المطلوبة للتصميم.</p>
                    </div>
                </div>
            </div>
            <img src="{{ asset('landing-v2/images/blogs/blog.svg') }}" class="icon" loading="lazy" alt="" />
        </div>
        <img src="{{ asset('landing-v2/images/icons/sub_header.png') }}" class="sub-header-img" loading="lazy" alt="" />
    </section>

    <section class="blogs general-section border-shape">
        <div class="container">
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-lg-4 col-12 mb-3">
                        <div class="box">
                            <div class="image-contain">
{{--                                <img src="{{ $post->getFirstMediaUrl('posts', 'medium') }}" loading="lazy" alt="" />--}}
                                <img src="{{ asset('landing-v2/images/blogs/img_1.png') }}" loading="lazy" alt="" />
                            </div>
                            <div class="contain">
                                <div class="flex-data">
                                    <span class="badge">{{ $post->category->title }}</span>
                                    <div class="user-img">
                                        <img src="{{ asset('landing-v2/images/blogs/user.png') }}" loading="lazy" alt="" />
                                        <span>{{ $post->admin->name }}</span>
                                    </div>
                                </div>
                                <h2>{{ $post->title }}</h2>
                                <p>{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>
                                <div class="flex-data">
                                    <div class="date">
                                        <img src="{{ asset('landing-v2/images/icons/date.svg') }}" loading="lazy" alt="" />
                                        <span>{{ $post->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    <a href="{{ route('blogs.show', $post->id) }}">
                                        <span>إقرأ المزيد</span>
                                        <img src="{{ asset('landing-v2/images/icons/left-arrow.svg') }}" loading="lazy" alt="" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-12">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 mb-4">
                <div class="contain">
                    <a href="#" class="brand-name">
                        <img src="{{ asset('landing-v2/images/logo/logo.svg') }}" loading="lazy" alt="" />
                    </a>
                    <p>سجل باستخدام عنوان بريدك الإلكتروني لتبقى على اطلاع بالخصومات والتحديثات الجديدة من جميع الحملات!</p>
                    <form action="" class="form-icon">
                        <div class="form-group">
                            <img src="{{ asset('landing-v2/images/icons/search.svg') }}" loading="lazy" alt="" />
                            <input type="text" class="form-control" placeholder="ادخل بريدك الالكتروني" />
                            <a href="#" class="custom-btn secondary-btn">
                                <span>سجل الان</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 col-12 mb-4">
                <div class="flex-data">
                    <div class="contain">
                        <h2>روابط مهمة</h2>
                        <ul class="links">
                            <li><a href="{{ url('/download-app') }}"><span>تطبيق مزودي الخدمة</span></a></li>
                            <li><a href="{{ url('/about-app') }}"><span>تطبيق المستخدمين</span></a></li>
                            <li><a href="{{ url('/about') }}"><span>عن قوابا</span></a></li>
                            <li><a href="{{ url('/blogs') }}"><span>المدونة</span></a></li>
                        </ul>
                    </div>
                    <div class="contain">
                        <h2>للتواصل</h2>
                        <ul class="links">
                            <li>
                                <a href="tel:5314343889" class="block">
                                    <span>للتواصل تليفونياً</span>
                                    <span>9665314343889</span>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:info@guapa.com.sa" class="block">
                                    <span>البريد الالكتروني</span>
                                    <span>info@guapa.com.sa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 mb-4">
                <div class="contain padding-contain">
                    <h2>تابعنا</h2>
                    <ul class="links">
                        <li><a href="#"><img src="{{ asset('landing-v2/images/footer/youtube.svg') }}" loading="lazy" alt="" /><span>موقع YouTube</span></a></li>
                        <li><a href="#"><img src="{{ asset('landing-v2/images/footer/x.svg') }}" loading="lazy" alt="" /><span>منصة إكس</span></a></li>
                        <li><a href="#"><img src="{{ asset('landing-v2/images/footer/linked.svg') }}" loading="lazy" alt="" /><span>لينكدين</span></a></li>
                        <li><a href="#"><img src="{{ asset('landing-v2/images/footer/insta.svg') }}" loading="lazy" alt="" /><span>انستغرام</span></a></li>
                        <li><a href="#"><img src="{{ asset('landing-v2/images/footer/facebook.svg') }}" loading="lazy" alt="" /><span>فيسبوك</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyrights">
            <p>© 2024 جوابا. جميع الحقوق محفوظة.</p>
            <ul class="links">
                <li><a href="#">إعدادات ملفات تعريف الارتباط</a></li>
                <li><a href="{{ url('/use-terms') }}">شروط الخدمة</a></li>
                <li><a href="{{ url('/privacy-policy') }}">سياسة الخصوصية</a></li>
            </ul>
        </div>
    </div>
</footer>

<script src={{asset("landing-v2/js/lib/jquery4.js")}}></script>
<script src={{asset("landing-v2/js/lib/popper.js")}}></script>
<script src={{asset("landing-v2/js/lib/bootstrap.js")}}></script>
<script src={{asset("landing-v2/js/lib/swiper-bundle.min.js")}}></script>
<script src={{asset("landing-v2/js/main.js")}}></script>
</body>
</html>
