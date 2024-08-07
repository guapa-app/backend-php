@php use Illuminate\Support\Str; @endphp
@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('blogs')) }}
@endsection
@section('content')
    <main>
        <section class="sub-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-12">
                        <div class="contain">
                            <ul class="list">
                                <li>
                                    <a href={{ route('landing') }}>
                                        الرئيسية
                                    </a>
                                </li>

                                <li>
                                    <span>
                                        المدونة
                                    </span>
                                </li>
                            </ul>

                            <h1>
                                المدونة
                            </h1>

                            <p>
                                لوريم إيبسوم هو نموذج إفتراضي يتم وضعه في التصاميم التي سيتم عرضها على العميل. وبعد
                                موافقة
                                العميل على بداية التصميم يتم إزالة هذا النص من التصميم ويتم وضع النصوص النهائية المطلوبة
                                للتصميم.
                            </p>
                        </div>
                    </div>
                </div>

                <img src="{{ asset('frontend/assets/images/blogs/blog.svg') }}" class="icon" loading="lazy" alt="" />
            </div>

            <img src="{{ asset('frontend/assets/images/icons/sub_header.png') }}" class="sub-header-img" loading="lazy"
                alt="" />
        </section>

        <section class="blogs general-section border-shape">
            <div class="container">
                <div class="row">
                    @foreach ($posts as $post)
                        <div class="col-lg-4 col-12 mb-3">
                            <div class="box">
                                <div class="image-contain">
                                    @if ($post->getFirstMediaUrl('posts', 'medium'))
                                        <img src="{{ $post->getFirstMediaUrl('posts', 'medium') }}" loading="lazy"
                                            alt="" />
                                    @else
                                        <img src="{{ asset('landing-v2/images/blogs/img_1.png') }}" loading="lazy"
                                            alt="" />
                                    @endif
                                </div>

                                <div class="contain">
                                    <h2>{{ $post->title }}</h2>
                                    <p>{{ Str::limit(strip_tags($post->content, false), 100) }}</p>

                                    <div class="flex-data">
                                        <div class="date">
                                            <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy"
                                                alt="" />

                                            <span> تاريخ النشر: {{ $post->created_at->format('d/m/Y') }}</span>
                                        </div>

                                        <a href="{{ route('single-blog', $post->id) }}">
                                            <span>
                                                إقرأ المزيد
                                            </span>
                                            <img src="{{ asset('frontend/assets/images/icons/left-arrow.svg') }}"
                                                loading="lazy" alt="" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
        </section>
    </main>
@endsection
