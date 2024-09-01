<?php

namespace App\Imports;

use App\Helpers\Common;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable;

    private $failures = [];

    public function __construct()
    {
    }

    public function model(array $row)
    {
        return [
            'name' => $row['name'],
            'phone' => $row['phone'],
        ];
    }

    public function rules(): array
    {
        return [
            'name' =>  'required|string|min:3|max:100',
            'phone' => 'required|string|' . (Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation()),
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function getFailures()
    {
        return $this->failures;
    }
}
