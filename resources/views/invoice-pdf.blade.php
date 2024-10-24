<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="view  rt" content="width=device-width, initial-scale=1">
    <meta name="author" content="ThemeMarch">
    <!-- Site Title -->
    <title>{{ config('app.name') }} Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
    <style>
        body,
        html {
            font-family: 'dejavu-sans', 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.5em;
            overflow-x: hidden;
            background-color: #f5f7ff;
        }

        .invoice-desc {
            text-align: center;
        }

        .invoice-qr-code {
            width: 10%
        }

        .invoice-logo {
            width: 10%;
            margin-bottom: 10%;
        }

        .s-custom-font {
            font-size: 10px;
        }

        .cs-invoice.cs-style1 {
            padding: 10px;
        }
    </style>
</head>

<body>
<div class="cs-invoice_in" id="download_section">
    <div class="cs-invoice_head" style="margin-bottom: -8%">
        <div class="cs-invoice_left">
            <div class="cs-logo cs-mb5">
                <img class="invoice-qr-code" src="{{ asset('landing/img/guapa-qr-code.png') }}" alt="Guapa Qr Code">
            </div>
        </div>
        <div class="text-in-same-line invoice-desc" style="font-size: 12px; margin-top: -10.5%">
            <b>Simplified Tax Invoice</b>
            <br>
            <b>فاتورة ضريبة مبسطة</b>
        </div>
        <div class="cs-invoice_right cs-text_right" style="margin-top: -6%">
            <div class="cs-logo cs-mb5"><img class="invoice-logo" src="{{ asset('landing/img/guapa-logo.svg') }}"
                                             alt="Guapa Logo"></div>
        </div>
    </div>
    <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">Guapa Information</div>
    <ul class="cs-grid_row cs-col_3 cs-mb5 text-in-same-line">
        <li style="font-size: 12px" class="s-custom-font">
            <p class="cs-primary_color cs-mb40">
                <b>Name</b>
                <br>
                <span>Beauty Society Foundation for Marketing Services</span>
            </p>
            <p class="cs-primary_color cs-mb40">
                <b>Address</b>
                <br>
                <span>7262 Al-Mashhad Dhahrat Laban District 3529</span>
            </p>
            <p class="cs-primary_color cs-mb40">
                <b>Unified number</b>
                <br>
                <span>7023326908</span>
            </p>
            <p class="cs-primary_color cs-mb40">
                <b>Commercial Reg. Number</b>
                <br>
                <span>1010708693</span>
            </p>
        </li>
        <li></li>
        <li style="font-size: 12px; margin-top: -30.5%">
            <p class="cs-primary_color cs-text_right cs-mb40">
                <b>الأسم</b>
                <br>
                <span class="cs-primary_color">مؤسسة مجتمع الجمال للخدمات التسويقية</span>
            </p>
            <p class="cs-primary_color cs-text_right cs-mb40">
                <b>العنوان</b>
                <br>
                <span class="cs-primary_color">
                                <span class="ar-info">۷۲٦۲</span>
                                <span>المشهد حي ظهرة لبن</span>
                                <span class="ar-info">۳٥۲۹</span>
                            </span>
            </p>
            <p class="cs-primary_color cs-text_right cs-mb40">
                <b>الرقم الموحد</b>
                <br>
                <span class="cs-primary_color">۷۰۲۳۳۲٦۹۰۸</span>
            </p>
            <p class="cs-primary_color cs-text_right cs-mb40">
                <b>رقم السجل التجاري</b>
                <br>
                <span class="cs-primary_color">۱۰۱۰۷۰۸٦۹۳</span>
            </p>
        </li>
    </ul>

    <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">Invoice Information</div>

    <ul class="cs-grid_row cs-col_3 cs-mb5">
        <li style="font-size: 12px;">
            <p class="cs-mb20"><span>Invoice Number</span></p>
            <p class="cs-mb20"><span>Invoice Date</span></p>
            <p class="cs-mb20"><span>Customer Name</span></p>
        </li>
        <li class="text-in-same-line cs-text_center" style="font-size: 12px; margin-top: -18%">
            <p class="cs-mb20"><span>{{ "$invoice->id-$invoice->invoice_id" }}</span></p>
            <p class="cs-mb20"><span>{{ $invoice->created_at }}</span></p>
            <p class="cs-mb20"><span>{{ $cus_name }}</span></p>
        </li>
        <li style="font-size: 12px; margin-top: -18%">
            <p class="cs-text_right cs-mb20"><span>رقم الفاتورة</span></p>
            <p class="cs-text_right cs-mb20"><span>تاريخ الفاتورة</span></p>
            <p class="cs-text_right cs-mb20"><span>أسم العميل</span></p>
        </li>
    </ul>

    <div class="cs-table cs-style2">
        <div class="cs-round_border">
            <div class="cs-table_responsive">
                <table>
                    <thead>
                    <tr class="cs-focus_bg text-in-same-line">
                        <th class="s-custom-font cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Details
                            <br>تفاصيل المنتج
                        </th>
                        <th class="s-custom-font cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Price
                            <br>السعر
                        </th>
                        <th class="s-custom-font cs-width_2 cs-semi_bold cs-primary_color cs-text_center">VAT({{ $vat }}
                            %)
                            <br>ضريبة القيمة المضافة
                        </th>
                        <th class="s-custom-font cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Quantity
                            <br>الكمية
                        </th>
                        <th class="s-custom-font cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Item Subtotal
                            With VAT
                            <br>المجموع شامل ضريبة القيمة المضافة
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($order_items as $item)
                        <tr>
                            <td class="s-custom-font cs-width_2">{{ $item['name'] }} </td>
                            <td class="s-custom-font cs-width_2 cs-text_center cs-primary_color">{{ round($item['price'], 2) }}</td>
                            <td class="s-custom-font cs-width_2 cs-text_center cs-primary_color">{{ $item['vat'] }}</td>
                            <td class="s-custom-font cs-width_2 cs-text_center cs-primary_color">{{ $item['qty'] }}</td>
                            <td class="s-custom-font cs-width_2 cs-text_center cs-primary_color">{{ $item['subtotal_with_vat'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cs-table cs-style2">
        <div class="cs-table_responsive">
            <table class="text-in-same-line">
                <tbody class="s-custom-font">
                <tr class="cs-table_baseline">
                    <td class="cs-width_5 cs-text_left">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0">Total Amount (Excluding VAT)</p>
                    </td>
                    <td class="cs-width_2 cs-text_center cs-f16">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ round(($invoice->amount_without_taxes), 2) }}
                            <small>SAR</small></p>
                    </td>
                    <td class="cs-width_5 cs-text_right">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0">المبلغ بدون ضريبة القيمة
                            المضافة</p>
                    </td>
                </tr>
                <tr class="cs-table_baseline">
                    <td class="cs-width_5 cs-text_left">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0">Total VAT</p>
                    </td>
                    <td class="cs-width_2 cs-text_center cs-f16">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ round(($invoice->taxes), 2) }}
                            <small>SAR</small></p>
                    </td>
                    <td class="cs-width_5 cs-text_right">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0">إجمالي ضريبة القيمة المضافة</p>
                    </td>
                </tr>
                <tr class="cs-table_baseline">
                    <td class="cs-width_5 cs-text_left">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0">Total Amount</p>
                    </td>
                    <td class="cs-width_2 cs-text_center cs-f16">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ round((float)$invoice->amount, 2) }}
                            <small>SAR</small></p>
                    </td>
                    <td class="cs-width_5 cs-text_right">
                        <p class="s-custom-font cs-primary_color cs-bold cs-f16 cs-m0">المبلغ الإجمالي</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <wbr>
    <div class="cs-note" style="margin-top: 1%">
        <div class="cs-note_right">
            <p class="cs-mb0"><b class="cs-primary_color cs-bold">Note:</b></p>
            <p class="cs-m1">
                - The amount paid represents the marketing fees for Guapa’s services. You must complete the payment of
                the remaining amount of the coupon value to the service provider to receive the service mentioned in the
                coupon.
                <br>- The coupon and the service or product provided within it are subject to the terms of the service
                provider mentioned on the page of the coupon or product mentioned.
                <br>- The terms and conditions of the application, as well as the conditions for return, are applied as
                mentioned in the General Terms and Conditions and Usage Policies section in the application.
            </p>
            <p class="cs-m0 ar-info">
                - المبلغ المدفوع يمثل الاجور التسويقية لخدمات قوابا. يجب اكمال دفع المبلغ المتبقي من قيمة الكوبون لدئ
                مزود الخدمة لتلقي الخدمة المذكورة في الكوبون.
                <br>- يخضع الكوبون و الخدمة المقدمة فيه او المنتج لشروط مزود الخدمة المذكورة في الصفحة الخاصة بالكوبون
                او المنتج المذكور.
                <br>- يتم تطبيق الشروط و الاحكام الخاصة بالتطبيق و كذلك الشروط الخاصة بالاسترجاع كما هو مذكور في خانة
                الشروط و الاحكام العامة و سياسات الاستخدام في التطبيق
            </p>
        </div>
    </div><!-- .cs-note -->
</div>
<script src="{{ asset("js/invoice/jquery.min.js") }}"></script>
<script src="{{ asset("js/invoice/jspdf.min.js") }}"></script>
<script src="{{ asset("js/invoice/html2canvas.min.js") }}"></script>
<script src="{{ asset("js/invoice/main.js") }}"></script>
</body>
</html>
