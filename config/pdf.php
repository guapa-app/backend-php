<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
    'tempDir'               => base_path('storage/app/public/mpdf'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
    'pdfWrapper'            => 'misterspelik\LaravelPdf\Wrapper\PdfWrapper',
    'defaultCssFile'        => public_path('css/invoice.css'),
    'font_path'             => public_path('fonts/dejavu-sans'),
    'font_data'             => [
                                'dejavu-sans'  => [
                                    'R'        => 'DejaVuSans.ttf',
                                    'B'        => 'DejaVuSans-Bold.ttf',
                                    'useOTL'     => 0xFF,
                                    'useKashida' => 75
                                ]
    ]
];
