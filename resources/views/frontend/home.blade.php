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
                        <h1>قوابا - عالم الجمال</h1>
                        <p>
                            قوابا منصة تهدف إلى تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على أفضل العروض الخاصة
                            بالإجراءات التجميلية الجراحية وغير الجراحية، كما توفر منصة قوابا مركزًا لطلب مختلف المنتجات
                            والمستحضرات ذات العلاقة بعالم الجمال، تقدم المنصة أيضًا مساحة لمشاركة الخبرات والتجارب سواء
                            من المستخدمين أو مزودي الخدمات المختلفة كوسيلة ربط هي الأولى من نوعها في العالم العربي.
                        </p>
                        <div class="mobile-button">
                            <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                    alt="تحميل تطبيق قوابا من متجر التطبيقات" />
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1"
                                class="download-btn">
                                <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                    alt="تحميل تطبيق قوابا من جوجل بلاي" />
                            </a>
                        </div>
                        <div class="flex-data">
                            <span>تقييم 4.5 من 5</span>
                            <img src="{{ asset('frontend/assets/images/icons/stars.svg') }}" loading="lazy"
                                alt="تقييم 4.5 نجوم" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12 mb-3">
                    <div class="image-contain">
                        <img src="{{ asset('frontend/assets/images/intro/intro.svg') }}" loading="lazy"
                            alt="صورة تمهيدية لمنصة قوابا - عالم الجمال" />
                    </div>
                </div>
            </div>
        </div>
        <a href="#features" class="down-arrow">
            <img src="{{ asset('frontend/assets/images/icons/down_arrow.svg') }}" loading="lazy"
                alt="سهم للأسفل للانتقال إلى المميزات" />
        </a>
    </header>

    <section class="offers general-section">
        <div class="container">
            <div class="custom-heading">
                <h1>عروض قوابا</h1>
                <p>منصة تهدف إلى تمكين وصول المهتمين بعالم الجمال والاطلاع على أحدث العروض الخاصة بعالم الجمال سواء كانت
                    إجراءات أو منتجات.</p>
            </div>
            <div class="swiper swiper-offers">
                <div class="swiper-wrapper offer-wrapper">
                    @forelse($products as $product)
                    <div class="swiper-slide">
                        <div class="offer-box">
                            <div class="contain">
                                <div class="company-name">
                                    <div class="data">
                                        <a href="{{ $product->shared_link }}">
                                            <img src="{{ $product->vendor?->photo?->getUrl() ?? asset('frontend/assets/images/placeholder.png') }}"
                                                loading="lazy"
                                                alt="شعار {{ $product->vendor?->name ?? 'مزود الخدمة' }}" />
                                        </a>
                                        <a href="{{ $product->shared_link }}">
                                            <h2>{{ $product->vendor?->name ?? 'غير محدد' }}</h2>
                                        </a>
                                    </div>
                                    <ul class="list">
                                        <li>
                                            <h6 style="font-weight: 700;">{{ $product->title }}</h6>
                                        </li>
                                    </ul>
                                    <ul class="list">
                                        <li>
                                            <img src="{{ asset('frontend/assets/images/offers/location.svg') }}"
                                                loading="lazy" alt="أيقونة الموقع" />
                                            <span>{{ $product->address ?? 'غير محدد' }}</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('frontend/assets/images/offers/money-recive.svg') }}"
                                                loading="lazy" alt="أيقونة النقاط" />
                                            <span>{{ $product->calcProductPoints() }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ $product->shared_link }}" class="fav">
                                    <img src="{{ asset('frontend/assets/images/offers/heart.svg') }}" loading="lazy"
                                        alt="إضافة {{ $product->title }} إلى المفضلة" />
                                </a>
                            </div>
                            <div class="image-contain">
                                <div class="swiper swiper-header">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <a href="{{ $product->shared_link }}">
                                                <img src="{{ $product->offer?->image?->getUrl() ?? asset('frontend/assets/images/placeholder.png') }}"
                                                    loading="lazy" alt="صورة عرض {{ $product->title }}" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                            <div class="price-contain">
                                <div class="data">
                                    <div class="price-list">
                                        <p class="after">{{ $product->offer_price }} ر.س</p>
                                        <p class="before">{{ number_format($product->price, 0) }} ر.س</p>
                                    </div>
                                    <span>لا يشمل ضريبة القيمة المضافة</span>
                                </div>
                                <div class="discount">-{{ $product->offer?->discount_string ?? 'غير محدد' }}</div>
                            </div>
                            @php($difference = \Carbon\Carbon::parse($product->offer?->expires_at)->diff(now()))
                            <div class="offer-time">
                                <p>مدة العرض</p>
                                <div class="countdown-card" data-days="{{ $difference->days }}"
                                    data-hours="{{ $difference->h }}" data-minutes="{{ $difference->i }}"
                                    data-seconds="{{ $difference->s }}">
                                    <div class="box">
                                        <span class="time days">{{ $difference->days }}</span>
                                        <span class="name">أيام</span>
                                    </div>
                                    <div class="box">
                                        <span class="time hours">{{ $difference->h }}</span>
                                        <span class="name">ساعة</span>
                                    </div>
                                    <div class="box">
                                        <span class="time minutes">{{ $difference->i }}</span>
                                        <span class="name">دقيقة</span>
                                    </div>
                                    <div class="box">
                                        <span class="time seconds">{{ $difference->s }}</span>
                                        <span class="name">ثانية</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p>لا توجد عروض متاحة حاليًا.</p>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="partners general-section">
        <div class="container">
            <div class="custom-heading">
                <h1>شركائنا بالنجاح</h1>
                <p>شركاؤنا في النجاح هم الأفراد والجهات التي تعمل معنا بشكل مستمر ومتواصل لتحقيق الأهداف والرؤية
                    المشتركة.</p>
            </div>
            <div class="swiper swiper-partners">
                <div class="swiper-wrapper offer-wrapper">
                    @forelse($vendors as $vendor)
                        <div class="swiper-slide">
                            <div class="box">
                                <a href="{{ $vendor->shared_link }}">
                                    <img src="{{ $vendor->photo?->getUrl() ?? asset('frontend/assets/images/placeholder.png') }}"
                                        loading="lazy" class="partner-logo" alt="شعار {{ $vendor->name }}" />
                                </a>
                                <div class="data">
                                    <a href="{{ $vendor->shared_link }}">
                                        <h2>
                                            {{ $vendor->name }}
                                            <img src="{{ asset('frontend/assets/images/offers/verified.svg') }}"
                                                loading="lazy" alt="أيقونة التحقق لـ {{ $vendor->name }}" />
                                        </h2>
                                    </a>
                                    <ul class="list">
                                        <li>
                                            <img src="{{ asset('frontend/assets/images/offers/location.svg') }}"
                                                loading="lazy" alt="أيقونة الموقع" />
                                            <span>{{ $vendor->address?->address_1 ?? 'غير محدد' }}</span>
                                        </li>
                                        <li>
                                            <a
                                                href="#">{{ __(\App\Models\Vendor::TYPES[$vendor->type] ?? 'غير محدد', [], 'ar') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>لا يوجد شركاء متاحون حاليًا.</p>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="all-blogs general-section">
        <div class="container">
            <div class="custom-heading">
                <h1>المدونات الحديثة</h1>
                <p>منصة تهدف إلى تمكين وصول المهتمين بعالم الجمال والاطلاع على أحدث العروض الخاصة بعالم الجمال سواء كانت
                    إجراءات أو منتجات.</p>
            </div>
            <div class="swiper swiper-blogs">
                <div class="swiper-wrapper offer-wrapper">
                    @forelse($posts as $post)
                        <div class="swiper-slide">
                            <div class="new-blog">
                                <div class="image-contain">
                                    <img src="{{ $post->getFirstMediaUrl('posts', 'large') ?: asset('frontend/assets/images/placeholder.png') }}"
                                        class="banner-img" loading="lazy" alt="صورة مدونة {{ $post->title }}" />
                                </div>
                                <div class="contain">
                                    <div class="flex-data">
                                        <span class="badge">{{ $post->category?->title ?? 'غير مصنف' }}</span>
                                        <div class="user-data">
                                            <img src="{{ asset('frontend/assets/images/blogs/user.png') }}" loading="lazy"
                                                alt="صورة المستخدم {{ $post->admin?->name ?? 'غير محدد' }}" />
                                            <span>{{ $post->admin?->name ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <h2>{{ $post->title }}</h2>
                                    <p>{{ Str::limit(strip_tags($post->content ?? '', false), 50) }}</p>
                                    <div class="flex-data">
                                        <div class="date">
                                            <img src="{{ asset('frontend/assets/images/offers/calender.svg') }}"
                                                loading="lazy" alt="أيقونة التقويم" />
                                            <span>{{ \Carbon\Carbon::parse($post->created_at)->format('Y-m-d') }}</span>
                                        </div>
                                        <a href="{{ route('post.show', ['id' => $post->id, 'slug' => \Str::slug($post->title)]) }}"
                                            class="see-more"> <span>إقرأ المزيد</span>
                                            <img src="{{ asset('frontend/assets/images/offers/see-more.svg') }}"
                                                loading="lazy" alt="أيقونة إقرأ المزيد" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>لا توجد مدونات متاحة حاليًا.</p>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section id="features" class="features general-section">
        <div class="container">
            <div class="custom-heading">
                <h1>كيف يمكنك الاستفادة من قوابا إذا كنت مزود خدمة</h1>
                <p>منصة تهدف إلى تمكين وصول المهتمين بعالم الجمال والاطلاع على أحدث العروض الخاصة بعالم الجمال سواء كانت
                    إجراءات أو منتجات.</p>
            </div>
            <div class="row">
                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('frontend/assets/images/feature/feature_1.svg') }}" loading="lazy"
                            alt="أيقونة الاستهداف" />
                        <h2>الإستهداف</h2>
                        <p>احصل على المزيد من المرضى المهتمين بعمليات ومنتجات التجميل عبر التطبيق. كن على تواصل دائم وزد
                            من إمكانية تواصل المهتمين بالإجراءات أو المنتجات التجميلية.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('frontend/assets/images/feature/feature_2.svg') }}" loading="lazy"
                            alt="أيقونة إدارة المتجر" />
                        <h2>إدارة متجرك</h2>
                        <p>أضف منتجاتك والعروض المتاحة على المنتجات وأماكن البيع في دقائق من خلال التطبيق.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('frontend/assets/images/feature/feature_3.svg') }}" loading="lazy"
                            alt="أيقونة عروض العملاء" />
                        <h2>عروض لعملائك</h2>
                        <p>قم بنشر عروضك والاستفادة من قاعدة عملاء ضخمة، وتحويل قاعدة العملاء إلى مستهلك دائم لمنتجاتك.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-12 mb-3">
                    <div class="box">
                        <img src="{{ asset('frontend/assets/images/feature/feature_4.svg') }}" loading="lazy"
                            alt="أيقونة مجتمع قوابا" />
                        <h2>مجتمع خاص بك</h2>
                        <p>احصل على المزيد من المرضى المهتمين بعمليات ومنتجات مجتمع قوابا. شارك مقاطع وإرشادات، وكن على
                            تواصل مع المهتمين بعالم الجمال وزودهم بالمعلومات الصحيحة.</p>
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
                            alt="صورة توضيحية لخدمات قوابا" />
                    </div>
                </div>
                <div class="col-lg-6 col-12 mb-3">
                    <div class="contain">
                        <ul class="list">
                            <li>
                                <img src="{{ asset('frontend/assets/images/take/take_1.svg') }}" loading="lazy"
                                    alt="أيقونة الحجز والتأكيد" />
                                <div class="data">
                                    <h2>قم بحجز وتأكيد الإجراء أو العرض أو المنتج الخاص بك</h2>
                                    <p>قم بحجز وتأكيد الإجراء أو العرض أو المنتج الخاص بك بسهولة.</p>
                                </div>
                            </li>
                            <li>
                                <img src="{{ asset('frontend/assets/images/take/take_2.svg') }}" loading="lazy"
                                    alt="أيقونة الطلب والتوصيل" />
                                <div class="data">
                                    <h2>سهولة الطلب والتوصيل</h2>
                                    <p>تصفح وابحث عن العديد من المنتجات والمستحضرات التجميلية والعروض الخاصة بها مع
                                        إمكانية طلبها وتوصيلها عن طريق مزود الخدمة.</p>
                                </div>
                            </li>
                            <li>
                                <img src="{{ asset('frontend/assets/images/take/take_3.svg') }}" loading="lazy"
                                    alt="أيقونة مجتمع الجمال" />
                                <div class="data">
                                    <h2>مجتمع خاص بعالم الجمال</h2>
                                    <p>مجتمع قوابا هو مرجعك إلى كل ما يخص عالم الجمال. شاهد مقاطع وإرشادات مزودي الخدمات
                                        والمنتجات مع إمكانية مشاركة آرائك وتجاربك.</p>
                                </div>
                            </li>
                        </ul>
                        <a href="{{ route('login') }}" class="custom-btn secondary-btn">
                            <span>سجل الآن</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="info general-section">
        <div class="container">
            <div class="contain">
                <h1>لنبدأ شيئًا عظيمًا<br />حمل التطبيق وشاركه مع أصحابك</h1>
                <ul class="nav nav-pills links mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                            aria-controls="pills-home" aria-selected="true">للمستخدمين</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                            aria-controls="pills-profile" aria-selected="false">لمزودي الخدمات</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <div class="mobile-button">
                            <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                    alt="تحميل تطبيق قوابا للمستخدمين من متجر التطبيقات" />
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1"
                                class="download-btn">
                                <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                    alt="تحميل تطبيق قوابا للمستخدمين من جوجل بلاي" />
                            </a>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="mobile-button">
                            <a href="https://apps.apple.com/sa/app/guapa/id1552554758" class="download-btn">
                                <img src="{{ asset('frontend/assets/images/icons/app-store.svg') }}" loading="lazy"
                                    alt="تحميل تطبيق قوابا لمزودي الخدمات من متجر التطبيقات" />
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.guapanozom.app&pli=1"
                                class="download-btn">
                                <img src="{{ asset('frontend/assets/images/icons/google_play.svg') }}" loading="lazy"
                                    alt="تحميل تطبيق قوابا لمزودي الخدمات من جوجل بلاي" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Organization Schema -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "قوابا",
            "alternateName": "Guapa",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('frontend/assets/images/logo.png') }}",
            "description": "قوابا منصة تهدف إلى تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على أفضل العروض الخاصة بالإجراءات التجميلية الجراحية وغير الجراحية.",
            "sameAs": [
                "https://www.instagram.com/guapa",
                "https://twitter.com/guapa"
            ]
        }
        </script>

    <!-- WebSite Schema -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "url": "{{ url('/') }}",
            "name": "قوابا - عالم الجمال",
            "description": "قوابا منصة تهدف إلى تمكين الوصول لجميع ما يتعلق بعالم الجمال والاطلاع على أفضل العروض الخاصة بالإجراءات التجميلية الجراحية وغير الجراحية.",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ url('/search') }}?q={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
        </script>
</main>
@endsection
