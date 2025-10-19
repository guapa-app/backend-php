@extends('frontend.layouts.app')

@php
    app()->setLocale('ar');
@endphp

@section('title')
    {{ $post->title }} | مدونة قوابا
@endsection
@section('heads')
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit(strip_tags($post->content, false), 160) }}">
    <meta name="keywords" content="{{ $post->category?->title }}, معلومات طبية, استشارات طبية, {{ $post->title }}, صحة">
    <meta name="author" content="{{ $post->admin->name }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Arabic">
    <meta property="og:title" content="{{ $post->title }} | مدونة قوابا">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->content, false), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $post->getFirstMediaUrl('posts', 'large') ?: asset('frontend/assets/images/sub-header/sub-header.png') }}">
    <meta property="og:article:published_time" content="{{ $post->created_at->toIso8601String() }}">
    <meta property="og:article:author" content="{{ $post->admin->name }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }} | مدونة قوابا">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($post->content, false), 160) }}">
    <meta name="twitter:image" content="{{ $post->getFirstMediaUrl('posts', 'large') ?: asset('frontend/assets/images/sub-header/sub-header.png') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Existing Snapchat Pixel Script -->
    <script type='text/javascript'>
        (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
        {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
            a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
            r.src=n;var u=t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r,u);})(window,document,
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
                        <div class="info-data no-bk">
                            <span class="badge">{{ $post->category?->title }}</span>
                            <h1>{{ $post->title }}</h1>
                            <p>{{ Str::limit(strip_tags($post->content, false), 50) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ $post->getFirstMediaUrl('posts', 'large') }}" class="sub-header-img" loading="lazy" alt="{{ $post->title ?? 'مقال في مدونة قوابا' }}"/>
        </section>

        <section class="single-blog blogs general-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-12 mb-3">
{{--                        <div class="filter-data">
                            <h1 class="head">
                                <img
                                    src="{{ asset('frontend/assets/images/sub-header/note.svg')}}"
                                    loading="lazy"
                                    alt="أيقونة المحتوى - قوابا"
                                />

                                المحتوي
                            </h1>

                            <ul class="list">
                                <li>
                                    <a href="#">
                                      <span class="data">
                                        عنوان
                                      </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                      <span class="data">عنوان اخر</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <span class="data">عنوان اخر</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <span class="data">عنوان اخر</span>
                                    </a>
                                </li>
                            </ul>
                        </div>--}}
                        <div class="annoucment">
                            <img src="{{ asset('frontend/assets/images/sub-header/advertisment.png')}}" loading="lazy" alt="إعلان قوابا للخدمات التجميلية"/>
                        </div>
                    </div>

                    <div class="col-lg-9 col-12 mb-3">
                        <div class="contain">
                            <h2 class="mt-0">
                                {{ $post->title }}
                            </h2>

                            <ul class="list">
                                <li>
                                    <img src="{{ asset('frontend/assets/images/blogs/user.png')}}" loading="lazy" class="full-radius-img" alt="صورة الكاتب {{ $post->admin->name }} - قوابا"/>
                                    <span> {{ $post->admin->name }} </span>
                                </li>

                                <li>
                                    <img src="{{ asset('frontend/assets/images/sub-header/calendar.svg')}}" loading="lazy" class="light-filter" alt="أيقونة التاريخ - قوابا"/>
                                    <span> {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('l j F Y') }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="image-contain">
                            <img src="{{ $post->getFirstMediaUrl('posts', 'large') }}" loading="lazy" alt="{{ $post->title }} - مدونة قوابا" />

                            <a href="{{ $post->youtube_url }}" data-fancybox class="video-icon">
                                <img src="{{ asset('frontend/assets/images/icons/video_play.svg') }}" loading="lazy" alt="زر تشغيل الفيديو - قوابا"/>
                            </a>
                        </div>

                        <div class="contain">
                            {!! $post->content !!}
                        </div>

                        <div class="image-contain semi-large">
                            <img src="{{ $post->getFirstMediaUrl('posts', 'medium') }}" loading="lazy" alt="{{ $post->title }} - مدونة قوابا"/>
                        </div>

                        <div class="row">
                            @foreach($post->getMedia('posts') as $index => $media)
                                @if($index > 0)
                                    <div class="col-lg-6 col-6 mb-3">
                                        <div class="image-contain small-img">
                                            <img src="{{ $media->getUrl() }}" loading="lazy" alt="{{ $post->title }} - صورة {{ $index + 1 }} - مدونة قوابا"/>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "headline": "{{ $post->title }}",
            "image": "{{ $post->getFirstMediaUrl('posts', 'large') ?: asset('frontend/assets/images/sub-header/sub-header.png') }}",
            "author": {
                "@type": "Person",
                "name": "{{ $post->admin->name }}"
            },
            "publisher": {
                "@type": "Organization",
                "name": "قوابا",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset('frontend/assets/images/logo.png') }}"
                }
            },
            "datePublished": "{{ $post->created_at->toIso8601String() }}",
            "dateModified": "{{ $post->updated_at->toIso8601String() }}",
            "description": "{{ Str::limit(strip_tags($post->content, false), 160) }}",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "{{ url()->current() }}"
            }
        }
        </script>
    </main>
@endsection