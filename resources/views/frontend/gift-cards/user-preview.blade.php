@extends('frontend.layouts.app')

@section('content')
    @include('frontend.gift-cards._user-card', ['giftCard' => $giftCard])
@endsection