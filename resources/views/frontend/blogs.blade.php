@php use Illuminate\Support\Str; @endphp
@extends('frontend.layouts.app')

@section('title')
    @php
        app()->setLocale('ar')
    @endphp
    {{ ucfirst(__('blogs')) }}
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

            <img
                src="{{ asset('frontend/assets/images/sub-header/sub-header.png') }}"
                class="sub-header-img"
                loading="lazy"
                alt=""
            />
        </section>

        <section class="banner general-section pb-0">
            <div class="container">
                <div class="contain">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="data-contain">
                                <p class="badge">زراعة الشعر</p>

                                <h1>أفضل الأدوية والفيتامينات للشعر المزروع</h1>

                                <p class="desc">
                                    زراعة الشعر هي استثمار في مظهرك وثقتك بنفسك، ولكن هذا
                                    الاستثمار يحتاج إلى عناية مستمرة حتى يحقق أقصى نتائجه. ومن
                                    أهم العوامل التي تساهم في نجاح عملية زراعة الشعر هي التغذية
                                    السليمة، التي تزود الجسم بالفيتامينات والمعادن اللازمة لنمو
                                    الشعر وتقويته.
                                </p>

                                <ul class="list">
                                    <li>
                                        <img
                                            src="{{ asset('frontend/assets/images/blogs/user.png')}}"
                                            loading="lazy"
                                            class="full-radius-img"
                                            alt=""
                                        />

                                        <span> بقلم دكتور فراس </span>
                                    </li>

                                    <li>
                                        <img
                                            src="{{ asset('frontend/assets/images/sub-header/calendar-white.svg')}}"
                                            loading="lazy"
                                            alt=""
                                        />

                                        <span> 22 أكتوبر 2024 </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('frontend/assets/images/sub-header/banner.png')}}" loading="lazy" class="banner-img" alt=""/>
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
                                    <img src="{{ asset('frontend/assets/images/sub-header/category.svg')}}" loading="lazy" alt=""/>
                                    فئات
                                </h1>

                                <ul class="list">
                                    <!-- categories -->
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
                                    <img src="{{ asset('frontend/assets/images/sub-header/stickynote.svg')}}" loading="lazy" alt=""/>
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
                            <img src="{{ asset('frontend/assets/images/sub-header/advertisment.png')}}" loading="lazy" alt="">
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
                                            <h2 style="height: 100px">{{ $post->title }}</h2>

                                            <ul class="list">
                                                 <li>
                                                    <img src="{{ asset('frontend/assets/images/blogs/user.png')}}" loading="lazy" class="full-radius-img" alt=""/>
                                                    <span> {{ $post->admin->name }} </span>
                                                </li>

                                                <li>
                                                    <img src="{{ asset('frontend/assets/images/sub-header/calendar.svg')}}" loading="lazy" class="light-filter" alt=""/>
                                                    <span> {{ $post->created_at->format('d/m/Y') }} </span>
                                                </li>
                                            </ul>

                                            <a href="{{ route('single-blog', $post->id) }}" class="see-more">
                                                <img src="{{ asset('frontend/assets/images/sub-header/arrow-left.svg')}}" loading="lazy" alt=""/>
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
