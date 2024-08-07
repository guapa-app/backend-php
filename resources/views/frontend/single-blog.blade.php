@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('blog')) }}
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
                                    <a href="{{ route('landing') }}">
                                        الرئيسية
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('blogs') }}">
                                        المدونة
                                    </a>
                                </li>
                            </ul>

                            <h1>
                                {{ $post->title }}
                            </h1>

                            <p>
                                {{ Str::limit($post->content, 150) }}
                            </p>
                        </div>
                    </div>
                </div>

                <img src="{{ asset('frontend/assets/images/blogs/blog.svg') }}" class="icon" loading="lazy"
                    alt="" />
            </div>

            <img src="{{ asset('frontend/assets/images/icons/sub_header.png') }}" class="sub-header-img" loading="lazy"
                alt="" />
        </section>

        <section class="single-blog general-section">
            <div class="container">
                <div class="image-contain">
                    <img src="{{ asset('frontend/assets/images/blogs/blog_3.png') }}" loading="lazy" alt="" />
                </div>

                <div class="contain">
                    <div class="flex-data">

                        <div class="user-img">
                            <img src="{{ asset('frontend/assets/images/blogs/user.png') }}" loading="lazy" alt="" />

                            <span>
                                {{ $post->admin->name }}
                            </span>
                        </div>
                    </div>

                    <h2>
                        {{ $post->title }}
                    </h2>

                    <div class="flex-data">
                        <span class="badge" style="width: auto;">
                            {{ $post->category->title }}
                        </span>

                        <div class="date">
                            <img src="{{ asset('frontend/assets/images/icons/date.svg') }}" loading="lazy"
                                alt="" />

                            <span>
                                تاريخ النشر: {{ $post->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    {!! $post->content !!}
                </div>

            </div>
        </section>
    </main>
@endsection
