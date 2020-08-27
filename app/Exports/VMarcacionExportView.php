<?php

namespace App\Exports;

use App\VMarcacion;
use App\VMarcacionDia;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class VMarcacionExportView implements FromView
{

    public function view(): View
    {

        return view('pages.exports.marcacion-dia', [
            'data' => VMarcacionDia::all()
        ]);
    }
}
