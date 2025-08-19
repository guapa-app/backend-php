<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Exports\VendorExport;
use Maatwebsite\Excel\Facades\Excel;

class VendorExportPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.vendor-export-page';

    protected static ?string $navigationLabel = 'Export Vendors';

    protected static ?string $navigationGroup = 'Exports';

    public function submit()
    {
        $export = new VendorExport();
        return Excel::download($export, 'vendors.xlsx');
    }
}
