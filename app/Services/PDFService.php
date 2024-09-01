<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Storage;
use Prgayman\Zatca\Facades\Zatca;
use PDF;

class PDFService
{
    public function addInvoicePDF(Order $order)
    {
        $cus_name = $order->user->name;

        $invoice = $order->invoice;

        $vat = Setting::getTaxes();

        $order_items = $order->items->map(function ($item) use ($vat) {
            $arr['name'] = $item->title;
            $arr['price'] = $item->amount_to_pay;
            $arr['vat'] = $arr['price'] * $item->taxes / 100;
            $arr['qty'] = $item->quantity;
            $arr['subtotal_with_vat'] = ($arr['price'] + $arr['vat']) * $arr['qty'];

            return $arr;
        });

        $data = [
            'invoice' => $invoice,
            'cus_name' => $cus_name,
            'order_items' => $order_items,
            'vat' => $vat
        ];

        $pdf = PDF::loadView('invoice-pdf', $data);
        $pdfContent = $pdf->output();

        // Define a unique file name for the PDF
        $fileName = 'mpdf/invoices/invoice_' . time() . '.pdf';

        // Upload the PDF to S3
        Storage::disk('s3')->put($fileName, $pdfContent, 'public');

        // Get the URL of the uploaded PDF
        $url = Storage::disk('s3')->url($fileName);

        return $url;
    }



    public function generatePDF($order)
    {
        $invoice = $order->invoice;

        abort_if($order->invoice == null, 405, 'There is no invoice for this order');

        $qr_code = $this->generateQRCode($invoice);

        TCPDF::SetRTL(true);
        TCPDF::AddPage('L', 'A4');
        TCPDF::SetFont('aealarabiya', '', 12);
        TCPDF::SetTitle('Guapa-invoice');
        TCPDF::WriteHTML(view('invoice', compact('invoice', 'order', 'qr_code'))->render());

        $filename = '/public/invoices/' . $invoice->id . '_' . rand(1000, 9999) . '.pdf';

        $pdf_file = TCPDF::Output($filename, 'S');

        Storage::disk('s3')->put($filename, $pdf_file, 'public');

        return config('filesystems.disks.s3.url') . "{$filename}";
    }

    public function generateQRCode($invoice)
    {
        return Zatca::sellerName($invoice->vendor_name)
            ->vatRegistrationNumber($invoice->vendor_reg_num)
            ->timestamp($invoice->created_at)
            ->totalWithVat($invoice->amount_format)
            ->vatTotal($invoice->taxes)
            ->toQrCode();
    }

    public function deletePDF($url)
    {
        return Storage::disk('s3')->delete($url);
    }
}
