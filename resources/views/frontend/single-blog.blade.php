@extends('frontend.layouts.app')

@php
    app()->setLocale('ar');
@endphp

@section('title')
    {{ ucfirst(__('blog')) }}
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
            <img src="{{ $post->getFirstMediaUrl('posts', 'large') }}" class="sub-header-img" loading="lazy" alt=""/>
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
                                    alt=""
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
                            <img src="{{ asset('frontend/assets/images/sub-header/advertisment.png')}}" loading="lazy" alt=""/>
                        </div>
                    </div>

                    <div class="col-lg-9 col-12 mb-3">
                        <div class="contain">
                            <h2 class="mt-0">
                                {{ $post->title }}
                            </h2>

                            <ul class="list">
                                <li>
                                    <img src="{{ asset('frontend/assets/images/blogs/user.png')}}" loading="lazy" class="full-radius-img" alt=""/>
                                    <span> {{ $post->admin->name }} </span>
                                </li>

                                <li>
                                    <img src="{{ asset('frontend/assets/images/sub-header/calendar.svg')}}" loading="lazy" class="light-filter" alt=""/>
                                    <span> {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('l j F Y') }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="image-contain">
                            <img src="{{ $post->getFirstMediaUrl('posts', 'large') }}" loading="lazy" alt="" />

                            <a href="{{ $post->youtube_url }}" data-fancybox class="video-icon">
                                <img src="{{ asset('frontend/assets/images/icons/video_play.svg') }}" loading="lazy" alt="" />
                            </a>
                        </div>

                        <div class="contain">
                            {!! $post->content !!}
                        </div>

                        <div class="image-contain semi-large">
                            <img src="{{ $post->getFirstMediaUrl('posts', 'medium') }}" loading="lazy" alt=""/>
                        </div>

                        <div class="row">
                            @foreach($post->getMedia('posts') as $index => $media)
                                @if($index > 0)
                                    <div class="col-lg-6 col-6 mb-3">
                                        <div class="image-contain small-img">
                                            <img src="{{ $media->getUrl() }}" loading="lazy" alt=""/>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
