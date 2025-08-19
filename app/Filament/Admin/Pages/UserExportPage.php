<?php

namespace App\Filament\Admin\Pages;

use App\Exports\UserExport;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class UserExportPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.user-export-page';

    protected static ?string $navigationLabel = 'Export Users';

    protected static ?string $navigationGroup = 'Exports';

    public function submit()
    {
        $export = new UserExport();
        return Excel::download($export, 'users.xlsx');
    }
}
