<!-- resources/views/redirect.blade.php -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body>
<script type="text/javascript">
    if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
        window.location = "{{ $iosAppLink }}";
        setTimeout(function () {
            window.location = "{{ $appStoreLink }}";
        }, 25);
    } else if (navigator.userAgent.match(/Android/i)) {
        window.location = "{{ $androidAppLink }}";
        setTimeout(function () {
            window.location = "{{ $playStoreLink }}";
        }, 25);
    } else {
        window.location = "{{ $webUrl }}";
    }
</script>
<noscript>
    <meta http-equiv="refresh" content="0;url={{ $webUrl }}">
</noscript>
</body>
</html>
