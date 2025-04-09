<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-11502298872">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11502298872');
</script>

@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('welcome')) }}
@endsection
@section('content')
    <main>
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="contain">
                            <h1>
                                قوابا- -  عالم الجمال
                            </h1>

                            <p>
                                قوابا منصة تهدف الي تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على افضل العروض الخاصة
                                بالاجراءات التجميلة الجراحية وغير الجراحية، كما توفر منصة قوابا مركزا لطلب مختلف المنتجات
                                والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة ايضا مساحة لمشاركة الخبرات والتجارب سواء
                                من المستخدمين او مزودي الخدمات المختلفة كوسيلة ربط هي الاولى من نوعها في العالم العربي.
                            </p>

                            <div class="mobile-button">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                        alt="" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                        alt="" />
                                </a>
                            </div>

                            <div class="flex-data">
                                <span>
                                    تقييم
                                    4.5 out of 5
                                </span>

                                <img src="{{ asset('frontend/assets/images/icons/stars.svg') }}" loading="lazy"
                                    alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mb-3">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/intro/intro.svg') }}" loading="lazy"
                                alt="" />
                        </div>
                    </div>
                </div>
            </div>

            <a href="#features" class="down-arrow">
                <img src="{{ asset('frontend/assets/images/icons/down_arrow.svg') }}" loading="lazy" alt="" />
            </a>
        </header>

        <section class="offers general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>عروض قوابا</h1>

                    <p>
                        انها منصة تهدف الى تمكين وصول المهتمين بعالم الجمال والإطلاع على
                        اخر العروض الخاصة بعالم الجمال سواء كانت اجراءات او منتجات
                    </p>
                </div>

                <div class="swiper swiper-offers">
                    <div class="swiper-wrapper offer-wrapper">
                        @foreach($products as $product)
                            <div class="swiper-slide">
                                    <div class="offer-box">
                                        <div class="contain">
                                            <div class="company-name">
                                                <div class="data">
                                                    <a href="{{$product->shared_link}}" >
                                                        <img
                                                            src="{{$product->vendor?->photo?->getUrl()}}"
                                                            loading="lazy"
                                                            alt=""
                                                        />
                                                    </a>
                                                    <a href="{{$product->shared_link}}" ><h2>{{$product->vendor?->name}} </h2></a>
                                                </div>
                                                <ul class="list">
                                                    <li>
                                                        <h6 style="font-weight: 700;">{{$product->title}} </h6>
                                                    </li>
                                                </ul>
                                                <ul class="list">
                                                    <li>
                                                        <img
                                                            src="{{ asset('frontend/assets/images/offers/location.svg') }}"
                                                            loading="lazy"
                                                            alt=""
                                                        />

                                                        <span> {{$product->address}} </span>
                                                    </li>

                                                    <li>
                                                        <img
                                                            src="{{ asset('frontend/assets/images/offers/money-recive.svg') }}"
                                                            loading="lazy"
                                                            alt=""
                                                        />

                                                        <span> {{$product->calcProductPoints()}} </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <a href="{{$product->shared_link}}" class="fav">
                                                <img
                                                    src="{{ asset('frontend/assets/images/offers/heart.svg') }}"
                                                    loading="lazy"
                                                    alt=""
                                                />
                                            </a>
                                        </div>

                                        <div class="image-contain">
                                            <div class="swiper swiper-header">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide">
                                                        <a href="{{$product->shared_link}}" >
                                                            <img src="{{$product->offer->image?->getUrl()}}"
                                                                loading="lazy"
                                                                alt=""
                                                            />
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="swiper-pagination"></div>
                                            </div>
                                        </div>

                                        <div class="price-contain">
                                            <div class="data">
                                                <div class="price-list">
                                                    <p class="after"> {{ $product->offer_price }}ر.س</p>

                                                    <p class="before">{{ number_format($product->price, 0) }} ر.س</p>
                                                </div>

                                                <span> لا يشمل ضريبة القيمة المضافة </span>
                                            </div>

                                            <div class="discount">-{{$product->offer->discount_string}}</div>
                                        </div>
                                        @php($difference = \Carbon\Carbon::parse($product->offer->expires_at)->diff(now()))
                                        <div class="offer-time">
                                            <p>مده العرض</p>

                                            <div
                                                class="countdown-card"
                                                data-days="{{$difference->days}}"
                                                data-hours="{{$difference->h}}"
                                                data-minutes="{{$difference->i}}"
                                                data-seconds="{{$difference->s}}"
                                            >
                                                <div class="box">
                                                    <span class="time days">{{$difference->days}}</span>
                                                    <span class="name">أيام</span>
                                                </div>

                                                <div class="box">
                                                    <span class="time hours">{{$difference->h}}</span>
                                                    <span class="name">ساعة</span>
                                                </div>

                                                <div class="box">
                                                    <span class="time minutes">{{$difference->i}}</span>
                                                    <span class="name">دقيقة</span>
                                                </div>

                                                <div class="box">
                                                    <span class="time seconds">{{$difference->s}}</span>
                                                    <span class="name">ثانية</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>

        <section class="partners general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>شركائنا بالنجاح</h1>

                    <p>
                        شركاؤنا في النجاح هم الأفراد والجهات التي تعمل معنا بشكل مستمر
                        ومتواصل لتحقيق الأهداف والرؤية المشتركة. يمثلون جزءًا لا يتجزأ من
                        فريقنا، ويساهمون بشكل كبير.
                    </p>
                </div>

                <div class="swiper swiper-partners">
                    <div class="swiper-wrapper offer-wrapper">
                        @foreach($vendors as $vendor)
                            <div class="swiper-slide">
                                <div class="box">
                                    <a href="{{$vendor->shared_link}}">
                                        <img src="{{$vendor->photo?->getUrl()}}"
                                            loading="lazy" class="partner-logo" alt="logo"
                                        />
                                    </a>
                                    <div class="data">
                                        <a href="{{$vendor->shared_link}}">
                                            <h2>
                                                {{ $vendor->name }}

                                                <img
                                                    src="{{ asset('frontend/assets/images/offers/verified.svg')}}"
                                                    loading="lazy"
                                                    alt=""
                                                />
                                            </h2>
                                        </a>

                                        <ul class="list">
                                            <li>
                                                <img
                                                    src="{{ asset('frontend/assets/images/offers/location.svg')}}"
                                                    loading="lazy"
                                                    alt=""
                                                />

                                                <span> {{ $vendor->address?->address_1 }} </span>
                                            </li>

                                            <li>
                                                <a href="#"> {{__(\App\Models\Vendor::TYPES[$vendor->type],[],'ar')}} </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>

        <section class="all-blogs general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>المدونات الحديثه</h1>

                    <p>
                        انها منصة تهدف الى تمكين وصول المهتمين بعالم الجمال والإطلاع على
                        اخر العروض الخاصة بعالم الجمال سواء كانت اجراءات او منتجات
                    </p>
                </div>

                <div class="swiper swiper-blogs">
                    <div class="swiper-wrapper offer-wrapper">
                        @foreach($posts as $post)
                            <div class="swiper-slide">
                                <div class="new-blog">
                                    <div class="image-contain">
                                        <img src="{{ $post->getFirstMediaUrl('posts', 'large') }}" class="banner-img" loading="lazy" alt=""/>
                                    </div>

                                    <div class="contain">
                                        <div class="flex-data">
                                            <span class="badge"> {{ $post->category?->title }} </span>

                                            <div class="user-data">
                                                <img src="{{ asset('frontend/assets/images/blogs/user.png')}}" loading="lazy"  alt=""/>
                                                <span> {{ $post->admin->name }} </span>
                                            </div>
                                        </div>

                                        <h2>{{ $post->title }}</h2>
                                        <p>{{ Str::limit(strip_tags($post->content, false), 50) }}</p>

                                        <div class="flex-data">
                                            <div class="date">
                                                <img src="{{ asset('frontend/assets/images/offers/calender.svg')}}" loading="lazy" alt=""/>
                                                <span> {{ \Carbon\Carbon::parse($post->created_at)->format('Y-m-d') }}</span>
                                            </div>
                                            <a href="{{route('single-blog',$post->id)}}" class="see-more">
                                                <span> إقرأ المزيد </span>

                                                <img
                                                    src="{{ asset('frontend/assets/images/offers/see-more.svg')}}"
                                                    loading="lazy"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
        <section id="features" class="features general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>
                        كيف يمكنك الاستفادة من قوابا إذا كنت مزود خدمة
                    </h1>

                    <p>
                        انها منصة تهدف الى تمكين وصول المهتمين بعالم الجمال والإطلاع على اخر العروض الخاصة بعالم الجمال سواء
                        كانت اجراءات او منتجات
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-12 mb-3">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/feature/feature_1.svg') }}" loading="lazy"
                                alt="" />

                            <h2>
                                الإستهداف
                            </h2>

                            <p>
                                احصل على المزيد من المرضى المهتمين بعمليات و منتجات التجميل عبر التطبيق. كن على تواصل دائم
                                وزد من امكانية تواصل العديد من المهتمين بالاجراءات او المنتجات التجميلية
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 mb-3">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/feature/feature_2.svg') }}" loading="lazy"
                                alt="" />

                            <h2>
                                إدارة متجرك
                            </h2>

                            <p>
                                اضف منتجاتك و العروض المتاحة على المنتجات و اماكن البيع في دقائق من خلال التطبيق
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 mb-3">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/feature/feature_3.svg') }}" loading="lazy"
                                alt="" />

                            <h2>
                                عروض لعملائك
                            </h2>

                            <p>
                                قم بنشر عروضك و الاستفادة من قاعدة عملاء ضخمةـ وتحويل قاعدة العملاء الي مستهلك دائم لمنتجاتك
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 mb-3">
                        <div class="box">
                            <img src="{{ asset('frontend/assets/images/feature/feature_4.svg') }}" loading="lazy"
                                alt="" />

                            <h2>
                                مجتمع خاص بك
                            </h2>

                            <p>
                                احصل على المزيد من المرضى المهتمين بعمليات و منتجات مجتمع قوابا هو مرجعك الى كل مايخص الجمال
                                تقدر تشارك من خلاله مقاطع وارشادات، شارك المهتمين بعالم الجمال وكن على تواصل معهم وزودهم
                                بالمعلومات الصحيحة وزيد من تقيمك.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="take general-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-3">
                        <div class="image-contain">
                            <img src="{{ asset('frontend/assets/images/take/take_it.svg') }}" loading="lazy"
                                alt="" />
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mb-3">
                        <div class="contain">
                            <ul class="list">
                                <li>
                                    <img src="{{ asset('frontend/assets/images/take/take_1.svg') }}" loading="lazy"
                                        alt="" />

                                    <div class="data">
                                        <h2>
                                            قم بحجز وتأكيد الإجراء أو العرض أو المنتج الخاص بك.
                                        </h2>

                                        <p>قم بحجز وتأكيد الإجراء أو العرض أو المنتج الخاص بك</p>
                                    </div>
                                </li>

                                <li>
                                    <img src="{{ asset('frontend/assets/images/take/take_2.svg') }}" loading="lazy"
                                        alt="" />

                                    <div class="data">
                                        <h2>
                                            سهولة الطلب والتوصيل
                                        </h2>

                                        <p>
                                            تصفح وابحث عن العديد من المنتجات والمستحضرات التجميلية و العروض الخاصة بها مع
                                            امكانية طلبها وتوصيلها عن طريق مزود الخدمة.
                                        </p>
                                    </div>
                                </li>

                                <li>
                                    <img src="{{ asset('frontend/assets/images/take/take_3.svg') }}" loading="lazy"
                                        alt="" />

                                    <div class="data">
                                        <h2>
                                            مجتمع خاص بعالم الجمال
                                        </h2>

                                        <p>
                                            مجتمع قوابا هو مرجعك الى كل مايخص عالم الجمال تقدر تشوف من خلاله مقاطع وارشادات
                                            مزودي الخدمات والمنتجات مع امكانية مشاركة ارائك وتجاربك
                                        </p>
                                    </div>
                                </li>
                            </ul>


                            <a href="{{ route('login') }}" class="custom-btn secondary-btn">
                                <span>
                                    سجل الان
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="info general-section">
            <div class="container">
                <div class="contain">
                    <h1>
                        لنبدأ شيئاً عظيماً
                        <br />
                        حملو التطبيق وشاركوه مع اصحابكم
                    </h1>

                    <ul class="nav nav-pills links mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">
                                للمستخدمين
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">
                                لمزودي الخدمات
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="mobile-button">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                        alt="" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                        alt="" />
                                </a>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="mobile-button">
                                <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                        alt="" />
                                </a>

                                <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1" class="download-btn">
                                    <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                        alt="" />
                                </a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </section>
    </main>
@endsection
