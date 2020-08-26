<?php

namespace App\Exports;

use App\VMarcacion;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VMarcacionExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $fini;
    private $ffin;

    /**
     * VMarcacionExport constructor.
     * @param $fini
     * @param $ffin
     */
    public function __construct($fini, $ffin)
    {
        $this->fini = $fini;
        $this->ffin = $ffin;
    }


    public function collection()
    {

        $f1 = "";
        $f2 = "";
        if (empty($this->fini) || empty($this->ffin)) {
            $f1 = date("Y-m-d");
            $f2 = new DateTime('+1 day');
        } else {
            $f1 = $this->fini;
            $f2 = new DateTime($this->ffin);
            $f2->modify('+1 day');

        }


        return $asistencias = VMarcacion::query()->whereBetween("fecha", [$f1, $f2->format('Y-m-d')])->get();


    }

    public function headings(): array
    {
        return [
            'DNI',
            'NOMBRES',
            'APELLIDOS',
            'OT',
            'FECHA'
        ];
    }
}
