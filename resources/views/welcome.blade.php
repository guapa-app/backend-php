<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} Navigation</title>

    <style id="" media="all">
        @font-face {
            font-family: 'Josefin Sans';
            font-style: normal;
            font-weight: 400;
            src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUAnx4RHw.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        /* latin-ext */
        @font-face {
            font-family: 'Josefin Sans';
            font-style: normal;
            font-weight: 400;
            src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUA3x4RHw.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        /* latin */
        @font-face {
            font-family: 'Josefin Sans';
            font-style: normal;
            font-weight: 400;
            src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUDXx4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* vietnamese */
        @font-face {
            font-family: 'Josefin Sans';
            font-style: normal;
            font-weight: 700;
            src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUAnx4RHw.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
        }

        /* latin-ext */
        @font-face {
            font-family: 'Josefin Sans';
            font-style: normal;
            font-weight: 700;
            src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUA3x4RHw.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        /* latin */
        @font-face {
            font-family: 'Josefin Sans';
            font-style: normal;
            font-weight: 700;
            src: url(/fonts.gstatic.com/s/josefinsans/v17/Qw3aZQNVED7rKGKxtqIqX5EUDXx4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>

    <link rel="shortcut icon" href="{{ asset("logo.png")}}" type="image/x-icon"/>
    <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/4.1.0/firebaseui.css"/>
    <link type="text/css" rel="stylesheet" href="https://colorlib.com/etc/404/colorlib-error-404-8/css/style.css"/>
    <meta name="robots" content="noindex, follow">
</head>
<body>
<div id="notfound">
    <div class="notfound">
        <img src="{{ asset("logo.png")}}" alt="logo">
        <br>
        <br>
        <p>Administrator Panels</p>
        <a href="/admin">React</a>
        <a href="/admin-panel">Nova</a>
        <br>
        <br>
        <p>Vendors Application</p>
        <a href="https://play.google.com/store/apps/details?id=com.app.guapa_provider">Android</a>
        <a href="https://apps.apple.com/us/app/id1549047437">Ios</a>
        <br>
        <br>
        <p>Users Application</p>
        <a href="https://play.google.com/store/apps/details?id=com.guapa.app">Android</a>
        <a href="https://apps.apple.com/us/app/id1552554758">Ios</a>
        <br>
        <br>
        <p>Doc & Landing</p>
        <a href="/docs">Api Docs</a>
        <a href="{{ route('landing') }}">Landing Page</a>
        <br>
        <br>
        <p class="mt-8 text-center text-xs text-80">
            &copy; {{ date('Y') }} {{ config('app.name') }}
            <span class="px-1">&middot;</span>
            v{{ env('DEPLOY_VERSION', '0.0.0') }}
        </p>

        <div class="phone-auth" style="margin-top: 50px;display: none;">
            <div id="firebaseui-auth-container"></div>
        </div>
    </div>
</div>
</body>


<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.4.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.4.0/firebase-auth.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#config-web-app -->

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyDqXH24Uj1EJJyyJb0dJ2nJM8NimCIaV2w",
        authDomain: "cosmo-567e4.firebaseapp.com",
        databaseURL: "https://cosmo-567e4.firebaseio.com",
        projectId: "cosmo-567e4",
        storageBucket: "cosmo-567e4.appspot.com",
        messagingSenderId: "203201234921",
        appId: "1:203201234921:web:b337a93dcb38f5c6316d78"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>
<script src="https://cdn.firebase.com/libs/firebaseui/4.1.0/firebaseui.js"></script>
<script type="text/javascript">
    // FirebaseUI config.
    var uiConfig = {
        signInSuccessUrl: '#',
        signInOptions: [
            // Leave the lines as is for the providers you want to offer your users.
            // firebase.auth.GoogleAuthProvider.PROVIDER_ID,
            // firebase.auth.FacebookAuthProvider.PROVIDER_ID,
            // firebase.auth.TwitterAuthProvider.PROVIDER_ID,
            // firebase.auth.GithubAuthProvider.PROVIDER_ID,
            // firebase.auth.EmailAuthProvider.PROVIDER_ID,
            firebase.auth.PhoneAuthProvider.PROVIDER_ID,
            // firebaseui.auth.AnonymousAuthProvider.PROVIDER_ID
        ],
        // tosUrl and privacyPolicyUrl accept either url string or a callback
        // function.
        // Terms of service url/callback.
        tosUrl: '<your-tos-url>',
        // Privacy policy url/callback.
        privacyPolicyUrl: function () {
            window.location.assign('<your-privacy-policy-url>');
        }
    };

    // Initialize the FirebaseUI Widget using Firebase.
    var ui = new firebaseui.auth.AuthUI(firebase.auth());
    // The start method will wait until the DOM is loaded.
    ui.start('#firebaseui-auth-container', uiConfig);

    firebase.auth().onAuthStateChanged(function (user) {
        if (user) {
            var phone = user.phoneNumber
            user.getIdToken().then(function (accessToken) {
                console.log(accessToken)
            })
        }
    });

    // // Firebase access token
    // var accessToken = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjY0MWU3OWQzZjUwOWUyYzdhNjQ1N2ZjOTVmY2U1MGNjOGM3M2VmMDMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL3NlY3VyZXRva2VuLmdvb2dsZS5jb20vaGVyZS1jZjY1NSIsImF1ZCI6ImhlcmUtY2Y2NTUiLCJhdXRoX3RpbWUiOjE1NjYyNjM2MDAsInVzZXJfaWQiOiJtZWJFMlZyZFZrZFRRQzNtRlp5VkdMaDIxOVEyIiwic3ViIjoibWViRTJWcmRWa2RUUUMzbUZaeVZHTGgyMTlRMiIsImlhdCI6MTU2NjI2OTk1OSwiZXhwIjoxNTY2MjczNTU5LCJwaG9uZV9udW1iZXIiOiIrMjAxMDY0OTMxNTk3IiwiZmlyZWJhc2UiOnsiaWRlbnRpdGllcyI6eyJwaG9uZSI6WyIrMjAxMDY0OTMxNTk3Il19LCJzaWduX2luX3Byb3ZpZGVyIjoicGhvbmUifX0.Lrm-axgmEdeDerwYWilsPUluYO_fHXyIdsUiIx67WwelnCLIb-MkQ64QuuFaec7keILfFQz_R59EWK0oJgjMcCPU6s_9UQrKt5wz3XZyE9ot25xw3Jvv74ClWASFe-qb-nUoM5moDDuFDUAc-jIXW_LA9pW0va4LRcKFLlcaC3EX_GM94VcI7UGTtEGVgfLg4-kwERF_8PCKtZSDzBKplkVmbKB654xqV0PAIdc2QTdTdL8KHRFSTuQ0TV16lIXS6Y3QcbKyMDnjZp8XOmOhyb-1UJBoHYaLyrToEc7MXeZwCxePRRRZTC-7--Tuw5xspEkCopLRT9_q6IMMGIIDvQ';
    // var phoneNumber = '+201064931597'

    // axios.post('http://deal.test/api/v1/auth/login', {
    //   firebase_jwt_token: accessToken,
    //   phone_number: phoneNumber,
    // }).then(function(rt) {
    //   console.log(rt)
    // }).catch(function(err) {
    //   console.log(err)
    //   console.log(err.response)
    // })
</script>


</html>
