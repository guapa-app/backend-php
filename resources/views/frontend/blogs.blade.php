@extends('frontend.layouts.app')

@php
    app()->setLocale('ar');
@endphp

@section('title')
    {{ ucfirst(__('blog')) }}
@endsection
@section('heads')
    <script type='text/javascript'>
        (function (e, t, n) {
            if (e.snaptr) return; var a = e.snaptr = function () { a.handleRequest ? a.handleRequest.apply(a, arguments) : a.queue.push(arguments) };
            a.queue = []; var s = 'script'; r = t.createElement(s); r.async = !0;
            r.src = n; var u = t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r, u);
        })(window, document,
            'https://sc-static.net/scevent.min.js');

        snaptr('init', '25f2dedb-b32d-4dca-be95-152a0448e6c9', {});

        snaptr('track', 'PAGE_VIEW');

    </script>
@endsection
@section('content')
    <main>
        <section class="sub-header large-sub-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="info-data">
                            <h1>معلومات طبية</h1>

                            <p>
                                منصة مبتكرة لخدمات الاستشارات الطبية عن بُعد! احصل على رعاية
                                صحية عالية الجودة في أي مكان وزمان. نحن هنا لتسهيل التواصل
                                بينك وبين الأطباء
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('frontend/assets/images/sub-header/sub-header.png') }}" class="sub-header-img" loading="lazy"
                alt="" />
        </section>

        <section class="banner general-section pb-0">
            <div class="container">
                <div class="contain">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="data-contain">
                                @if($posts->count() > 0)
                                                            @php
                                                                $post = $posts->random();
                                                            @endphp
                                                            <p class="badge">{{ $post->category?->title }}</p>

                                                            <h1>{{ $post->title }}</h1>

                                                            <p>{{ Str::limit(strip_tags($post->content, false), 50) }}</p>

                                                            <ul class="list">
                                                                <li>
                                                                    <img src="{{ asset('frontend/assets/images/blogs/user.png')}}" loading="lazy"
                                                                        class="full-radius-img" alt="" />
                                                                    <span> {{ $post->admin->name }} </span>
                                                                </li>

                                                                <li>
                                                                    <img src="{{ asset('frontend/assets/images/sub-header/calendar-white.svg')}}"
                                                                        loading="lazy" alt="" />
                                                                    <span>
                                                                        {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('l j F Y') }}</span>
                                                                </li>
                                                            </ul>
                                @else
                                    <p class="badge">مقالات</p>

                                    <h1>المدونة الطبية</h1>

                                    <p>استكشف مقالاتنا الطبية المميزة للحصول على معلومات صحية موثوقة</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($posts->count() > 0)
                        <img src="{{ $post->getFirstMediaUrl('posts', 'large') }}" class="banner-img" loading="lazy" alt="" />
                    @else
                        <img src="{{ asset('frontend/assets/images/blogs/default-banner.jpg') }}" class="banner-img"
                            loading="lazy" alt="" />
                    @endif
                </div>
            </div>
        </section>

        <section class="blogs general-section border-shape">
            <div class="container">
                <div class="row">
                    <!-- filters -->
                    <div class="col-lg-3 col-12 mb-3">
                        <div class="filter-data">
                            @if($postCategories->count())
                                <h1 class="head">
                                    <img src="{{ asset('frontend/assets/images/sub-header/category.svg')}}" loading="lazy"
                                        alt="" />
                                    فئات
                                </h1>

                                <ul class="list">
                                    <!-- categories -->
                                    <li>
                                        <a href="{{ route('blogs') }}">
                                            <span class="data">الكل</span>
                                        </a>
                                    </li>
                                    @foreach($postCategories as $category)
                                        <li>
                                            <a href="{{ route('blogs', "filter[category_id]=$category->id") }}">
                                                <span class="data">{{ $category->title }}</span>
                                                <span class="count">{{ $category->posts_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @if($postTags->count())
                                <h1 class="head">
                                    <img src="{{ asset('frontend/assets/images/sub-header/stickynote.svg')}}" loading="lazy"
                                        alt="" />
                                    علامات
                                </h1>

                                <ul class="tags">
                                    <!-- tags -->
                                    @foreach($postTags as $tag)
                                        <li>
                                            <a href="{{ route('blogs', "filter[tag_id]=$tag->id") }}">{{ $tag->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>

                        <div class="annoucment">
                            <img src="{{ asset('frontend/assets/images/sub-header/advertisment.png')}}" loading="lazy"
                                alt="">
                        </div>
                    </div>

                    <div class="col-lg-9 col-12 mb-3">
                        <div class="custom-heading text-count">
                            <h1>
                                المقالات
                                <span> ({{ $postsCounter }}) </span>
                            </h1>
                        </div>
                        <div class="row">
                            <!-- blogs -->
                            @foreach ($posts as $post)
                                <div class="col-lg-4 col-6 mb-3 padding-contain">
                                    <div class="box">
                                        <div class="image-contain">
                                            <span class="badge"> {{ $post->category->title }} </span>
                                            @if ($post->getFirstMediaUrl('posts', 'medium'))
                                                <img src="{{ $post->getFirstMediaUrl('posts', 'medium') }}" loading="lazy" alt="" />
                                            @else
                                                <img src="{{ asset('landing-v2/images/blogs/img_1.png') }}" loading="lazy" alt="" />
                                            @endif
                                        </div>

                                        <div class="contain">
                                            <h2>{{ $post->title }}</h2>

                                            <ul class="list">
                                                <li>
                                                    <img src="{{ asset('frontend/assets/images/blogs/user.png')}}"
                                                        loading="lazy" class="full-radius-img" alt="" />
                                                    <span> {{ $post->admin->name }} </span>
                                                </li>

                                                <li>
                                                    <img src="{{ asset('frontend/assets/images/sub-header/calendar.svg')}}"
                                                        loading="lazy" class="light-filter" alt="" />
                                                    <span>
                                                        {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('j F Y') }}</span>
                                                </li>
                                            </ul>

                                            <a href="{{ route('single-blog', $post->id) }}" class="see-more">
                                                <img src="{{ asset('frontend/assets/images/sub-header/arrow-left.svg')}}"
                                                    loading="lazy" alt="" />
                                                <span> اقرأ المزيد </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- pagination -->
                            <div class="col-12">
                                <ul class="pagintain">
                                    @if ($currentPage > 1)
                                        <li><a href="{{ $posts->previousPageUrl() }}">&lt;</a></li>
                                    @endif

                                    @if ($currentPage > 2)
                                        <li><a href="{{ $posts->url(1) }}">1</a></li>
                                        @if ($currentPage > 3)
                                            <li><span>...</span></li>
                                        @endif
                                    @endif

                                    @for ($i = $start; $i <= $end; $i++)
                                        <li>
                                            @if ($i === $currentPage)
                                                <a class="active" href="{{ $posts->url($i) }}">{{ $i }}</a>
                                            @else
                                                <a href="{{ $posts->url($i) }}">{{ $i }}</a>
                                            @endif
                                        </li>
                                    @endfor

                                    @if ($currentPage < $lastPage - 1)
                                        @if ($currentPage < $lastPage - 2)
                                            <li><span>...</span></li>
                                        @endif
                                        <li><a href="{{ $posts->url($lastPage) }}">{{ $lastPage }}</a></li>
                                    @endif

                                    @if ($currentPage < $lastPage)
                                        <li><a href="{{ $posts->nextPageUrl() }}">&gt;</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection