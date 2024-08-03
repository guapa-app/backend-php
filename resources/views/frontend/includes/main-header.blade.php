<div class="middle-header-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <div class="middle-header-logo">
                    <a href="{{ route('welcome') }}" title="{{ ucwords(__("texts.".config('app.name'))) }}">
                        <h1 style="color: #ffffff">{{ ucwords(__("texts.".config('app.name'))) }}</h1>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 style="color: #ffffff">{{ ucfirst(__("texts.we handle all service activities with customers in mind")) }}</h5>
                {{--<div class="middle-header-search">
                    <form>
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select>
                                        <option>{{ ucwords(__('texts.all_categories')) }}</option>
                                        @foreach(\App\Models\Category::all() as $index => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="search-box">
                                    <input class="form-control" placeholder="{{ ucfirst(__('texts.search')) }}..." type="text">
                                    <button type="submit"><i class='bx bx-search'></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>--}}
            </div>
            <div class="col-lg-3">
                <ul class="middle-header-optional">
                    <li>
                        <a title="{{ ucfirst(__('texts.wishlist')) }}" href="{{ route('wishlist') }}"><i class="flaticon-heart"></i><span>{{ $wishlistItemsCount }}</span></a>
                    </li>
                    <li>
                        <a title="{{ ucfirst(__('texts.cart')) }}" href="{{ route('cart') }}"><i class="flaticon-shopping-cart"></i><span>{{ $cart->products->count() }}</span></a>
                    </li>
                    @if($cart->price)<li>{{ $cart->price .' '. $currency}}</li>@endif
                </ul>
            </div>
        </div>
    </div>
</div>
