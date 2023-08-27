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
</head>
<body>
<div class="cs-container">
    <div class="cs-invoice cs-style1">
        <div class="cs-invoice_in" id="download_section">
            <div class="cs-invoice_head cs-mb25">
                <div class="cs-invoice_left">
                    {!! $qr_code !!}
                </div>
                <div class="cs-invoice_right cs-text_right">
                    <div class="cs-logo cs-mb5"><img src="{{ asset('landing/img/guapa-logo.svg') }}" alt="Guapa Logo" style="width: 25%"></div>
                </div>
            </div>
            <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">Seller Information</div>
            <ul class="cs-grid_row cs-col_3 cs-mb5">
                <li>
                    <p class="cs-mb20"><span>Name</span></p>
                    <p class="cs-mb20"><span>Email</span></p>
                    <p class="cs-mb20"><span>VAT Number</span></p>
                    <p class="cs-mb20"><span>Commercial Reg. Number</span></p>
                </li>
                <li>
                    <p class="cs-mb20"><span>{{ $order->vendor->name }}</span></p>
                    <p class="cs-mb20"><span>{{ $order->vendor->email }}</span></p>
                    <p class="cs-mb20"><span>{{ $order->vendor->tax_number }}</span></p>
                    <p class="cs-mb20"><span>{{ $order->vendor->cat_number }}</span></p>
                </li>
                <li>
                    <p class="cs-text_right cs-mb20"><span>الاسم</span></p>
                    <p class="cs-text_right cs-mb20"><span>البريد الإلكتروني</span></p>
                    <p class="cs-text_right cs-mb20"><span>رقم التسجيل الضريبي</span></p>
                    <p class="cs-text_right cs-mb20"><span>رقم السجل التجاري</span></p>
                </li>
            </ul>

            <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">Invoice Information</div>
            <ul class="cs-grid_row cs-col_3 cs-mb5">
                <li>
                    <p class="cs-mb20"><span>Invoice Number</span></p>
                    <p class="cs-mb20"><span>Invoice Date</span></p>
                </li>
                <li style="width: max-content">
                    <p class="cs-mb20"><span>{{ "$invoice->id-$invoice->invoice_id" }}</span></p>
                    <p class="cs-mb20"><span>{{ $invoice->created_at }}</span></p>
                </li>
                <li>
                    <p class="cs-text_right cs-mb20"><span>رقم الفاتورة</span></p>
                    <p class="cs-text_right cs-mb20"><span>تاريخ الفاتورة</span></p>
                </li>
            </ul>

            <div class="cs-table cs-style2">
                <div class="cs-round_border">
                    <div class="cs-table_responsive">
                        <table>
                            <thead>
                            <tr class="cs-focus_bg">
                                <th class="cs-width_10 cs-semi_bold cs-primary_color">تفاصيل المنتج<br>Details</th>
                                <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_right">السعر<br>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="cs-width_10">{{ $item->product->title }}
                                        - {{ $item->product->description }} </td>
                                    <td class="cs-width_2 cs-text_right cs-primary_color">{{ $item->amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="cs-table cs-style2">
                <div class="cs-table_responsive">
                    <table>
                        <tbody>
                        <tr class="cs-table_baseline">
                            <td class="cs-width_5 cs-text_right">
                                <p class="cs-primary_color cs-bold cs-f16 cs-m0">Total Amount</p>
                            </td>
                            <td class="cs-width_2 cs-text_rightcs-f16">
                                <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ $order->total }}</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="cs-note">
                <div class="cs-note_left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <path d="M416 221.25V416a48 48 0 01-48 48H144a48 48 0 01-48-48V96a48 48 0 0148-48h98.75a32 32 0 0122.62 9.37l141.26 141.26a32 32 0 019.37 22.62z"
                              fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                        <path d="M256 56v120a32 32 0 0032 32h120M176 288h160M176 368h160" fill="none"
                              stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                    </svg>
                </div>
                <div class="cs-note_right">
                    <p class="cs-mb0"><b class="cs-primary_color cs-bold">Note:</b></p>
                    <p class="cs-m0">An amount of {{ number_format($invoice->amount / 100, 2) }} riyals was paid as
                        administrative expenses for using the Jawaba application only, and this does not include the
                        price of the service provided <br>
                        تم دفع
                        مبلغ {{ number_format($invoice->amount / 100, 2) }} ريال كمصاريف ادرايه لاستخدام تطبيق جوابا فقط
                        وهذا لا
                        يشمل ثمن الخدمة المقدمة</p>
                </div>
            </div><!-- .cs-note -->
            <div class="cs-invoice_btns cs-hide_print">
                <button id="download_btn" class="cs-invoice_btn cs-color2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>
                            Download</title>
                        <path d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40"
                              fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="32"/>
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="32" d="M176 272l80 80 80-80M256 48v288"/>
                    </svg>
                    <span>Download</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset("js/invoice/jquery.min.js") }}"></script>
<script src="{{ asset("js/invoice/jspdf.min.js") }}"></script>
<script src="{{ asset("js/invoice/html2canvas.min.js") }}"></script>
<script src="{{ asset("js/invoice/main.js") }}"></script>
</body>
</html>
