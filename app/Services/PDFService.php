<?php

namespace App\Services;

use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Storage;
use Prgayman\Zatca\Facades\Zatca;

class PDFService
{
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
