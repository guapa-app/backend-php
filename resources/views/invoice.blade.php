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
                        <div class="cs-logo cs-mb5"><img class="invoice-qr-code" src="{{ asset('landing/img/guapa-qr-code.png') }}" alt="Guapa Qr Code"></div>
                    </div>
                    <div class="text-in-same-line invoice-desc">
                        <b>Simplified Tax Invoice</b>
                        <br>
                        <b>فاتورة ضريبة مبسطة</b>
                    </div>
                    <div class="cs-invoice_right cs-text_right">
                        <div class="cs-logo cs-mb5"><img class="invoice-logo" src="{{ asset('landing/img/guapa-logo.svg') }}" alt="Guapa Logo"></div>
                    </div>
                </div>
                <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">Guapa Information</div>
                <ul class="cs-grid_row cs-col_3 cs-mb5 text-in-same-line">
                    <li>
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
                    <li>
                        <p class="cs-primary_color cs-text_right cs-mb40">
                            <b>الأسم</b>
                            <br>
                            <span class="cs-primary_color">مؤسسة مجتمع الجمال للخدمات التسويقية</span>
                        </p>
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
                        </p>
                        <p class="cs-primary_color cs-text_right cs-mb40">
                            <b>رقم السجل التجاري</b>
                            <br>
                            <span class="cs-primary_color">۱۰۱۰۷۰۸٦۹۳</span>
                        </p>
                        </p>
                    </li>
                </ul>

                <div class="cs-heading cs-style1 cs-f18 cs-primary_color cs-mb25 cs-semi_bold">Invoice Information</div>

                <ul class="cs-grid_row cs-col_3 cs-mb5">
                    <li>
                        <p class="cs-mb20"><span>Invoice Number</span></p>
                        <p class="cs-mb20"><span>Invoice Date</span></p>
                        <p class="cs-mb20"><span>Customer Name</span></p>
                    </li>
                    <li class="text-in-same-line cs-text_center">
                        <p class="cs-mb20"><span>{{ "$invoice->id-$invoice->invoice_id" }}</span></p>
                        <p class="cs-mb20"><span>{{ $invoice->created_at }}</span></p>
                        <p class="cs-mb20"><span>{{ $cus_name }}</span></p>
                    </li>
                    <li>
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
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Details
                                            <br>تفاصيل المنتج
                                        </th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Price
                                            <br>السعر
                                        </th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_center">VAT({{ $vat }}%)
                                            <br>ضريبة القيمة المضافة
                                        </th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Quantity
                                            <br>الكمية
                                        </th>
                                        <th class="cs-width_2 cs-semi_bold cs-primary_color cs-text_center">Item Subtotal With VAT
                                            <br>المجموع شامل ضريبة القيمة المضافة
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order_items as $item)
                                    <tr>
                                        <td class="cs-width_2">{{ $item['name'] }} </td>
                                        <td class="cs-width_2 cs-text_center cs-primary_color">{{ round($item['price'], 2) }}</td>
                                        <td class="cs-width_2 cs-text_center cs-primary_color">{{ $item['vat'] }}</td>
                                        <td class="cs-width_2 cs-text_center cs-primary_color">{{ $item['qty'] }}</td>
                                        <td class="cs-width_2 cs-text_center cs-primary_color">{{ $item['subtotal_with_vat'] }}</td>
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
                            <tbody>
                                <tr class="cs-table_baseline">
                                    <td class="cs-width_5 cs-text_left">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0">Total Amount (Excluding VAT)</p>
                                    </td>
                                    <td class="cs-width_2 cs-text_center cs-f16">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ round(($invoice->amount_without_taxes / 100), 2) }} <small>SAR</small></p>
                                    </td>
                                    <td class="cs-width_5 cs-text_right">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0">المبلغ بدون ضريبة القيمة المضافة</p>
                                    </td>
                                </tr>
                                <tr class="cs-table_baseline">
                                    <td class="cs-width_5 cs-text_left">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0">Total VAT</p>
                                    </td>
                                    <td class="cs-width_2 cs-text_center cs-f16">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ round(($invoice->taxes / 100), 2) }} <small>SAR</small></p>
                                    </td>
                                    <td class="cs-width_5 cs-text_right">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0">إجمالي ضريبة القيمة المضافة</p>
                                    </td>
                                </tr>
                                <tr class="cs-table_baseline">
                                    <td class="cs-width_5 cs-text_left">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0">Total Amount</p>
                                    </td>
                                    <td class="cs-width_2 cs-text_center cs-f16">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0 cs-text_right">{{ round($invoice->amount_format, 2) }} <small>SAR</small></p>
                                    </td>
                                    <td class="cs-width_5 cs-text_right">
                                        <p class="cs-primary_color cs-bold cs-f16 cs-m0">المبلغ الإجمالي</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="cs-note">
                    <div class="cs-note_left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M416 221.25V416a48 48 0 01-48 48H144a48 48 0 01-48-48V96a48 48 0 0148-48h98.75a32 32 0 0122.62 9.37l141.26 141.26a32 32 0 019.37 22.62z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M256 56v120a32 32 0 0032 32h120M176 288h160M176 368h160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                    </div>
                    <div class="cs-note_right">
                        <p class="cs-mb0"><b class="cs-primary_color cs-bold">Note:</b></p>
                        <p class="cs-m1">
                            - The amount paid represents the marketing fees for Guapa’s services. You must complete the payment of the remaining amount of the coupon value to the service provider to receive the service mentioned in the coupon.
                            <br>- The coupon and the service or product provided within it are subject to the terms of the service provider mentioned on the page of the coupon or product mentioned.
                            <br>- The terms and conditions of the application, as well as the conditions for return, are applied as mentioned in the General Terms and Conditions and Usage Policies section in the application.
                        </p>
                        <p class="cs-m0 ar-info">
                            - المبلغ المدفوع يمثل الاجور التسويقية لخدمات قوابا. يجب اكمال دفع المبلغ المتبقي من قيمة الكوبون لدئ مزود الخدمة لتلقي الخدمة المذكورة في الكوبون.
                            <br>- يخضع الكوبون و الخدمة المقدمة فيه او المنتج لشروط مزود الخدمة المذكورة في الصفحة الخاصة بالكوبون او المنتج المذكور.
                            <br>- يتم تطبيق الشروط و الاحكام الخاصة بالتطبيق و كذلك الشروط الخاصة بالاسترجاع كما هو مذكور في خانة الشروط و الاحكام العامة و سياسات الاستخدام في التطبيق   
                        </p>
                    </div>
                </div><!-- .cs-note -->
                <div class="cs-invoice_btns cs-hide_print">
                    <button id="download_btn" class="cs-invoice_btn cs-color2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <title>
                                Download</title>
                            <path d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M176 272l80 80 80-80M256 48v288" />
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