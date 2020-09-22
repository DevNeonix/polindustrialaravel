<?php

namespace App\Exports;

use App\VMarcacion;
use App\VMarcacionDia;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class VMarcacionExportView implements FromView, WithColumnWidths,ShouldAutoSize
{

    public function view(): View
    {

        return view('pages.exports.marcacion-dia', [
            'data' => VMarcacionDia::all()
        ]);
    }

    public function columnWidths(): array
    {
        return ['A' => 350,];

    }
}
