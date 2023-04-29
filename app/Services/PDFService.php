<?php

namespace App\Services;

use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    function generatePDF($order)
    {
        $invoice = $order->invoice;

        TCPDF::SetRTL(true);
        TCPDF::AddPage('L', 'A4');
        TCPDF::SetFont('aealarabiya', '', 12);
        TCPDF::SetTitle('Guapa-invoice');
        TCPDF::WriteHTML(view('invoice', compact('invoice', 'order'))->render());

        $filename = '/public/invoices/' . $invoice->id . '_' . rand(1000, 9999) . '.pdf';

        $pdf_file = TCPDF::Output($filename, 'S');

        Storage::disk('s3')->put($filename, $pdf_file, 'public');

        return config('filesystems.disks.s3.url') . "{$filename}";
    }

    function deletePDF($url)
    {
        return Storage::disk('s3')->delete($url);
    }
}
