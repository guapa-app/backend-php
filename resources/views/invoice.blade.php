<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ config('app.name') }} Invoice</title>

    <style>
        .header {
            text-align: center;
        }

        .footer {
            background-color: #eeeeee;
            height: 1in;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #eeeeee;
        }

        .guapa_details img {
            width: 1.5cm;
            height: 1.5cm;
        }

    </style>
</head>

<body>
<main>
    <div class="header">
        <div class="guapa_details">
            <a style="color: #6c388f; text-decoration: none" href="https://guapa.com.sa">
                <img src="{{ asset('landing/img/Logo-1.png') }}" alt="logo-1">
            </a>
        </div>
    </div>

    <h4 style="font-size: 26px; text-align: center; color: #666; margin: 0"><strong>{{ $order->vendor->name }}</strong>
    </h4>
    <ul>
        <li>{{ $order->vendor->phone  }} </li>
        <li>{{ $order->vendor->email  }} </li>
        <li>{{ $order->vendor->about  }} </li>
    </ul>


    <div></div>

    <table class="table" style="margin: 30px 0;">
        <tr>
            <td style="text-align: center; height: 0.32in; color: #222">اسم المنتج</td>
            <td style="text-align: center; height: 0.32in; color: #222">تفاصيل</td>
            <td style="text-align: center; height: 0.32in; color: #222">السعر</td>
        </tr>
        @foreach ($order->items as $item)
            <tr>
                <td style="text-align: center; height: 0.32in; color: #555">{{ $item->product->title }}</td>
                <td style="text-align: center; height: 0.32in; color: #555">{{ $item->product->description }}</td>
                <td style="text-align: center; height: 0.32in; color: #555">{{ $item->amount }}</td>
            </tr>
        @endforeach
    </table>

    <div></div>

    <div class="footer">
        <h2 style="color: #444444; margin: 0; text-align: center">الإجمالي: {{ $order->total }} ريال سعودي</h2>
        <br>
        <h2 style="color: #444444; margin: 0; text-align: center"> تم دفع
            مبلغ {{ number_format($invoice->amount / 100, 2) }} ريال كمصاريف ادرايه لاستخدام تطبيق جوابا فقط وهذا لا
            يشمل ثمن الخدمة المقدمة</h2>
    </div>
</main>
</body>

</html>
