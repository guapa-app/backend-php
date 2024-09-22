<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد استلام الدفع</title>
    <style>
        body, html {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            background-color: #fa6c6d;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .logo {
            margin-bottom: 15px;
        }
        .logo img {
            max-width: 150px;
        }
        .content {
            padding: 20px;
            direction: rtl;
        }
        .content p {
            line-height: 1.8;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">
            <img src="{{ asset('frontend/assets/images/logo/logo.svg') }}" alt="Guapa Logo">
        </div>
        <h2>تأكيد استلام الدفع - رقم الطلب  #{{ $order->id }}</h2>
    </div>
    <div class="content">
        <h3>مرحبًا {{ $recipientType == 'customer' ? $order->user->name : '' }},</h3>
        <h4>نشكر لك ثقتك. نود أن نعلمكم أن الطلب التالي : #{{ $order->id }} تم دفعه بنجاح. :</h4>
        <p>تفاصيل الطلب كما يلي</p>
        <p><strong>رقم الطلب:</strong> {{ $order->id }}</p>
        @if($recipientType != 'customer')
            <p><strong>اسم العميل:</strong> {{ $order->user->name }}</p>
        @endif
        <p><strong>مقدم الخدمة:</strong> {{ $order->vendor?->name }}</p>
        <p><strong>إسم المنتج:</strong>
            @foreach ($order->items as $item)
                {{ $item->product?->title }}@if (!$loop->last) - @endif
            @endforeach
        </p>
        <p><strong>تاريخ الطلب:</strong> {{ $order->created_at }}</p>
        <p><strong>المبلغ المدفوع:</strong> {{ $order->paid_amount_with_taxes  }} SAR</p>
        <p><strong>المبلغ المتبقي:</strong> {{ $order->remaining_amount }} SAR</p>

        <p>إذا كان لديك أي استفسار أو تحتاج إلى مساعدة إضافية، لا تتردد في التواصل معنا على <a href="mailto:info@guapa.com.sa">info@guapa.com.sa</a> أو الاتصال على <a href="tel:9665314343889">9665314343889</a> أو الاتصال على  <a
                .</p>

        <p>شكرًا لتعاملك معنا، ونتطلع إلى خدمتك مجددًا.</p>

        <p>تفضل بقبول فائق الاحترام،</p>
        <p>فريق قوابا</p>
    </div>
    <div class="footer">
        <p>guapa.com.sa | 5314343889</p>
    </div>
</div>
</body>
</html>
