@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('ready')) }}
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
                                    <span>

                                    </span>
                                </li>
                            </ul>

                            <h1>

                            </h1>

                            <p>

                            </p>
                        </div>
                    </div>
                </div>

                <img src="{{ asset('frontend/assets/images/questions/question_pattern.svg') }}" class="icon" loading="lazy"
                    alt="" />
            </div>

            <img src="{{ asset('frontend/assets/images/icons/sub_header.png') }}" class="sub-header-img" loading="lazy"
                alt="" />
        </section>
    </main>
@endsection
