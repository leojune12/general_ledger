<?php

namespace App\Exports;

use App\Http\Resources\LedgerExportResource;
use App\Http\Resources\LedgerResource;
use App\Models\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LedgerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    */
    /*protected $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }*/

    public function collection()
    {
        //return Ledger::all();
        //return LedgerResource::collection(Ledger::all());
        return LedgerExportResource::collection(Ledger::all());
    }

    public function headings(): array
    {
        return [
            'Date Encoded',
            'Account Code',
            'Project Code',
            'Amount',
            'Description',
        ];
    }
}
