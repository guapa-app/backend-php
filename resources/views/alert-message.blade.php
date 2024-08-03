<style>
    .alert-notification {
        -webkit-animation: seconds 1.0s forwards;
        -webkit-animation-iteration-count: 1;
        -webkit-animation-delay: 5s;
        animation: seconds 1.0s forwards;
        animation-iteration-count: 1;
        animation-delay: 3s;
        position: relative;
        text-align: center;
    }
    @-webkit-keyframes seconds {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            left: -9999px;
            position: absolute;
        }
    }
    @keyframes seconds {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            left: -9999px;
            position: absolute;
        }
    }
</style>
<div class="alert-notification">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @elseif ($message = Session::get('warning'))
        <div class="alert alert-warning alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @elseif ($message = Session::get('info'))
        <div class="alert alert-info alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @elseif ($message = Session::get('ops'))
        <div class="alert alert-dismissible alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @elseif (is_string($errors))
        <div class="alert alert-secondary">
            @lang('texts.'.$errors)
        </div>
    @elseif ($errors->any())
        <div class="alert alert-secondary">
            @lang('texts.check_errors')
        </div>
    @endif
</div>
